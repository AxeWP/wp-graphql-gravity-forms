<?php
/**
 * Maps the Gravity Forms Field inputs.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.10.0
 *
 * @todo maybe refactor to Trait?
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - InputMapper
 */
class InputMapper {
	/**
	 * An array of GraphQL type names registered by the class.
	 *
	 * Used to prevent reregistering duplicate types, since `graphql_register_types()` doesn't run until later.
	 *
	 * @var array
	 */
	public static array $registered_types = [];

	/**
	 * Registers a GraphQL object type for the input, and returns the GF Field `inputs`.
	 *
	 * @param GF_Field $field .
	 * @param array    $input_fields .
	 */
	public static function map_inputs( GF_Field $field, array $input_fields ) : array {
		$name = Utils::get_safe_form_field_type_name( $field->type . 'InputProperty' );

		// Don't register duplicate fields.
		if ( ! in_array( $name, self::$registered_types, true ) ) {
			register_graphql_object_type(
				$name,
				[
					// translators: GF field type.
					'description' => sprintf( __( '%s input values.', 'wp-graphql-gravity-forms' ), ucfirst( $field->type ) ),
					'fields'      => $input_fields,
					'resolve'     => function( GF_Field $source, array $args, AppContext $context ) {
						$context->gfField = $source;

						return ! empty( $source->inputs ) ? $source->inputs : null;
					},
				]
			);

			self::$registered_types[] = $name;
		}

		return [
			'inputs' => [
				'type'        => [ 'list_of' => $name ],
				// translators: GF field type.
				'description' => sprintf( __( 'An array of the available properties for each input of the %s field.', 'wp-graphql-gravity-forms' ), $field->type ),
			],
		];
	}

	/**
	 * Registers additional GraphQL fields to the given `input` field.
	 *
	 * @param GF_Field $field .
	 * @param array    $input_fields .
	 */
	public static function add_fields_to_input( GF_Field $field, array $input_fields ) : void {
		$name = Utils::get_safe_form_field_type_name( $field->type . 'InputProperty' );

		register_graphql_fields( $name, $input_fields );
	}
}
