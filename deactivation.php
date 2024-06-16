<?php
/**
 * Deactivation Hook
 *
 * @package WPGraphql\GF
 * @since 0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF;

/**
 * Runs when WPGraphQL is de-activated.
 *
 * This cleans up data that WPGraphQL stores.
 */
function deactivation_callback(): callable {
	return static function (): void {
		// Fire an action when WPGraphQL is de-activating.
		do_action( 'graphql_gf_deactivate' );
	};
}
