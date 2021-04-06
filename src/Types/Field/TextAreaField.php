<?php
/**
 * GraphQL Object Type - TextAreaField
 *
 * @see https://docs.gravityforms.com/gf_field_textarea/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - TextAreaField
 */
class TextAreaField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'TextAreaField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'textarea';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Textarea (Paragraph Text) field.', 'wp-graphql-gravity-forms' );
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
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\MaxLengthProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'useRichTextEditor' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the field uses the rich text editor interface.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
