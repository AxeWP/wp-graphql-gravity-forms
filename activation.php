<?php
/**
 * Activation Hook
 *
 * @package WPGraphql\GF
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF;

/**
 * Runs when the plugin is activated.
 */
function activation_callback(): callable {
	return static function (): void {
		do_action( 'graphql_gf_activate' );

		// Store the current version of the plugin.
		update_option( 'wp_graphql_gf_version', WPGRAPHQL_GF_VERSION );
	};
}
