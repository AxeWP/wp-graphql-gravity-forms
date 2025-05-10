<?php
/**
 * Plugin Name: WPGraphQL for Gravity Forms
 * Plugin URI: https://github.com/axewp/wp-graphql-gravity-forms
 * GitHub Plugin URI: https://github.com/axewp/wp-graphql-gravity-forms
 * Description: Adds Gravity Forms functionality to the WPGraphQL schema.
 * Author: AxePress Development
 * Author URI: https://axepress.dev
 * Update URI: https://github.com/axewp/wp-graphql-gravity-forms/releases
 * Version: 0.13.0.1
 * Text Domain: wp-graphql-gravity-forms
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * Requires Plugins: wp-graphql
 * Gravity Forms requires at least: 2.7.0
 * WPGraphQL requires at least: 1.26.0
 * WPGraphQL tested up to: 2.3.0
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPGraphQL\GF
 * @author axepress
 * @license GPL-3
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If the codeception remote coverage file exists, require it.
// This file should only exist locally or when CI bootstraps the environment for testing.
if ( file_exists( __DIR__ . '/c3.php' ) ) {
	require_once __DIR__ . '/c3.php';
}

// Load the autoloader.
require_once __DIR__ . '/src/Autoloader.php';
if ( ! \WPGraphQL\GF\Autoloader::autoload() ) {
	return;
}

/**
 * Define plugin constants.
 */
function constants(): void {
	// Plugin version.
	if ( ! defined( 'WPGRAPHQL_GF_VERSION' ) ) {
		define( 'WPGRAPHQL_GF_VERSION', '0.13.1' );
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

// Run this function when the plugin is activated.
if ( file_exists( __DIR__ . '/activation.php' ) ) {
	require_once __DIR__ . '/activation.php';
	register_activation_hook(
		__FILE__,
		'WPGraphQL\GF\activation_callback'
	);
}

// Run this function when the plugin is deactivated.
if ( file_exists( __DIR__ . '/deactivation.php' ) ) {
	require_once __DIR__ . '/deactivation.php';
	register_activation_hook( __FILE__, 'WPGraphQL\GF\deactivation_callback' );
}

/**
 * Checks if all the the required plugins are installed and activated.
 *
 * @return array<string,string> List of dependencies not ready.
 */
function dependencies_not_ready(): array {
	$wpgraphql_version = '1.26.0';
	$gf_version        = '2.7.0';

	$deps = [];

	if ( ! class_exists( 'WPGraphQL' ) || ( defined( 'WPGRAPHQL_VERSION' ) && version_compare( WPGRAPHQL_VERSION, $wpgraphql_version, '<' ) ) ) {
		$deps['WPGraphQL'] = $wpgraphql_version;
	}

	if ( ! class_exists( 'GFCommon' ) || ( ! empty( \GFCommon::$version ) && version_compare( \GFCommon::$version, $gf_version, '<' ) ) ) {
		$deps['Gravity Forms'] = $gf_version;
	}

	return $deps;
}

/**
 * Initializes WPGraphQL for GF.
 */
function init(): void {
	constants();

	$not_ready = dependencies_not_ready();

	if ( empty( $not_ready ) && defined( 'WPGRAPHQL_GF_PLUGIN_DIR' ) ) {
		require_once WPGRAPHQL_GF_PLUGIN_DIR . 'src/GF.php';
		\WPGraphQL\GF\GF::instance();
	}

	// Output an error notice for the dependencies that are not ready.
	foreach ( $not_ready as $dep => $version ) {
		add_action(
			'admin_notices',
			static function () use ( $dep, $version ) {
				?>
				<div class="error notice">
					<p>
						<?php
							printf(
								/* translators: dependency not ready error message */
								esc_html__( '%1$s (v%2$s) must be active for WPGraphQL for Gravity Forms to work.', 'wp-graphql-gravity-forms' ),
								esc_attr( $dep ),
								esc_attr( $version ),
							);
						?>
					</p>
				</div>
				<?php
			}
		);
	}
}

// Initialize the plugin.
add_action( 'graphql_init', 'WPGraphQL\GF\init' );
