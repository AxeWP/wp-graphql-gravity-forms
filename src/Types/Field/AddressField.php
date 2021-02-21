<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_address/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - AddressField
 */
class AddressField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'AddressField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'address';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms Address field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\LabelPlacementProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\SubLabelPlacementProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						// @TODO - Convert to an enum. Possible values: international, us, canadian
						'addressType'             => [
							'type'        => 'String',
							'description' => __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' ),
						],
						'copyValuesOptionDefault' => [
							'type'        => 'Boolean',
							'description' => __( 'Determines whether the option to copy values is turned on or off by default.', 'wp-graphql-gravity-forms' ),
						],
						'copyValuesOptionField'   => [
							'type'        => 'Integer',
							'description' => __( 'The field id of the field being used as the copy source.', 'wp-graphql-gravity-forms' ),
						],
						'copyValuesOptionLabel'   => [
							'type'        => 'Stting',
							'description' => __( 'The label that appears next to the copy values option when the form is displayed. The default value is “Same as previous”.', 'wp-graphql-gravity-forms' ),
						],
						'defaultCountry'          => [
							'type'        => 'String',
							'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "international".', 'wp-graphql-gravity-forms' ),
						],
						'defaultProvince'         => [
							'type'        => 'String',
							'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "canadian".', 'wp-graphql-gravity-forms' ),
						],
						'defaultState'            => [
							'type'        => 'String',
							'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "us".', 'wp-graphql-gravity-forms' ),
						],
						'enableCopyValuesOption'  => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates whether the copy values option can be used. This option allows users to skip filling out the field and use the same values as another. For example, if the mailing and billing address are the same.', 'wp-graphql-gravity-forms' ),
						],
						'inputs'                  => [
							'type'        => [ 'list_of' => FieldProperty\AddressInputProperty::TYPE ],
							'description' => __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' ),
						],
					],
					/**
					 * Deprecated field properties.
					 *
					 * @since 0.2.0
					 */

					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type. Please use `inputs { name }` instead.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
