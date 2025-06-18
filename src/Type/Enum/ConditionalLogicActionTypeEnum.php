<?php
/**
 * Enum Type - ConditionalLogicActionTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - ConditionalLogicActionTypeEnum
 */
class ConditionalLogicActionTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConditionalLogicActionTypeEnum';

	// Individual elements.
	public const SHOW = 'show';
	public const HIDE = 'hide';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The type of action the conditional logic will perform.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'SHOW' => [
				'description' => static fn () => __( 'Image button.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SHOW,
			],
			'HIDE' => [
				'description' => static fn () => __( 'Text button (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDE,
			],
		];
	}
}
