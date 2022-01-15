<?php
/**
 * Maps the Gravity Forms Field choices.
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
 * Class - ChoiceMapper
 */
class ChoiceMapper {
	/**
	 * An array of GraphQL type names registered by the class.
	 *
	 * Used to prevent reregistering duplicate types.
	 *
	 * @var array
	 */
	public static array $registered_types = [];

	/**
	 * Registers a GraphQL object type for the choice, and returns the GF Field `choices`.
	 *
	 * @todo use inputType names for inherited fields. Needs a way to handle unknown input types at registration.
	 *
	 * @param GF_Field $field .
	 * @param array    $choice_fields .
	 */
	public static function map_choices( GF_Field $field, array $choice_fields ) : array {
		$input_type = ! empty( $field->inputType ) ? $field->inputType : $field->type;

		$name = Utils::get_safe_form_field_type_name( ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type ) . 'FieldChoice' );

		// Don't register duplicate fields.
		if ( ! in_array( $name, self::$registered_types, true ) ) {
			register_graphql_object_type(
				$name,
				[
					// translators: GF field type.
					'description' => sprintf( __( '%s choice values.', 'wp-graphql-gravity-forms' ), ucfirst( $field->type ) ),
					'fields'      => $choice_fields,
					'resolve'     => function( GF_Field $source, array $args, AppContext $context ) {
						$context->gfField = $source;

						return ! empty( $source->choices ) ? $source->choices : null;
					},
				]
			);

			self::$registered_types[] = $name;
		}

		return [
			'choices' => [
				'type'        => [ 'list_of' => $name ],
				// translators: GF field type.
				'description' => sprintf( __( 'The available choices for the %s field.', 'wp-graphql-gravity-forms' ), $field->type ),
			],
		];
	}

	/**
	 * Registers additional GraphQL fields to the given `choice` field.
	 *
	 * @param GF_Field $field .
	 * @param array    $choice_fields .
	 */
	public static function add_fields_to_choice( GF_Field $field, array $choice_fields ) : void {
		$name = Utils::get_safe_form_field_type_name( ( $field->type !== $field->inputType ? $field->type . '_' . $field->inputType : $field->type ) . 'FieldChoice' );

		register_graphql_fields( $name, $choice_fields );
	}
}
