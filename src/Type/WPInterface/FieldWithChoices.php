<?php
/**
 * Interface - Form Field with `choices`.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface;

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
			],
		];
	}
}
