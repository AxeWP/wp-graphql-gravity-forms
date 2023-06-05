<?php
/**
 * Interface - Gravity Forms field choice.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Registry\FieldChoiceRegistry;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldChoice
 */
class FieldChoice extends AbstractInterface {
	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoice';

	/**
	 * {@inheritDoc}
	 *
	 * @var bool
	 */
	public static bool $should_load_eagerly = true;

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
		return __( 'Gravity Forms field choice.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
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
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry The WPGraphQL type registry.
	 */
	public static function resolve_type( TypeRegistry $type_registry ): callable {
		return static function ( $value ) use ( $type_registry ) {
			$name = '';

			if ( is_array( $value ) && isset( $value['graphql_type'] ) ) {
				$name = $value['graphql_type'];
			} elseif ( $value instanceof \GF_Field ) {
				$name = FieldChoiceRegistry::get_type_name( $value );
			}

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
