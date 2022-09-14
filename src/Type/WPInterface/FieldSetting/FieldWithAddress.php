<?php
/**
 * GraphQL Interface for a FormField with the `address_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FieldInputRegistry;
use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;
use WPGraphQL\GF\Type\Enum\AddressFieldTypeEnum;

/**
 * Class - FieldWithAddress
 */
class FieldWithAddress extends AbstractFieldSetting {
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
	public static function register_hooks(): void {
		add_action( 'graphql_gf_register_form_field_inputs', [ __CLASS__, 'add_inputs' ], 11, 2 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
				'type'        => 'String',
				'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "CANADA".', 'wp-graphql-gravity-forms' ),
			],
			'defaultState'    => [
				'type'        => 'String',
				'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "US".', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 */
	public static function add_inputs( GF_Field $field, array $settings ) : void {
		if (
			! in_array( self::$field_setting, $settings, true )
		) {
			return;
		}

		// Register the FieldChoice for the object.
		FieldInputRegistry::register( $field, $settings );
	}
}
