<?php
/**
 * GraphQL Field - AddressFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - AddressFieldValueProperty
 */
class AddressFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'AddressField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'addressValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Address field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : string {
		return AddressValueProperty::$type;
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
			'street'  => $entry[ $field->inputs[0]['id'] ] ?: null,
			'lineTwo' => $entry[ $field->inputs[1]['id'] ] ?: null,
			'city'    => $entry[ $field->inputs[2]['id'] ] ?: null,
			'state'   => $entry[ $field->inputs[3]['id'] ] ?: null,
			'zip'     => $entry[ $field->inputs[4]['id'] ] ?: null,
			'country' => $entry[ $field->inputs[5]['id'] ] ?: null,
		];
	}
}
