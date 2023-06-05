<?php
/**
 * GraphQL Interface for a FormField with the `date_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum\DateFieldFormatEnum;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithDateFormat
 */
class FieldWithDateFormat extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDateFormatSetting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( ?TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );

		$config['interfaces'] = static::get_interfaces();

		return $config;
	}

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'date_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'dateFormat' => [
				'type'        => DateFieldFormatEnum::$type,
				'description' => __( 'Determines how the date is displayed.', 'wp-graphql-gravity-forms' ),
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
