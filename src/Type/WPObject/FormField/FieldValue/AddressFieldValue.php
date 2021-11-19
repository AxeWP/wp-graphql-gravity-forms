<?php
/**
 * GraphQL Object Type - AddressFieldValue
 * Values for an individual Address field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\AddressFieldValueProperty;

/**
 * Class - AddressFieldValue
 */
class AddressFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms address field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
		return AddressFieldValueProperty::get( $entry, $field );
	}
}
