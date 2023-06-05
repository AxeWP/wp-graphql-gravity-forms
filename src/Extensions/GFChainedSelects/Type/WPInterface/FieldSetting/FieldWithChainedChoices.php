<?php
/**
 * GraphQL Interface for a FormField with the `chained_choices_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithChoices as FieldWithChoicesSetting;
use WPGraphQL\GF\Type\WPInterface\FieldWithChoices;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithChainedChoices
 */
class FieldWithChainedChoices extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_choices_setting';

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
	public static function register_hooks(): void {
		add_filter( 'graphql_gf_form_field_settings_with_choices', [ self::class, 'add_setting' ], 10 );
		add_filter( 'graphql_gf_form_field_settings_with_inputs', [ self::class, 'add_setting' ], 10 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		// This setting is identical to `choices_setting` but for some reason exists independently.
		return FieldWithChoicesSetting::get_fields();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [
			FieldWithChoices::$type,
			FieldWithInputs::$type,
		];
	}

	/**
	 * Adds the `chained_choices_setting` setting to the list of settings that have the GraphQL choices field.
	 *
	 * @param array $settings the GF Field settings.
	 */
	public static function add_setting( array $settings ): array {
		if ( ! in_array( self::$field_setting, $settings, true ) ) {
			$settings[] = self::$field_setting;
		}

		return $settings;
	}
}
