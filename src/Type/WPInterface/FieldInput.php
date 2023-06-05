<?php
/**
 * Interface - Gravity Forms field input.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Registry\FieldInputRegistry;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldInput
 */
class FieldInput extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInput';

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
	public static function get_description(): string {
		return __( 'Gravity Forms field input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'label' => [
				'type'        => 'String',
				'description' => __( 'The label to be used for the input.', 'wp-graphql-gravity-forms' ),
			],
			'id'    => [
				'type'        => 'Float',
				'description' => __( 'The input ID. Input IDs follow the following naming convention: FIELDID.INPUTID (i.e. 5.1), where FIELDID is the id of the containing field and INPUTID specifies the input field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Resolves the interface to the GraphQL type.
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry The WPGraphQL type registry.
	 */
	public static function resolve_type( TypeRegistry $type_registry ): callable {
		return static function ( $value ) use ( $type_registry ) {
			$name = '';

			if ( is_array( $value ) && isset( $value['graphql_type'] ) ) {
				$name = $value['graphql_type'];
			} elseif ( $value instanceof \GF_Field ) {
				$name = FieldInputRegistry::get_type_name( $value );
			}

			$type = $type_registry->get_type( $name );

			if ( null === $type ) {
				throw new UserError(
					sprintf(
					/* translators: %s: The Choice Field name */
						__( 'The "%s" type does not exist in the schema.', 'wp-graphql-gravity-forms' ),
						$name
					)
				);
			}

			return $type;
		};
	}
}
