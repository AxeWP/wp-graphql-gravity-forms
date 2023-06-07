<?php
/**
 * Enum Type - FormPageProgressStyleEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormPageProgressStyleEnum
 */
class FormPageProgressStyleEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormPageProgressStyleEnum';

	// Individual elements.
	public const BLUE   = 'blue';
	public const GREY   = 'grey';
	public const GREEN  = 'green';
	public const ORANGE = 'orange';
	public const RED    = 'red';
	public const CUSTOM = 'custom';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Style of progress bar.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
