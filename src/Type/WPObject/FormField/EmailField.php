<?php
/**
 * GraphQL Object Type - EmailField
 *
 * @see https://docs.gravityforms.com/gf_field_email/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - EmailField
 */
class EmailField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EmailField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'email';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			// For some reason GF doesnt include this in the settings.
			FieldPRoperty\AutocompleteAttributeProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			// For some reason GF doesnt include this in the settings.
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'emailConfirmEnabled' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines whether the Confirm Email field is active.', 'wp-graphql-gravity-forms' ),
				],
				'inputs'              => [
					'type'        => [ 'list_of' => FieldProperty\EmailInputProperty::$type ],
					'description' => __( 'An array containing the the individual properties for each element of the email field.', 'wp-graphql-gravity-forms' ),
				],
			],
			... static::get_fields_from_gf_settings(),
		);
	}
}
