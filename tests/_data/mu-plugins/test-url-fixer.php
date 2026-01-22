<?php
// This is global bootstrap for autoloading
function wpgraphql_wpenv_fix_url( $url ) {
	// Only apply URL fix for Codeception tests (WPBrowser).
	// These tests run inside Docker and send specific headers.
	// Playwright e2e tests run outside Docker and need localhost URLs.
	$is_codeception_request = (
		! empty( $_SERVER['HTTP_X_TEST_REQUEST'] ) ||
		! empty( $_SERVER['HTTP_X_WPBROWSER_REQUEST'] )
	);

	if ( ! $is_codeception_request ) {
		return $url;
	}

	return preg_replace( '#https?://(localhost|tests-wordpress):8889#', 'http://tests-wordpress', $url );
}

add_filter( 'site_url', 'wpgraphql_wpenv_fix_url', 1 );

add_filter( 'home_url', 'wpgraphql_wpenv_fix_url', 1 );

add_filter( 'wp_login_url', 'wpgraphql_wpenv_fix_url', 1 );

add_filter( 'admin_url', 'wpgraphql_wpenv_fix_url', 1 );
