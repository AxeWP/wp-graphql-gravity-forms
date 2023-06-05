<?php
/**
 * Abstract Class for FormField InputSetting Interfaces
 *
 * @package WPGraphQL\GF\Type\Interface\FieldInputSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\AbstractInterface;

/**
 * Class - AbstractFieldInputSetting
 */
abstract class AbstractFieldInputSetting extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * The name of GF Field InputSetting
	 *
	 * @var string
	 */
	public static string $field_setting;

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return sprintf(
			// translators: The Gravity Forms field Setting.
			__( 'An Input for a form field with the `%s` setting.', 'wp-graphql-gravity-forms' ),
			static::$field_setting
		);
	}
}
