<?php
/**
 * Interface - Gravity Forms field choice.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormFieldChoice
 */
class FormFieldChoice extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldChoice';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( ?TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );
		if ( null !== $type_registry ) {
			$config['resolveType'] = static::resolve_type( $type_registry );
		}

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms field choice.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'text'  => [
				'type'        => 'String',
				'description' => __( 'The text to be displayed to the user when displaying this choice.', 'wp-graphql-gravity-forms' ),
			],
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value to be stored in the database when this choice is selected. Note: This property is only supported by the Drop Down and Post Category fields. Checkboxes and Radio fields will store the text property in the database regardless of the value property.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Resolves the interface to the GraphQL type.
	 *
	 * @param TypeRegistry $type_registry The WPGraphQL type registry.
	 */
	public static function resolve_type( TypeRegistry $type_registry ) : callable {
		return function( $value ) use ( $type_registry ) {
			$input_type = $value->get_input_type();

			$name = ( $value->type !== $input_type ? $value->type . '_' . $input_type : $value->type ) . 'FieldChoice';
			$name = Utils::get_safe_form_field_type_name( $name );

			$type = $type_registry->get_type( $name );

			if ( null === $type ) {
				throw new UserError(
					sprintf(
					/* translators: %s: The Choice Field name */
						__( 'The "%s" field does not exist in the schema.', 'wp-graphql-gravity-forms' ),
						$name
					)
				);
			}

			return $type;
		};
	}
}
