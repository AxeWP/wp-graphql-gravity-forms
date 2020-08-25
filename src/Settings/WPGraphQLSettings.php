<?php

namespace WPGraphQLGravityForms\Settings;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * WPGraphQL Settings.
 */
class WPGraphQLSettings implements Hookable {
    public function register_hooks() {
		add_action( 'graphql_connection_max_query_amount', [ $this, 'set_max_query_amount' ], 11 );
	}

	/**
	 * Bump max query amount to account for forms with many fields.
	 *
	 * @param  int $max_query_amount Max query amount.
	 *
	 * @return int Max query amount, possibly bumped.
	 */
	public function set_max_query_amount( $max_query_amount ) : int {
		// Original max amount or 600 - whichever is higher.
		return (int) max( $max_query_amount, 600 );
	}
}
