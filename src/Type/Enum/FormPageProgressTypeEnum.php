<?php
/**
 * Enum Type - FormPageProgressTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormPageProgressTypeEnum
 */
class FormPageProgressTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormPageProgressTypeEnum';

	// Individual elements.
	public const PERCENTAGE = 'percentage';
	public const STEPS      = 'steps';
	public const NONE       = 'none';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of page progress indicator to be displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'PERCENTAGE' => [
				'description' => __( 'Show page progress indicator as a percentage.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PERCENTAGE,
			],
			'STEPS'      => [
				'description' => __( 'Show page progress indicator as steps.', 'wp-graphql-gravity-forms' ),
				'value'       => self::STEPS,
			],
			'NONE'       => [
				'description' => __( "Don't show a page progress indicator.", 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
		];
	}
}
