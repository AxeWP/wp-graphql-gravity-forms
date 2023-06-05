<?php
/**
 * GraphQL Interface for a FormField with the `time_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum\TimeFieldFormatEnum;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithTimeFormat
 */
class FieldWithTimeFormat extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithTimeFormatSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'time_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( ?TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );

		$config['interfaces'] = static::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'timeFormat' => [
				'type'        => TimeFieldFormatEnum::$type,
				'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [
			FieldWithInputs::$type,
		];
	}
}
