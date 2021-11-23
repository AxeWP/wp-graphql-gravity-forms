<?php
/**
 * GraphQL Object Type - PasswordInputProperty
 * An individual input in the Password field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_password/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 * @since   0.3.0 Add isHidden property.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;

/**
 * Class - PasswordInputProperty
 */
class PasswordInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PasswordInputProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'An array containing the the individual properties for each element of the password field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			PlaceholderProperty::get(),
			LabelProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputIsHiddenProperty::get(),
		);
	}
}
