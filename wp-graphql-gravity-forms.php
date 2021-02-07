<?php
/**
 * Plugin Name: WP GraphQL
 * Plugin URI: https://github.com/wp-graphql/wp-graphql
 * GitHub Plugin URI: https://github.com/wp-graphql/wp-graphql
 * Description: GraphQL API for WordPress
 * Author: Harness Software
 * Author URI: https://www.harnessup.com
 * Version: 0.0.1
 * Text Domain: wp-graphql-gravity-forms
 * Domain Path: /languages
 * Requires at least: 5.0
 * WPGraphQL requires at least: 1.0.0+
 * GravityForms requires at least: 2.4.0+
 * Tested up to: 5.6.1
 * Requires PHP: 7.4
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPGraphQLGravityForms
 * @author harnessup
 * @license GPL-3
 */

add_action(
	'plugins_loaded',
	function() {
		$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

		$dependencies = [
			'Composer autoload files' => is_readable( $autoload ),
			'WPGraphQL plugin'        => class_exists( 'WPGraphQL' ),
			'Gravity Forms plugin'    => class_exists( 'GFAPI' ),
		];

		$missing_dependencies = array_keys( array_diff( $dependencies, array_filter( $dependencies ) ) );

		$display_admin_notice = function() use ( $missing_dependencies ) {
			?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'The WPGraphQL for Gravity Forms plugin can\'t be loaded because these dependencies are missing:', 'wp-graphql-gravityforms' ); ?></p>
			<ul>
				<?php foreach ( $missing_dependencies as $missing_dependency ) : ?>
					<li><?php echo esc_html( $missing_dependency ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
			<?php
		};

		// If dependencies are missing, display admin notice and return early.
		if ( $missing_dependencies ) {
			add_action( 'network_admin_notices', $display_admin_notice );
			add_action( 'admin_notices', $display_admin_notice );

			return;
		}

		require_once $autoload;

		( new WPGraphQLGravityForms\WPGraphQLGravityForms() )->run();
	}
);
