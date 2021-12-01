<?php
/**
 * GraphQL Object Type - NameField
 *
 * @see https://docs.gravityforms.com/gf_field_name/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - NameField
 */
class NameField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'NameField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'name';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Name field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'inputs'     => [
					'type'        => [ 'list_of' => FieldProperty\NameInputProperty::$type ],
					'description' => __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' ),
				],
				'nameFormat' => [
					'type'        => 'String',
					'description' => __( 'The format of the name field. Originally, the name field could be a “normal” format with just First and Last being the fields displayed or an “extended” format which included prefix and suffix fields, or a “simple” format which just had one input field. These are legacy formats which are no longer used when adding a Name field to a form. The Name field was modified in a way which allows each of the components of the normal and extended formats to be able to be turned on or off. The nameFormat is now only “advanced”. Name fields in the previous formats are automatically upgraded to the new type if the form field is modified in the admin. The code is backwards-compatible and will continue to handle the “normal”, “extended”, “simple” formats for fields which have not yet been upgraded.', 'wp-graphql-gravity-forms' ),
				],
			],
			... static::get_fields_from_gf_settings(),
		);
	}
}
