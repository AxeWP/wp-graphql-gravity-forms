<?php
/**
 * Interface - Form Field with `inputs`.
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface;

/**
 * Class - FieldWithInputs
 */
class FieldWithInputs extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithInputs';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A Gravity Forms field with possible field inputs.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputs' => [
				'type'        => [ 'list_of' => FieldInput::$type ],
				'description' => __( 'The inputs for the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
