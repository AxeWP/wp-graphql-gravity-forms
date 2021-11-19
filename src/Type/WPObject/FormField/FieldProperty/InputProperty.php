<?php
/**
 * GraphQL Object Type - InputProperty
 * An individual input for the 'inputs' field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Utils\Utils;

/**
 * Class - InputProperty
 */
class InputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'InputProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms input property.', 'wp-graphql-gravity-forms' );
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
			 * @since 0.2.0
			 */

			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
