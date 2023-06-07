<?php
/**
 * Registers a Gravity Forms form field choice to the WPGraphQL schema.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Registry
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Registry;

use GF_Field;
use WPGraphQL\GF\Registry\TypeRegistry as GFTypeRegistry;
use WPGraphQL\GF\Type\WPInterface\FieldChoice;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;


/**
 * Class - FieldChoiceRegistry
 */
class FieldChoiceRegistry {
	/**
	 * The GraphQL type names registered in this registry.
	 *
	 * Used to prevent duplicate type registration.
	 *
	 * @since 0.12.2
	 *
	 * @var array
	 */
	public static $registered_types = [];

	/**
	 * Gets the GraphQL type name for the generated GF Field input.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 */
	public static function get_type_name( GF_Field $field ): string {
		$input_type = $field->get_input_type();

		switch ( true ) {
			default:
				$input_name = ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type ) . 'FieldChoice';
		}

		return Utils::get_safe_form_field_type_name( $input_name );
	}

	/**
	 * Registers the Choice property for the GF Form Field as a GraphQL object.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $settings The Gravity Forms field settings used to define the GraphQL object.
	 * @param bool      $as_interface Whether to register the choice as an interface. Default false.
	 */
	public static function register( GF_Field $field, array $settings, bool $as_interface = false ): void {
		add_action(
			get_graphql_register_action(),
			static function ( TypeRegistry $type_registry ) use ( $field, $settings, $as_interface ) {
				$choice_name = self::get_type_name( $field );
				
				// Skip if already registered.
				if ( in_array( $choice_name, self::$registered_types, true ) ) {
					return;
				}

				$config = self::get_config_from_settings( $choice_name, $field, $settings );

				if ( $as_interface ) {
					$config['resolveType'] = static function ( $value ) use ( $choice_name ) {
						return $choice_name;
					};

					$config['eagerlyLoadType'] = true;

					register_graphql_interface_type( $choice_name, $config );
				} else {
					$parent_choice_name = Utils::get_safe_form_field_type_name( $field->type ) . 'FieldChoice';

					// Check if we need to register a parent interface.
					if ( $parent_choice_name !== $choice_name && in_array( $parent_choice_name, self::$registered_types, true ) ) {
						$config['interfaces'] = array_merge( $config['interfaces'], [ $parent_choice_name ] );
					}

					register_graphql_object_type( $choice_name, $config );
				}

				// Store in static array to prevent duplicate registration.
				self::$registered_types[] = $choice_name;
			}
		);
	}

	/**
	 * Returns the config array used to register the form field.
	 *
	 * @param string    $choice_name The name of the choice.
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $settings The Gravity Forms field settings.
	 */
	public static function get_config_from_settings( string $choice_name, GF_Field $field, array $settings ): array {
		$interfaces = self::get_interfaces( $settings );

		$fields = self::get_fields( $choice_name, $field, $settings, $interfaces );

		return [
			'description'     => sprintf(
				// translators: GF field choice type.
				__( '%s choice values.', 'wp-graphql-gravity-forms' ),
				ucfirst( $choice_name )
			),
			'interfaces'      => $interfaces,
			'fields'          => $fields,
			'eagerlyLoadType' => true,
		];
	}

	/**
	 * Returns the config array used to register the form field choice.
	 *
	 * @param array $settings .
	 */
	public static function get_interfaces( array $settings ): array {
		// Every Choice is a FieldChoice.
		$interfaces = [
			FieldChoice::$type,
		];

		$classes = GfTypeRegistry::form_field_setting_choices();

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
	 * Gets the fields to register to the FieldChoice object.
	 *
	 * @param string    $choice_name .
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $settings The Gravity Forms field settings.
	 * @param array     $interfaces The list of interfaces to add to the field.
	 */
	public static function get_fields( string $choice_name, GF_Field $field, array $settings, array $interfaces ): array {
		$fields = FieldChoice::get_fields();

		/**
		 * Filter to modify the Form Field Choice GraphQL fields.
		 *
		 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param string   $choice_name The name of the choice type.
		 * @param \GF_Field $field The Gravity Forms Field object.
		 * @param array    $settings The `form_editor_field_settings()` keys.
		 * @param array    $interfaces The list of interfaces for the GraphQL type.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_setting_choice_fields', $fields, $choice_name, $field, $settings, $interfaces );

		return apply_filters( 'graphql_gf_form_field_setting_choice_fields_' . $choice_name, $fields, $field, $settings, $interfaces );
	}
}
