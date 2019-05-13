<?php
/**
 * Plugin Name: WPGraphQL for Gravity Forms
 * Description: Provides a GraphQL API for interacting with Gravity Forms.
 * Version:     0.1.0
 * Author:      Harness Software, Kellen Mace
 * Author URI:  https://harnessup.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

add_action( 'plugins_loaded', function() {
    $autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

    $dependencies = [
        'Composer autoload files' => is_readable( $autoload ),
        'WPGraphQL plugin'        => class_exists('WPGraphQL'),
        'Gravity Forms plugin'    => class_exists('GFAPI'),
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
} );
