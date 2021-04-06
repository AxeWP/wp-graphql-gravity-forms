<?php
/**
 * Enum Type - PageProgressStyleEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - PageProgressStyleEnum
 */
class PageProgressStyleEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PageProgressStyleEnum';

	// Individual elements.
	const BLUE   = 'blue';
	const GREY   = 'grey';
	const GREEN  = 'green';
	const ORANGE = 'orange';
	const RED    = 'red';
	const CUSTOM = 'custom';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Style of progress bar.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'BLUE'   => [
				'description' => __( 'Blue progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BLUE,
			],
			'GREY'   => [
				'description' => __( 'Grey progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GREY,
			],
			'GREEN'  => [
				'description' => __( 'Green progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GREEN,
			],
			'ORANGE' => [
				'description' => __( 'Orange progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ORANGE,
			],
			'RED'    => [
				'description' => __( 'Red progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RED,
			],
			'CUSTOM' => [
				'description' => __( 'Custom progress bar style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CUSTOM,
			],
		];
	}
}
