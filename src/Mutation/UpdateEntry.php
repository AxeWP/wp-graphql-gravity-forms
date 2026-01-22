<?php
/**
 * Mutation - UpdateGfEntry
 *
 * Updates a Gravity Forms entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Mutation;

use GFCommon;
use GFFormsModel;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\EntryObjectMutation;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Input\FormFieldValuesInput;
use WPGraphQL\GF\Type\Input\UpdateEntryMetaInput;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - UpdateEntry
 */
class UpdateEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGfEntry';

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array{id:int,message:string}[]
	 */
	protected static array $errors = [];

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'             => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => static fn () => __( 'ID of the entry to update, either a global or database ID.', 'wp-graphql-gravity-forms' ),
			],
			'entryMeta'      => [
				'type'        => UpdateEntryMetaInput::$type,
				'description' => static fn () => __( 'The entry meta values to update.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues'    => [
				'type'        => [ 'list_of' => FormFieldValuesInput::$type ],
				'description' => static fn () => __( 'The field ids and their values to update.', 'wp-graphql-gravity-forms' ),
			],
			'shouldValidate' => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Whether the field values should be validated on submission. Defaults to false.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'entry'  => [
				'type'        => SubmittedEntry::$type,
				'description' => static fn () => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( array $payload, array $args, AppContext $context ) {
					if ( ! empty( $payload['errors'] ) || empty( $payload['entryId'] ) ) {
						return null;
					}

					return Factory::resolve_entry( (int) $payload['entryId'], $context );
				},
			],
			'errors' => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => static fn () => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload(): callable {
		return static function ( $input ): array {
			// Check for required fields.
			static::check_required_inputs( $input );

			// Get the entry.
			$entry_id = Utils::get_entry_id_from_id( $input['id'] );
			$entry    = GFUtils::get_entry( $entry_id );

			// Check if user has permissions.
			if (
				! GFCommon::current_user_can_any( 'gravityforms_edit_entries' ) &&
				! empty( $entry['created_by'] )
				&& get_current_user_id() !== absint( $entry['created_by'] )
			) {
				throw new UserError( esc_html__( 'Sorry, you are not allowed to edit entries.', 'wp-graphql-gravity-forms' ) );
			}

			// Prepare the entry data.
			$form = GFUtils::get_form( (int) $entry['form_id'] );

			$entry_data = self::prepare_entry_data( $input, $entry, $form );

			// Return early if field errors.
			if ( ! empty( $entry_data['errors'] ) ) {
				return $entry_data;
			}

			$updated_entry_id = GFUtils::update_entry( $entry_data );

			self::update_post( $updated_entry_id, $form );

			return [
				'entryId' => $updated_entry_id,
			];
		};
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected static function check_required_inputs( $input = null ): void {
		if ( ! empty( $input['entryMeta'] ) && empty( $input['fieldValues'] ) ) {
			throw new UserError( esc_html__( 'Mutation not processed. No data provided to update.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Prepares entry object for update.
	 *
	 * @param array<string,mixed>     $input The GraphQL input.
	 * @param array<int|string,mixed> $entry The entry array.
	 * @param array<string,mixed>     $form The form array.
	 *
	 * @return array<int|string,mixed> The prepared entry data.
	 * @throws \GraphQL\Error\UserError .
	 */
	private static function prepare_entry_data( array $input, array $entry, array $form ): array {
		$should_validate = isset( $input['shouldValidate'] ) ? (bool) $input['shouldValidate'] : true;

		// Initialize files.
		$all_files = EntryObjectMutation::initialize_files( $form['fields'], $input['fieldValues'] ?? [], false );

		if ( ! empty( $all_files ) ) {
			$_POST['gform_uploaded_files'] = wp_json_encode( $all_files );
			GFFormsModel::set_uploaded_files( $form['id'] );
		}

		// Update Field values.
		$field_values = ! empty( $input['fieldValues'] ) ? self::prepare_field_values( $input['fieldValues'], $entry, $form, $should_validate ) : [];

		if ( ! empty( self::$errors ) ) {
			return [ 'errors' => self::$errors ];
		}

		// Update Created by id.
		if ( isset( $input['entryMeta']['createdById'] ) ) {
			if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_entries' ) ) {
				throw new UserError( esc_html__( 'Sorry, you do not have permission to change the Entry user.', 'wp-graphql-gravity-forms' ) );
			}
			$entry['created_by'] = absint( $input['entryMeta']['createdById'] );
		}

		// Update Date created.
		if ( isset( $input['entryMeta']['dateCreatedGmt'] ) ) {
			$entry['date_created'] = sanitize_text_field( $input['entryMeta']['dateCreatedGmt'] );
		}

		// Update IP.
		if ( isset( $input['entryMeta']['ip'] ) ) {
			$ip          = empty( $form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['entryMeta']['ip'] ?? '' ) : '';
			$entry['ip'] = ! empty( $ip ) ? sanitize_text_field( $ip ) : $entry['ip'];
		}

		// Update isRead.
		if ( isset( $input['entryMeta']['isRead'] ) ) {
			$entry['is_read'] = ! empty( $input['entryMeta']['isRead'] );
		}

		// Update isStarred.
		if ( isset( $input['entryMeta']['isStarred'] ) ) {
			$entry['is_starred'] = ! empty( $input['entryMeta']['isStarred'] );
		}

		// Update source url.
		if ( isset( $input['entryMeta']['sourceUrl'] ) ) {
			$entry['source_url'] = sanitize_text_field( $input['entryMeta']['sourceUrl'] );
		}

		// Update status.
		if ( isset( $input['entryMeta']['status'] ) ) {
			$entry['status'] = sanitize_text_field( $input['entryMeta']['status'] );
		}

		// Update user agent.
		if ( isset( $input['entryMeta']['userAgent'] ) ) {
			$entry['user_agent'] = sanitize_text_field( $input['entryMeta']['userAgent'] );
		}

		return array_replace(
			$entry,
			$field_values,
		);
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array<string,mixed>[]   $field_values .
	 * @param array<int|string,mixed> $entry The entry array.
	 * @param array<string,mixed>     $form The form array.
	 * @param bool                    $should_validate .
	 *
	 * @return array<int|string,mixed> The prepared field values.
	 */
	private static function prepare_field_values( array $field_values, array $entry, array $form, bool $should_validate ): array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field_value_input = EntryObjectMutation::get_field_value_input( $values, $form, false, $entry );

			if ( $should_validate ) {
				$field_value_input->validate_value( self::$errors );
			}

			$field_value_input->add_value_to_submission( $formatted_values );
		}

		return self::prepare_field_values_for_save( $formatted_values, $entry, $form );
	}

	/**
	 * Prepares field values before saving it to the entry.
	 *
	 * @param array<int|string,mixed> $values the field values.
	 * @param array<int|string,mixed> $entry the existing entry.
	 * @param array<string,mixed>     $form The form array.
	 *
	 * @return array<int|string,mixed> The prepared values.
	 */
	public static function prepare_field_values_for_save( array $values, array $entry, array $form ): array {
		// We need the entry fresh to prepare the values.
		$entry = array_merge( $entry, $values );

		foreach ( $values as $id => &$value ) {
			$input_name = 'input_' . str_replace( '.', '_', (string) $id );
			$field_id   = strtok( (string) $id, '.' );
			$field      = GFUtils::get_field_by_id( $form, (int) $field_id );

			// Radio fields use the `_other` field for the other choice.
			if ( 'radio' === $field->get_input_type() && 'gf_other_choice' === $value ) {
				$value = $values[ $id . '_other' ];
			}

			// File upload values are already prepared by Initialize_files() in prepare_entry_data().
			if ( 'post_image' !== $field->type && 'fileupload' !== $field->type ) {
				$value = GFFormsModel::prepare_value( $form, $field, $value, $input_name, $entry['id'], $entry );
			}
		}

		return $values;
	}

	/**
	 * Grabs the updated entry, and then updates the post.
	 *
	 * @param int                 $entry_id .
	 * @param array<string,mixed> $form The form array.
	 */
	public static function update_post( int $entry_id, array $form ): void {
		$entry = GFUtils::get_entry( $entry_id );

		add_filter( 'gform_post_data', [ self::class, 'set_post_id_for_update' ], 10, 3 );
		GFFormsModel::create_post( $form, $entry );
		remove_filter( 'gform_post_data', [ self::class, 'set_post_id_for_update' ] );
	}

	/**
	 * Sets the post id so GFFormsModel::create_post updates the post instead of creating a new one.
	 *
	 * @param array<string,mixed>     $post_data .
	 * @param array<string,mixed>     $form The form array.
	 * @param array<int|string,mixed> $entry .
	 *
	 * @return array<string,mixed> The post data.
	 */
	public static function set_post_id_for_update( array $post_data, array $form, array $entry ): array {
		$post_data['ID'] = $entry['post_id'];
		return $post_data;
	}
}
