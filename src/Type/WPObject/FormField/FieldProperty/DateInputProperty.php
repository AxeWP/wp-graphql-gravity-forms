<?php
/**
 * GraphQL Object Type - DateInputProperty
 * An individual input in the Email field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_email/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.6.3
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - DateInputProperty
 */
class DateInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'DateInputProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'An array containing the the individual properties for each element of the Email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			AutocompleteAttributeProperty::get(),
			DefaultValueProperty::get(),
			LabelProperty::get(),
			PlaceholderProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.6.0
			 */
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( AutocompleteAttributeProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// @since 0.2.0 .
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
