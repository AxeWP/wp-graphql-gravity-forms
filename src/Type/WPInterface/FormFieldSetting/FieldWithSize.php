<?php
/**
 * GraphQL Interface for a FormField with the `size_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\FormFieldSizeEnum;

/**
 * Class - FieldWithSize
 */
class FieldWithSize extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSize';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'size_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'size' => [
				'type'        => FormFieldSizeEnum::$type,
				'description' => __( 'Determines the size of the field when displayed on the page.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
