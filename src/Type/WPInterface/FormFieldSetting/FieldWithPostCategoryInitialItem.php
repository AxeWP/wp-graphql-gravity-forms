<?php
/**
 * GraphQL Interface for a FormField with the `post_category_initial_item_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithPostCategoryInitialItem
 */
class FieldWithPostCategoryInitialItem extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostCategoryInitialItem';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_category_initial_item_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'dropdownPlaceholder' => [
				'type'        => 'String',
				'description' => __( 'The dropdown placeholder for the field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->categoryInitialItem ) ? $source->categoryInitialItem : null,
			],
		];
	}
}
