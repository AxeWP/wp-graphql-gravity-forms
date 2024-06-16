<?php
/**
 * Enables support for WPGraphQLContentBlocks.
 *
 * @package WPGraphQL\GF\Extensions\WPGraphQLContentBlocks
 * @since 0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Extensions\WPGraphQLContentBlocks;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - WPGraphQLContentBlocks
 */
class WPGraphQLContentBlocks implements Hookable, Registrable {
	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		if ( ! self::is_plugin_enabled() ) {
			return;
		}

		// Register action monitors.
		add_action( 'graphql_register_types', [ self::class, 'register' ] );
	}

	/**
	 * Returns whether WPGraphQLContentBlocks is enabled.
	 */
	public static function is_plugin_enabled(): bool {
		return class_exists( 'WPGraphQLContentBlocks' ) && defined( 'WPGRAPHQL_CONTENT_BLOCKS_VERSION' ) && version_compare( WPGRAPHQL_CONTENT_BLOCKS_VERSION, '4.0.0', '>=' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		register_graphql_field(
			'GravityformsFormAttributes', // Generated by wp-graphql-content-blocks.
			'form',
			[
				'type'        => Form::$type,
				'description' => __( 'The form object associated with the block.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( empty( $source['attrs']['formId'] ) ) {
						return null;
					}

					return Factory::resolve_form( (int) $source['attrs']['formId'], $context );
				},
			]
		);
	}
}
