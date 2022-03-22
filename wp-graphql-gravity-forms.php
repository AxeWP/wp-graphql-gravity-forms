<?php
/**
 * Plugin Name: WPGraphQL for Gravity Forms
 * Plugin URI: https://github.com/harness-software/wp-graphql-gravity-forms
 * GitHub Plugin URI: https://github.com/harness-software/wp-graphql-gravity-forms
 * Description: Adds Gravity Forms functionality to the WPGraphQL schema.
 * Author: Harness Software
 * Author URI: https://www.harnessup.com
 * Update URI: https://github.com/harness-software/wp-graphql-gravity-forms/releases
 * Version: 0.11.0
 * Text Domain: wp-graphql-gravity-forms
 * Domain Path: /languages
 * Requires at least: 5.4.1
 * Tested up to: 5.9.0
 * Requires PHP: 7.4+
 * WPGraphQL requires at least: 1.7.0
 * GravityForms requires at least: 2.5.0
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPGraphQL\GF
 * @author harnessup
 * @license GPL-3
 */

/**
 * Define plugin constants.
 */
function gf_graphql_constants() : void {
	// Plugin version.
	if ( ! defined( 'WPGRAPHQL_GF_VERSION' ) ) {
		define( 'WPGRAPHQL_GF_VERSION', '0.11.0' );
	}

	// Plugin Folder Path.
	if ( ! defined( 'WPGRAPHQL_GF_PLUGIN_DIR' ) ) {
		define( 'WPGRAPHQL_GF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Plugin Folder URL.
	if ( ! defined( 'WPGRAPHQL_GF_PLUGIN_URL' ) ) {
		define( 'WPGRAPHQL_GF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	// Plugin Root File.
	if ( ! defined( 'WPGRAPHQL_GF_PLUGIN_FILE' ) ) {
		define( 'WPGRAPHQL_GF_PLUGIN_FILE', __FILE__ );
	}

	// Whether to autoload the files or not.
	if ( ! defined( 'WPGRAPHQL_GF_AUTOLOAD' ) ) {
		define( 'WPGRAPHQL_GF_AUTOLOAD', true );
	}

	// Whether to enable untested form fields the files or not.
	if ( ! defined( 'WPGRAPHQL_GF_EXPERIMENTAL_FIELDS' ) ) {
		define( 'WPGRAPHQL_GF_EXPERIMENTAL_FIELDS', false );
	}
}

/**
 * Checks if all the the required plugins are installed and activated.
 */
function gf_graphql_dependencies_not_ready() : array {
	$deps = [];

	if ( ! class_exists( '\WPGraphQL' ) ) {
		$deps[] = 'WPGraphQL';
	}

	if ( ! class_exists( 'GFAPI' ) ) {
		$deps[] = 'Gravity Forms';
	}

	return $deps;
}

/**
 * Initializes WPGraphQL for GF.
 *
 * @return \WPGraphQL\GF\GF|false
 */
function gf_graphql_init() {
	gf_graphql_constants();

	$not_ready = gf_graphql_dependencies_not_ready();

	if ( empty( $not_ready ) && defined( 'WPGRAPHQL_GF_PLUGIN_DIR' ) ) {
		require_once WPGRAPHQL_GF_PLUGIN_DIR . 'src/GF.php';
		return \WPGraphQL\GF\GF::instance();
	}

	foreach ( $not_ready as $dep ) {
		add_action(
			'admin_notices',
			function() use ( $dep ) {
				?>
				<div class="error notice">
					<p>
						<?php
							printf(
								/* translators: dependency not ready error message */
								esc_html__( '%1$s must be active for WPGraphQL for Gravity Forms to work.', 'wp-graphql-gravity-forms' ),
								esc_html( $dep )
							);
						?>
					</p>
				</div>
				<?php
			}
		);
	}

	return false;
}

add_action( 'graphql_init', 'gf_graphql_init' );
