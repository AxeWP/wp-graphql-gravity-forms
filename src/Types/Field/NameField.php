<?php
/**
 * GraphQL Object Type - NameField
 *
 * @see https://docs.gravityforms.com/gf_field_name/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - NameField
 */
class NameField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'name';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Name field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputsProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'inputs'     => [
					'type'        => [ 'list_of' => FieldProperty\NameInputProperty::TYPE ],
					'description' => __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' ),
				],
				'nameFormat' => [
					'type'        => 'String',
					'description' => __( 'The format of the name field. Originally, the name field could be a “normal” format with just First and Last being the fields displayed or an “extended” format which included prefix and suffix fields, or a “simple” format which just had one input field. These are legacy formats which are no longer used when adding a Name field to a form. The Name field was modified in a way which allows each of the components of the normal and extended formats to be able to be turned on or off. The nameFormat is now only “advanced”. Name fields in the previous formats are automatically upgraded to the new type if the form field is modified in the admin. The code is backwards-compatible and will continue to handle the “normal”, “extended”, “simple” formats for fields which have not yet been upgraded.', 'wp-graphql-gravity-forms' ),
				],
			],
			/**
			* Deprecated field properties.
			*
			* @since 0.2.0
			*/

			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type. Please use `inputs { name }` instead.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
