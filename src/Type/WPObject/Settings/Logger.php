<?php
/**
 * GraphQL Object Type - Logging Settings
 *
 * @package WPGraphQL\GF\Type
 * @since   0.10.2
 */

namespace WPGraphQL\GF\Type\WPObject\Settings;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - Logger
 */
class Logger extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfLogger';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms Logging Settings.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the logger is enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['enable'] ),
			],
			'name'      => [
				'type'        => 'String',
				'description' => __( 'The name of the Gravity Forms logger.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
