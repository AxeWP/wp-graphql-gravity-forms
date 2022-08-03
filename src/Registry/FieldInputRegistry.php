<?php
/**
 * Registers a Gravity Forms form field input to the WPGraphQL schema.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Registry
 * @since   @todo
 */

namespace WPGraphQL\GF\Registry;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPInterface\FieldInputProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FieldInputRegistry
 */
class FieldInputRegistry {
	/**
	 * Gets the GraphQL type name for the generated GF Field input.
	 *
	 * @param GF_Field $field The Gravity Forms field object.
	 */
	public static function get_type_name( GF_Field $field ) : string {
		$input_type = $field->get_input_type();

		$input_name = ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type ) . 'InputProperty';

		return Utils::get_safe_form_field_type_name( $input_name );
	}

	/**
	 * Registers the Input property for the GF Form Field as a GraphQL object.
	 *
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings used to define the GraphQL object.
	 */
	public static function register( GF_Field $field, $settings ) : void {
		$input_name = self::get_type_name( $field );

		$config = self::get_config_from_settings( $input_name, $field, $settings );

		register_graphql_object_type( $input_name, $config );

		// Register the inputs field.
		register_graphql_field(
			$field->graphql_single_name,
			'inputs',
			[
				'type'        => [ 'list_of' => $input_name ],
				// translators: GF field type.
				'description' => sprintf( __( 'The available inputs for the %s field.', 'wp-graphql-gravity-forms' ), $field->type ),
			]
		);
	}

	/**
	 * Returns the config array used to register the form field.
	 *
	 * @param string   $input_name The name of the input.
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings.
	 */
	public static function get_config_from_settings( string $input_name, GF_Field $field, array $settings ) : array {
		$interfaces = self::get_interfaces( $settings );

		$fields = self::get_fields( $input_name, $field, $settings, $interfaces );

		return [
			'description' => sprintf(
				// translators: GF field input type.
				__( '%s input values.', 'wp-graphql-gravity-forms' ),
				ucfirst( $input_name )
			),
			'interfaces'  => $interfaces,
			'fields'      => $fields,
			'resolve'     => function( GF_Field $source, array $args, AppContext $context ) {
				$context->gfField = $source;

				return ! empty( $source->inputs ) ? $source->inputs : null;
			},
		];
	}

	/**
	 * Returns the config array used to register the form field input.
	 *
	 * @param array $settings .
	 */
	public static function get_interfaces( array $settings ) : array {
		// Every Input is a FieldInput.
		$interfaces = [
			FieldInputProperty::$type,
		];

		$classes = TypeRegistry::form_field_setting_inputs();

		// Loop through the individual settings.
		foreach ( $settings as $setting ) {
			// Skip settings registered elsewhere.
			if ( in_array( $setting, Utils::get_ignored_gf_settings(), true ) ) {
				continue;
			}

			$interface_class = isset( $classes[ $setting ] ) ? $classes[ $setting ] : null;

			// Skip if no interface, or if interface was already added.
			if ( empty( $interface_class ) || in_array( $interface_class::$type, $interfaces, true ) ) {
				continue;
			}

			$interfaces[] = $interface_class::$type;
		}

		return $interfaces;
	}

	/**
	 * Gets the fields to register to the FieldInput object.
	 *
	 * @param string   $input_name .
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings.
	 * @param array    $interfaces The list of interfaces to add to the field.
	 */
	public static function get_fields( string $input_name, GF_Field $field, array $settings, array $interfaces ) : array {
		$fields = FieldInputProperty::get_fields();

		/**
		 * Filter to modify the Form Field Input GraphQL fields.
		 *
		 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param string   $input_name The name of the input type.
		 * @param GF_Field $field The Gravity Forms Field object.
		 * @param array    $settings The `form_editor_field_settings()` key.
		 * @param array    $interfaces The list of interfaces for the GraphQL type.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_setting_input_fields', $fields, $input_name, $field, $settings, $interfaces );
		$fields = apply_filters( 'graphql_gf_form_field_setting_input_fields_' . $input_name, $fields, $field, $settings, $interfaces );

		return $fields;
	}

}
