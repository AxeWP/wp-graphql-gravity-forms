<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_address/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.1.0 Use FieldProperty\SubLabelPlacementProperty instead of local property.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

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
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\InputsProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelPlacementProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\SubLabelPlacementProperty::get(),
					[
						// @TODO - Convert to an enum. Possible values: international, us, canadian
						'addressType'     => [
							'type'        => 'String',
							'description' => __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' ),
						],
						'defaultCountry'  => [
							'type'        => 'String',
							'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "international".', 'wp-graphql-gravity-forms' ),
						],
						'defaultProvince' => [
							'type'        => 'String',
							'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "canadian".', 'wp-graphql-gravity-forms' ),
						],
						'defaultState'    => [
							'type'        => 'String',
							'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "us".', 'wp-graphql-gravity-forms' ),
						],
						// @TODO - add placeholders.
					],
				),
			]
		);
	}
}
