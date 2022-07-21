<?php
/**
 * GraphQL Interface for a FormField with the `product_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithProductField
 */
class FieldWithProductField extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithProductField';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'product_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		// @todo make connection.
		return [
			'productField' => [
				'type'        => 'Int',
				'description' => __( 'The id of the product field to which the field is associated.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
