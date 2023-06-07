<?php
/**
 * Enum Type - PostFormatTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\Type\WPEnumType;

/**
 * Class - PostFormatTypeEnum
 */
class PostFormatTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PostFormatTypeEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'List of possible post formats.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		$values = [
			'STANDARD' => [
				'value'       => '0',
				'description' => __( 'A standard post format.', 'wp-graphql-gravity-forms' ),
			],
		];

		// If post formats are enabled for the site, add them as enum values.
		$post_formats = get_theme_support( 'post-formats' );

		if ( isset( $post_formats[0] ) && is_array( $post_formats[0] ) ) {
			foreach ( $post_formats[0] as $type ) {
				$values[ WPEnumType::get_safe_name( $type ) ] = [
					'value'       => $type,
					// translators: Post format.
					'description' => sprintf( __( 'A %s post format.', 'wp-graphql-gravity-forms' ), $type ),
				];
			}
		}

		return $values;
	}
}
