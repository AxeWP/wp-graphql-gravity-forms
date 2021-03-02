<?php
/**
 * GraphQL Object Type - ListFieldValue
 * Values for an individual List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Return early if value is null or empty.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\Field\ListField;

/**
 * Class - ListFieldValue
 */
class ListFieldValue implements Hookable, Type, FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = ListField::TYPE . 'Value';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
			add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
			register_graphql_object_type(
				self::TYPE,
				[
					'description' => __( 'List field values.', 'wp-graphql-gravity-forms' ),
					'fields'      => [
						'listValues' => [
							'type'        => [ 'list_of' => ListInputValue::TYPE ],
							'description' => __( 'Field values.', 'wp-graphql-gravity-forms' ),
						],
					],
				]
			);
	}

	/**
	 * Get the field values.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field values.
	 *
	 * @throws UserError .
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		$entry_values = isset( $entry[ $field['id'] ] ) ? unserialize( $entry[ $field['id'] ] ) : null;

		// Return null if no value is set, or unserialize creates an empty array.
		if ( empty( $entry_values ) ) {
			return [];
		}

		// Check if there are too many rows.
		if ( $field['maxRows'] < count( $entry_values ) ) {
			// translators: maximum number of rows.
			throw new UserError( sprintf( __( 'You may only submit %d rows.', 'wp-graphql-gravity-forms' ), $field['maxRows'] ) );
		}

		// If columns are enabled, save each row-value pair.
		if ( $field['enableColumns'] ) {

			// Get column names.
			$field_keys = wp_list_pluck( $field->choices, 'text' );

			// Save each row-value pair.
			$listValues = array_map(
				function( $row ) use ( $field_keys ) {
					$row_values = [];

					foreach ( $row as $key => $single_value ) {
						$row_values[] = $single_value[ $field_keys[ $key ] ];
					}

					return [ 'value' => $row_values ];
				},
				$entry_values
			);

			return compact( 'listValues' );
		}

		// If no columns, entry values can be mapped directly to 'value'.
		$listValues = array_map(
			function( $single_value ) {
				return [ 'value' => [ $single_value ] ]; // $single_value must be Iteratable.
			},
			$entry_values
		);

		return compact( 'listValues' );
	}
}
