<?php
/**
 * GraphQL Interface for a FormField with the `address_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;
use WPGraphQL\GF\Type\Enum\AddressFieldProvinceEnum;
use WPGraphQL\GF\Type\Enum\AddressFieldTypeEnum;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithAddress
 */
class FieldWithAddress extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAddressSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'address_setting';

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
			'addressType'     => [
				'type'        => AddressFieldTypeEnum::$type,
				'description' => __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' ),
			],
			'defaultCountry'  => [
				'type'        => AddressFieldCountryEnum::$type,
				'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "INTERATIONAL".', 'wp-graphql-gravity-forms' ),
			],
			'defaultProvince' => [
				'type'        => AddressFieldProvinceEnum::$type,
				'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "CANADA".', 'wp-graphql-gravity-forms' ),
			],
			'defaultState'    => [
				'type'        => AddressFieldProvinceEnum::$type,
				'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "US".', 'wp-graphql-gravity-forms' ),
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
