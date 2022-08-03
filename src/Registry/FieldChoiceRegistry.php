<?php
/**
 * Registers a Gravity Forms form field choice to the WPGraphQL schema.
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
use WPGraphQL\GF\Type\WPInterface\FieldChoice;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FieldChoiceRegistry
 */
class FieldChoiceRegistry {
	/**
	 * Gets the GraphQL type name for the generated GF Field input.
	 *
	 * @param GF_Field $field The Gravity Forms field object.
	 */
	public static function get_type_name( GF_Field $field ) : string {
		$input_type = $field->get_input_type();

		$input_name = ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type ) . 'FieldChoice';

		return Utils::get_safe_form_field_type_name( $input_name );
	}

	/**
	 * Registers the Choice property for the GF Form Field as a GraphQL object.
	 *
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings used to define the GraphQL object.
	 */
	public static function register( GF_Field $field, $settings ) : void {
		$choice_name = self::get_type_name( $field );

		$config = self::get_config_from_settings( $choice_name, $field, $settings );

		register_graphql_object_type( $choice_name, $config );

		// Register the choices field.
		register_graphql_field(
			$field->graphql_single_name,
			'choices',
			[
				'type'        => [ 'list_of' => $choice_name ],
				// translators: GF field type.
				'description' => sprintf( __( 'The available choices for the %s field.', 'wp-graphql-gravity-forms' ), $field->type ),
			]
		);
	}

	/**
	 * Returns the config array used to register the form field.
	 *
	 * @param string   $choice_name The name of the choice.
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings.
	 */
	public static function get_config_from_settings( string $choice_name, GF_Field $field, array $settings ) : array {
		$interfaces = self::get_interfaces( $settings );

		$fields = self::get_fields( $choice_name, $field, $settings, $interfaces );

		return [
			'description' => sprintf(
				// translators: GF field choice type.
				__( '%s choice values.', 'wp-graphql-gravity-forms' ),
				ucfirst( $choice_name )
			),
			'interfaces'  => $interfaces,
			'fields'      => $fields,
			'resolve'     => function( GF_Field $source, array $args, AppContext $context ) {
				$context->gfField = $source;

				return ! empty( $source->choices ) ? $source->choices : null;
			},
		];
	}

	/**
	 * Returns the config array used to register the form field choice.
	 *
	 * @param array $settings .
	 */
	public static function get_interfaces( array $settings ) : array {
		// Every Choice is a FieldChoice.
		$interfaces = [
			FieldChoice::$type,
		];

		$classes = TypeRegistry::form_field_setting_choices();

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
	 * @param string   $choice_name .
	 * @param GF_Field $field The Gravity Forms field object.
	 * @param array    $settings The Gravity Forms field settings.
	 * @param array    $interfaces The list of interfaces to add to the field.
	 */
	public static function get_fields( string $choice_name, GF_Field $field, array $settings, array $interfaces ) : array {
		$fields = FieldChoice::get_fields();

		/**
		 * Filter to modify the Form Field Choice GraphQL fields.
		 *
		 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param string   $choice_name The name of the choice type.
		 * @param GF_Field $field The Gravity Forms Field object.
		 * @param array    $settings The `form_editor_field_settings()` keys.
		 * @param array    $interfaces The list of interfaces for the GraphQL type.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_setting_choice_fields', $fields, $choice_name, $field, $settings, $interfaces );
		$fields = apply_filters( 'graphql_gf_form_field_setting_choice_fields_' . $choice_name, $fields, $field, $settings, $interfaces );

		return $fields;
	}

}
