<?php
/**
 * Mutation - UpdateGravityFormsEntry
 *
 * Updates a Gravity Forms entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Input\FieldValuesInput;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - UpdateEntry
 */
class UpdateEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGravityFormsEntry';

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public function get_input_fields() : array {
		return [
			'entryId'     => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The Gravity Forms entry id.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FieldValuesInput::$type ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'   => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been starred (i.e marked with a star).', 'wp-graphql-gravity-forms' ),
			],
			'isRead'      => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'createdBy'   => [
				'type'        => 'Int',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'status'      => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'The current status of the entry.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Defines the output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'entryId' => [
				'type'        => 'Int',
				'description' => __( 'The ID of the entry that was created. Null if the entry was only partially submitted or submitted as a draft.', 'wp-graphql-gravity-forms' ),
			],
			'entry'   => [
				'type'        => Entry::$type,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) {
					if ( ! empty( $payload['errors'] ) || ! $payload['entryId'] ) {
						return null;
					}

					$entry = GFUtils::get_entry( $payload['entryId'] );

					return EntryDataManipulator::manipulate( $entry );
				},
			],
			'errors'  => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Defines the data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Check for required fields.
			$this->check_required_inputs( $input );

			// Set default values.
			$entry      = GFUtils::get_entry( (int) $input['entryId'] );
			$this->form = GFUtils::get_form( $entry['form_id'] );

			$entry_data = $this->prepare_entry_data( $input, $entry );

			if ( ! empty( $this->errors ) ) {
				return [ 'errors' => $this->errors ];
			}

			$updated_entry_id = GFUtils::update_entry( $entry_data );

			$this->update_post( $updated_entry_id );

			return [
				'entryId' => $updated_entry_id,
			];
		};
	}

	/**
	 * Prepares entry object for update.
	 *
	 * @param array $input .
	 * @param array $entry .
	 * @return array
	 */
	private function prepare_entry_data( array $input, array $entry ) : array {
			$is_starred = $input['isStarred'] ?? null;
			$is_read    = $input['isRead'] ?? null;
			$ip         = empty( $this->form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$created_by = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : null;
			$status     = $input['status'] ?? null;

			$entry_properties = array_filter(
				[
					'is_starred' => $is_starred,
					'is_read'    => $is_read,
					'ip'         => $ip,
					'created_by' => $created_by,
					'status'     => $status,
				],
				fn( $property ) => (bool) strlen( $property )
			);

			$field_values = $this->prepare_field_values( $input['fieldValues'], $entry );

			return array_replace(
				$entry,
				$entry_properties,
				$field_values,
			);
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array $field_values .
	 * @param array $entry .
	 * @return array
	 */
	private function prepare_field_values( array $field_values, array $entry ) : array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field = GFUtils::get_field_by_id( $this->form, $values['id'] );

			$prev_value = $entry[ $values['id'] ] ?? null;

			$value = $this->prepare_single_field_value( $values, $field, $prev_value );

			// Signature field requires $_POST['input_{#}'] on update.
			if ( 'signature' === $field->type ) {
				$_POST[ 'input_' . $field->id ] = $value;
			}

			if ( 'post_image' === $field->type ) {
				// String follows pattern: `$url |:| $title |:| $caption |:|$description |:| $alt` .
				$url         = $value[ $field->id . '_0' ];
				$title       = $value[ $field->id . '_1' ];
				$caption     = $value[ $field->id . '_4' ];
				$description = $value[ $field->id . '_7' ];
				$alt         = $value[ $field->id . '_2' ];

				$formatted_values[ (string) $field->id ] = $url . '|:|' . $title . '|:|' . $caption . '|:|' . $description . '|:|' . $alt;
			}

			// Validate the field value.
			$this->validate_field_value( $field, $value );

			// Add values to array based on field type.
			$formatted_values = $this->add_value_to_array( $formatted_values, $field, $value );
		}

		$formatted_values = $this->prepare_field_values_for_save( $formatted_values, $entry );

		return $formatted_values;
	}

	/**
	 * Ensures required input fields are set.
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input = null ) : void {
		parent::check_required_inputs( $input );

		if ( ! isset( $input['entryId'] ) ) {
			throw new UserError( __( 'Mutation not processed. Entry ID not provided.', 'wp-graphql-gravity-forms' ) );
		}

		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Prepares field values before saving it to the entry.
	 *
	 * @param array $values the entry values.
	 * @param array $entry the existing entry.
	 * @return array
	 */
	public function prepare_field_values_for_save( array $values, array $entry ) : array {
		foreach ( $values as $id => &$value ) {
			$input_name = 'input_' . str_replace( '.', '_', $id );
			$field_id   = strtok( $id, '.' );
			$field      = GFUtils::get_field_by_id( $this->form, (int) $field_id );

			$value = GFFormsModel::prepare_value( $this->form, $field, $value, $input_name, $entry['id'], $entry );
		}

		return $values;
	}

	/**
	 * Updates the post associated with the entry.
	 *
	 * @param integer $entry_id .
	 */
	public function update_post( int $entry_id ) : void {
		$entry = GFUtils::get_entry( $entry_id );
		add_filter( 'gform_post_data', [ $this, 'set_post_id_for_update' ], 10, 3 );
		GFFormsModel::create_post( $this->form, $entry );
		remove_filter( 'gform_post_data', [ $this, 'set_post_id_for_update' ] );
	}

	/**
	 * Sets the post id so GFFormsModel::create_post updates the post instead of creating a new one.
	 *
	 * @param array $post_data .
	 * @param array $form .
	 * @param array $entry .
	 */
	public function set_post_id_for_update( array $post_data, array $form, array $entry ): array {
		$post_data['ID'] = $entry['post_id'];
		return $post_data;
	}
}
