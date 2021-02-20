<?php
/**
 * GraphQL Object Type - NumberField
 *
 * @see https://docs.gravityforms.com/gf_field_number/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use GF_Field_Number;
use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - NumberField
 */
class NumberField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'NumberField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'number';

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
				'description' => __( 'Gravity Forms Number field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DefaultValueProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\PlaceholderProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'calculationFormula'  => [
							'type'        => 'String',
							'description' => __( 'The formula used for the number field.', 'wp-graphql-gravity-forms' ),
						],
						'calculationRounding' => [
							'type'        => 'String',
							'description' => __( 'Specifies to how many decimal places the number should be rounded. This is available when enableCalculation is true, but is not available when the chosen format is “Currency”.', 'wp-graphql-gravity-forms' ),
						],
						'enableCalculation'   => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates whether the number field is a calculation.', 'wp-graphql-gravity-forms' ),
						],
						'numberFormat'        => [
							'type'        => 'String',
							'description' => __( 'Specifies the format allowed for the number field. Possible values are "currency", "decimal_dot" (9,999.99), "decimal_comma" (9.999,99).', 'wp-graphql-gravity-forms' ),
						],
						'rangeMin'            => [
							'type'        => 'Float',
							'description' => __( 'Minimum allowed value for a number field. Values lower than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
							'resolve'     => function( GF_Field_Number $root ) {
								if ( '' === $root['rangeMin'] ) {
									return null;
								}

								return (float) $root['rangeMin'];
							},
						],
						'rangeMax'            => [
							'type'        => 'Float',
							'description' => __( 'Maximum allowed value for a number field. Values higher than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
							'resolve'     => function( GF_Field_Number $root ) {
								if ( '' === $root['rangeMax'] ) {
									return null;
								}

								return (float) $root['rangeMax'];
							},
						],
					]
				),
			]
		);
	}
}
