<?php
/**
 * Registers a Gravity Forms form field input to the WPGraphQL schema.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Registry
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Registry;

use GF_Field;
use WPGraphQL\GF\Registry\TypeRegistry as GFTypeRegistry;
use WPGraphQL\GF\Type\WPInterface\FieldInput;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FieldInputRegistry
 */
class FieldInputRegistry {
	/**
	 * The GraphQL type names registered in this registry.
	 *
	 * Used to prevent duplicate type registration.
	 *
	 * @since 0.12.2
	 *
	 * @var string[]
	 */
	public static $registered_types = [];

	/**
	 * Gets the GraphQL type name for the generated GF Field input.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 */
	public static function get_type_name( GF_Field $field ): string {
		$input_type = $field->get_input_type();

		$input_name = ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type ) . 'InputProperty';

		// Search-replace complex field types who's names are overcomplicated.
		$names_to_map = [
			'product_singleproduct' => 'ProductSingle',
		];
		$input_name   = str_replace( array_keys( $names_to_map ), array_values( $names_to_map ), $input_name );

		return Utils::get_safe_form_field_type_name( $input_name );
	}

	/**
	 * Registers the Input property for the GF Form Field as a GraphQL object.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param string[]  $settings The Gravity Forms field settings used to define the GraphQL object.
	 * @param bool      $as_interface Whether to register the choice as an interface. Default false.
	 */
	public static function register( GF_Field $field, array $settings, bool $as_interface = false ): void {
		$input_name = self::get_type_name( $field );

		// Skip if already registered.
		if ( in_array( $input_name, self::$registered_types, true ) ) {
			return;
		}

		$config = self::get_config_from_settings( $input_name, $field, $settings );

		if ( $as_interface ) {
			$config['resolveType'] = static function () use ( $input_name ) {
				return $input_name;
			};

			$config['eagerlyLoadType'] = true;
			register_graphql_interface_type( $input_name, $config );
		} else {
			$parent_input_name = Utils::get_safe_form_field_type_name( $field->type . 'InputProperty' );

			// Check if we need to register a parent interface.
			if ( $parent_input_name !== $input_name && in_array( $parent_input_name, self::$registered_types, true ) ) {
				$config['interfaces'] = array_merge( $config['interfaces'], [ $parent_input_name ] );
			}

			register_graphql_object_type( $input_name, $config );
		}

		register_graphql_field(
			$field->graphql_single_name,
			'inputs',
			[
				'type'        => [ 'list_of' => $input_name ],
				'description' => static fn () => sprintf(
					// translators: GF field type.
					__( 'The input properties for the %s field.', 'wp-graphql-gravity-forms' ),
					$field->type
				),
			]
		);

		// Store in static array to prevent duplicate registration.
		self::$registered_types[] = $input_name;
	}

	/**
	 * Returns the config array used to register the form field.
	 *
	 * @param string    $input_name The name of the input.
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param string[]  $settings The Gravity Forms field settings.
	 *
	 * @return array{description:callable():string,interfaces:string[],fields:array<string,array<string,mixed>>,eagerlyLoadType:bool}
	 */
	public static function get_config_from_settings( string $input_name, GF_Field $field, array $settings ): array {
		$interfaces = self::get_interfaces( $settings );

		$fields = self::get_fields( $input_name, $field, $settings, $interfaces );

		return [
			'description'     => static fn () => sprintf(
				// translators: GF field input type.
				__( '%s input values.', 'wp-graphql-gravity-forms' ),
				ucfirst( $input_name )
			),
			'interfaces'      => $interfaces,
			'fields'          => $fields,
			'eagerlyLoadType' => true,
		];
	}

	/**
	 * Returns the config array used to register the form field input.
	 *
	 * @param string[] $settings .
	 *
	 * @return string[]
	 */
	public static function get_interfaces( array $settings ): array {
		// Every Input is a FieldInput.
		$interfaces = [
			FieldInput::$type,
		];

		$classes = GfTypeRegistry::form_field_setting_inputs();

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
	 * @param string    $input_name .
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param string[]  $settings The Gravity Forms field settings.
	 * @param string[]  $interfaces The list of interfaces to add to the field.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function get_fields( string $input_name, GF_Field $field, array $settings, array $interfaces ): array {
		$fields = FieldInput::get_fields();

		/**
		 * Filter to modify the Form Field Input GraphQL fields.
		 *
		 * @param array<string,array<string,mixed>> $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param string                            $input_name The name of the input type.
		 * @param \GF_Field                         $field The Gravity Forms Field object.
		 * @param string[]                          $settings The `form_editor_field_settings()` keys.
		 * @param string[]                          $interfaces The list of interfaces for the GraphQL type.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_setting_input_fields', $fields, $input_name, $field, $settings, $interfaces );

		return apply_filters( 'graphql_gf_form_field_setting_input_fields_' . $input_name, $fields, $field, $settings, $interfaces );
	}
}
