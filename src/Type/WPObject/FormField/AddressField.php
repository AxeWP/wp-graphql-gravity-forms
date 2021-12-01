<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_address/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\AddressTypeEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;


/**
 * Class - AddressField
 */
class AddressField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'address';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Address field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		$return = array_merge(
			FieldProperty\DescriptionPlacementProperty::get(),
			[
				'addressType'             => [
					'type'        => AddressTypeEnum::$type,
					'description' => __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' ),
				],
				'copyValuesOptionDefault' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines whether the option to copy values is turned on or off by default.', 'wp-graphql-gravity-forms' ),
				],
				'copyValuesOptionField'   => [
					'type'        => 'Int',
					'description' => __( 'The field id of the field being used as the copy source.', 'wp-graphql-gravity-forms' ),
				],
				'copyValuesOptionLabel'   => [
					'type'        => 'String',
					'description' => __( 'The label that appears next to the copy values option when the form is displayed. The default value is “Same as previous”.', 'wp-graphql-gravity-forms' ),
				],
				'defaultCountry'          => [
					'type'        => 'String',
					'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "INTERATIONAL".', 'wp-graphql-gravity-forms' ),
				],
				'defaultProvince'         => [
					'type'        => 'String',
					'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "CANADA".', 'wp-graphql-gravity-forms' ),
				],
				'defaultState'            => [
					'type'        => 'String',
					'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "US".', 'wp-graphql-gravity-forms' ),
				],
				'enableCopyValuesOption'  => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the copy values option can be used. This option allows users to skip filling out the field and use the same values as another. For example, if the mailing and billing address are the same.', 'wp-graphql-gravity-forms' ),
				],
				'inputs'                  => [
					'type'        => [ 'list_of' => FieldProperty\AddressInputProperty::$type ],
					'description' => __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings()
		);

		return $return;
	}
}
