<?php
/**
 * GraphQL Object Type - HiddenField
 *
 * @see https://docs.gravityforms.com/gf_field_hidden/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Hidden field.
 *
 * @see https://docs.gravityforms.com/gf_field_hidden/
 */
class HiddenField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'HiddenField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'hidden';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Hidden field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			static::get_global_properties(),
			static::get_custom_properties(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
			/**
			* Deprecated field properties.
			*
			* @since 0.2.0
			*/

			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\IsRequiredProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\NoDuplicatesProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
