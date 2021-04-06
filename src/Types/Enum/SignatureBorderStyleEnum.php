<?php
/**
 * Enum Type - SignatureBorderStyleEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - SignatureBorderStyleEnum
 */
class SignatureBorderStyleEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SignatureBorderStyleEnum';

	// Individual elements.
	const DOTTED = 'dotted';
	const DASHED = 'dashed';
	const GROOVE = 'groove';
	const RIDGE  = 'ridge';
	const INSET  = 'inset';
	const OUTSET = 'outset';
	const DOUBLE = 'double';
	const SOLID  = 'solid';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'DOTTED' => [
				'description' => __( 'A "dotted" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOTTED,
			],
			'DASHED' => [
				'description' => __( 'A "dashed" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DASHED,
			],
			'RIDGE'  => [
				'description' => __( 'A "ridge" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIDGE,
			],
			'INSET'  => [
				'description' => __( 'An "inset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INSET,
			],
			'OUTSET' => [
				'description' => __( 'An "outset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::OUTSET,
			],
			'DOUBLE' => [
				'description' => __( 'A "double" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOUBLE,
			],
			'SOLID'  => [
				'description' => __( 'A "solid" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SOLID,
			],
		];
	}
}
