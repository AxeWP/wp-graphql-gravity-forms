<?php
/**
 * GraphQL Object Type - ListField
 *
 * @see https://docs.gravityforms.com/gf_field_list/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - ListField
 */
class ListField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'list';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms List field.', 'wp-graphql-gravity-forms' );
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
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\LabelPlacementProperty::get(),
			FieldProperty\PageNumberProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'addIconUrl'    => [
					'type'        => 'String',
					'description' => __( 'The URL of the image to be used for the add row button.', 'wp-graphql-gravity-forms' ),
				],
				'choices'       => [
					'type'        => [ 'list_of' => FieldProperty\ListChoiceProperty::TYPE ],
					'description' => __( 'The column labels. Only used when enableColumns is true.', 'wp-graphql-gravity-forms' ),
				],
				'deleteIconUrl' => [
					'type'        => 'String',
					'description' => __( 'The URL of the image to be used for the delete row button.', 'wp-graphql-gravity-forms' ),
				],
				'enableColumns' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines if the field should use multiple columns. Default is false.', 'wp-graphql-gravity-forms' ),
				],
				'maxRows'       => [
					'type'        => 'Integer',
					'description' => __( 'The maximum number of rows the user can add to the field.', 'wp-graphql-gravity-forms' ),
				],
			]
		);
	}
}
