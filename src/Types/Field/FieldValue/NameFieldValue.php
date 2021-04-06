<?php
/**
 * GraphQL Object Type - NameFieldValue
 * Values for an individual Name field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Class - NameFieldValue
 */
class NameFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Name field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'prefix' => [
				'type'        => 'String',
				'description' => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
			],
			'first'  => [
				'type'        => 'String',
				'description' => __( 'First name.', 'wp-graphql-gravity-forms' ),
			],
			'middle' => [
				'type'        => 'String',
				'description' => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
			],
			'last'   => [
				'type'        => 'String',
				'description' => __( 'Last name.', 'wp-graphql-gravity-forms' ),
			],
			'suffix' => [
				'type'        => 'String',
				'description' => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
			return [
				'prefix' => ! empty( $entry[ $field['inputs'][0]['id'] ] ) ? $entry[ $field['inputs'][0]['id'] ] : null,
				'first'  => ! empty( $entry[ $field['inputs'][1]['id'] ] ) ? $entry[ $field['inputs'][1]['id'] ] : null,
				'middle' => ! empty( $entry[ $field['inputs'][2]['id'] ] ) ? $entry[ $field['inputs'][2]['id'] ] : null,
				'last'   => ! empty( $entry[ $field['inputs'][3]['id'] ] ) ? $entry[ $field['inputs'][3]['id'] ] : null,
				'suffix' => ! empty( $entry[ $field['inputs'][4]['id'] ] ) ? $entry[ $field['inputs'][4]['id'] ] : null,
			];
	}
}
