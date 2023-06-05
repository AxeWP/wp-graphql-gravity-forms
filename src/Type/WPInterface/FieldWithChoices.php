<?php
/**
 * Interface - Form Field with `choices`.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Registry\FieldChoiceRegistry;

/**
 * Class - FieldWithChoices
 */
class FieldWithChoices extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChoices';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A Gravity Forms field with possible field choices.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'choices' => [
				'type'        => [ 'list_of' => FieldChoice::$type ],
				'description' => __( 'The choices for the field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context, $info ) {
					/** @var \GF_Field $source */
					$context->gfField = $source;

					return ! empty( $source->choices )
						// Include GraphQL Type in resolver.
						? array_map(
							static function ( $choice ) use ( $source ) {
								$choice['graphql_type'] = FieldChoiceRegistry::get_type_name( $source );

								return $choice;
							},
							$source->choices
						)
						: null;
				},
			],
		];
	}
}
