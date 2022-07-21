<?php
/**
 * GraphQL Interface for a FormField with the `post_category_checkbox_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithPostCategoryCheckbox
 */
class FieldWithPostCategoryCheckbox extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostCategoryCheckbox';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_category_checkbox_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasAllCategories' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if all categories should be displayed on the Post Category drop down. If this property is true (display all categories), the Post Category drop down will display the categories hierarchically.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayAllCategories ),
			],
		];
	}
}
