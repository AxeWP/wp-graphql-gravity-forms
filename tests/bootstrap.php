<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Wp_Graphql_Gravity_Forms
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // WPCS: XSS ok.
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load WPGraphQL for Gravity Forms and its dependencies.
 */
function _manually_load_plugins() {
	require dirname( dirname( __DIR__ ) ) . '/gravityforms/gravityforms.php';
	require dirname( dirname( __DIR__ ) ) . '/wp-graphql/wp-graphql.php';
	require dirname( __DIR__ ) . '/wp-graphql-gravity-forms.php';
	require dirname( __DIR__ ) . '/vendor/autoload.php'; // Composer autoload files.
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugins' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
