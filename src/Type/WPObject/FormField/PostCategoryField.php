<?php
/**
 * GraphQL Object Type - PostCategoryField
 *
 * @see https://docs.gravityforms.com/post-category/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - PostCategoryField
 */
class PostCategoryField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PostCategoryField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'post_category';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Post Category field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'displayAllCategories' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines if all categories should be displayed on the Post Category drop down. If this property is true (display all categories), the Post Category drop down will display the categories hierarchically.', 'wp-graphql-gravity-forms' ),
				],
			],
			... static::get_fields_from_gf_settings(),
		);
	}
}
