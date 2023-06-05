<?php
/**
 * Abstract Class for FormField Setting Interfaces
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\AbstractInterface;

/**
 * Class - AbstractFieldSetting
 */
abstract class AbstractFieldSetting extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting;

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return sprintf(
			// translators: The Gravity Forms field setting.
			__( 'A form field with the `%s` setting.', 'wp-graphql-gravity-forms' ),
			static::$field_setting
		);
	}
}
