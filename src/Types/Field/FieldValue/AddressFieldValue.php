<?php
/**
 * GraphQL Object Type - AddressFieldValue
 * Values for an individual Address field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Class - AddressFieldValue
 */
class AddressFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'AddressFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms address field values.', 'wp-graphql-gravity-forms' );
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
			'street'  => [
				'type'        => 'String',
				'description' => __( 'Street address.', 'wp-graphql-gravity-forms' ),
			],
			'lineTwo' => [
				'type'        => 'String',
				'description' => __( 'Address line two.', 'wp-graphql-gravity-forms' ),
			],
			'city'    => [
				'type'        => 'String',
				'description' => __( 'City.', 'wp-graphql-gravity-forms' ),
			],
			'state'   => [
				'type'        => 'String',
				'description' => __( 'State / province.', 'wp-graphql-gravity-forms' ),
			],
			'zip'     => [
				'type'        => 'String',
				'description' => __( 'ZIP / postal code.', 'wp-graphql-gravity-forms' ),
			],
			'country' => [
				'type'        => 'String',
				'description' => __( 'Country.', 'wp-graphql-gravity-forms' ),
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
			'street'  => $entry[ $field['inputs'][0]['id'] ] ?? null,
			'lineTwo' => $entry[ $field['inputs'][1]['id'] ] ?? null,
			'city'    => $entry[ $field['inputs'][2]['id'] ] ?? null,
			'state'   => $entry[ $field['inputs'][3]['id'] ] ?? null,
			'zip'     => $entry[ $field['inputs'][4]['id'] ] ?? null,
			'country' => $entry[ $field['inputs'][5]['id'] ] ?? null,
		];
	}
}
