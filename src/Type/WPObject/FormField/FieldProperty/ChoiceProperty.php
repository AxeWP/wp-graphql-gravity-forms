<?php
/**
 * GraphQL Object Type - ChoiceProperty
 * An individual property for the 'choices' field property.
 *
 * @see https://docs.gravityforms.com/field-object/#basic-properties
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Refactor ChoiceProperty for reuse.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - ChoiceProperty
 */
class ChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChoiceProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			ChoiceProperty\ChoiceIsSelectedProperty::get(),
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
		);
	}
}
