<?php
/**
 * GraphQL Interface for a Single Product Field inputs.
 *
 * This isnt a real GF setting, as the inputs are added directly to GF_Field_SingleProduct.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithSingleProductInputs
 */
class FieldWithSingleProductInputs extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSingleProductInputs';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'single_product_inputs';

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
	 *
	 * The only added field is `inputs`, which is handled by the Input registry.
	 */
	public static function get_fields(): array {
		return FieldWithInputs::get_fields();
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
