<?php
/**
 * GraphQL Object Type - PostCategoryField
 *
 * @see https://docs.gravityforms.com/post-category/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - PostCategoryField
 */
class PostCategoryField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PostCategoryField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'post_category';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Post Category field.', 'wp-graphql-gravity-forms' );
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
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'displayAllCategories' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines if all categories should be displayed on the Post Category drop down. If this property is true (display all categories), the Post Category drop down will display the categories hierarchically.', 'wp-graphql-gravity-forms' ),
				],
			]
		);
	}
}
