<?php
/**
 * GraphQL Interface for a FormField with the `address_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;
use WPGraphQL\GF\Type\Enum\AddressFieldTypeEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithAddress
 */
class FieldWithAddress extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAddress';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'address_setting';

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
	 * Register GraphQL fields to the FormField objects that implement this interface.
	 *
	 * @param GF_Field     $field The Gravity forms field.
	 * @param array        $settings The GF settings for the field.
	 * @param TypeRegistry $registry The WPGraphQL type registry.
	 */
	public static function register_object_fields( GF_Field $field, array $settings, TypeRegistry $registry ) : void {
		// Register the InputProperty for the object.
		FormFieldInputs::register( $field, $settings, $registry );
	}
}
