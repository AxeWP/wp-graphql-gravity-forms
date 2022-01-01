<?php
/**
 * Enum Type - FormDescriptionPlacementEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormDescriptionPlacementEnum
 */
class FormDescriptionPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormDescriptionPlacementEnum';

	// Individual elements.
	const ABOVE = 'above';
	const BELOW = 'below';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Determines where the field description is displayed relative to the field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		return [
			'ABOVE' => [
				'description' => __( 'The field description is displayed above the field input (i.e. immediately after the field label).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ABOVE,
			],
			'BELOW' => [
				'description' => __( 'The field description is displayed below the field input.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BELOW,
			],
		];
	}
}
