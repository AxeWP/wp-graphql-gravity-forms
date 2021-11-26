<?php
/**
 * Mutation - UpdateGravityFormsDraftEntry
 *
 * Updates a Gravity Forms draft entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Mutation;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Type\Input\FieldValuesInput;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - UpdateDraftEntry
 */
class UpdateDraftEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGravityFormsDraftEntry';

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected static array $errors = [];

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields() : array {
		return [
			'resumeToken' => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FieldValuesInput::$type ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'createdBy'   => [
				'type'        => 'Int',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'   => [
				'type'        => 'String',
				'description' => __( 'Optional. Used to overwrite the sourceUrl the form was submitted from.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft entry resume token.', 'wp-graphql-gravity-forms' ),
			],
			'entry'       => [
				'type'        => Entry::$type,
				'description' => __( 'The draft entry after the update mutation has been applied. If a validation error occurred, the draft entry will NOT have been updated with the invalid value provided.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload, array $args, AppContext $context ) {
					if ( ! empty( $payload['errors'] ) || ! $payload['resumeToken'] ) {
						return null;
					}
					return Factory::resolve_draft_entry( $payload['resumeToken'], $context );
				},
			],
			'errors'      => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl'   => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Check for required fields.
			static::check_required_inputs( $input );

			$resume_token = sanitize_text_field( $input['resumeToken'] );
			$submission   = GFUtils::get_draft_submission( $resume_token );
			$form         = GFUtils::get_form( $submission['partial_entry']['form_id'] );

			$values = self::prepare_field_values( $input['fieldValues'], $form, $submission['partial_entry'], $submission );
			if ( ! empty( self::$errors ) ) {
				return [ 'errors' => self::$errors ];
			}

			$submission['partial_entry'] = array_replace( $submission['partial_entry'], $values );
			$submission['field_values']  = array_replace( $submission['field_values'] ?? [], FormSubmissionHelper::rename_keys_for_field_values( $values ) );

			$ip                                = empty( $form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$submission['partial_entry']['ip'] = ! empty( $ip ) ? $ip : $submission['partial_entry']['ip'];

			$submission['partial_entry']['created_by'] = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : $submission['partial_entry']['created_by'];

			$resume_token = GFUtils::save_draft_submission(
				$form,
				$submission['partial_entry'],
				$submission['field_values'],
				$submission['page_number'] ?? 1, // @TODO: Maybe get from request.
				$submission['files'] ?? [],
				$submission['gform_unique_id'] ?? null,
				$submission['partial_entry']['ip'],
				$input['source_url'] ?? $submission['partial_entry']['source_url'] ?? '',
				$resume_token
			);

			return [
				'resumeToken' => $resume_token,
				'resumeUrl'   => GFUtils::get_resume_url( $submission['partial_entry']['source_url'], $resume_token, $form ),
			];
		};
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array $field_values .
	 * @param array $form .
	 * @param array $entry .
	 * @param array $submission .
	 */
	private static function prepare_field_values( array $field_values, array $form, array $entry, array &$submission ) : array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field = GFUtils::get_field_by_id( $form, $values['id'] );

			$prev_value = $entry[ $values['id'] ] ?? null;

			$value = FormSubmissionHelper::prepare_single_field_value( $values, $field, $prev_value );

			// Validate the field value.
			FormSubmissionHelper::validate_field_value( $value, $field, $form, self::$errors );

			// Add field values to submitted values.
			$submission['submitted_values'][ $field->id ] = $value;

			// Add values to array based on field type.
			$formatted_values = FormSubmissionHelper::add_value_to_array( $formatted_values, $field, $value );
		}

		return $formatted_values;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	protected static function check_required_inputs( $input = null ) : void {
		parent::check_required_inputs( $input );

		if ( ! isset( $input['resumeToken'] ) ) {
			throw new UserError( __( 'Mutation not processed. Resume token not provided.', 'wp-graphql-gravity-forms' ) );
		}

		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
