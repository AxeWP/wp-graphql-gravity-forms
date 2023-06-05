<?php
/**
 * Initializes a singleton instance of WPGraphQL\GF.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

namespace WPGraphQL\GF;

use WPGraphQL\GF\Extensions\Extensions;
use WPGraphQL\GF\Registry\TypeRegistry;

if ( ! class_exists( 'WPGraphQL\GF\GF' ) ) :
	/**
	 * Class - GF
	 */
	final class GF {
		/**
		 * Class instances.
		 *
		 * @var ?\WPGraphQL\GF\GF $instance
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		public static function instance(): self {
			if ( ! isset( self::$instance ) || ! ( is_a( self::$instance, self::class ) ) ) {
				if ( ! function_exists( 'is_plugin_active' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				self::$instance = new self();
				self::$instance->includes();
				self::$instance->setup();
			}

			/**
			 * Fire off init action.
			 *
			 * @param \WPGraphQL\GF\GF $instance the instance of the plugin class.
			 */
			do_action( 'graphql_gf_init', self::$instance );

			return self::$instance;
		}

		/**
		 * Includes the required files with Composer's autoload.
		 *
		 * @since 0.10.0
		 */
		private function includes(): void {
			if ( defined( 'WPGRAPHQL_GF_AUTOLOAD' ) && false !== WPGRAPHQL_GF_AUTOLOAD && defined( 'WPGRAPHQL_GF_PLUGIN_DIR' ) ) {
				require_once WPGRAPHQL_GF_PLUGIN_DIR . 'vendor/autoload.php';
			}
		}

		/**
		 * Sets up the schema.
		 */
		private function setup(): void {
			Extensions::register_hooks();
			CoreSchemaFilters::register_hooks();
			UpdateChecker::register_hooks();

			// Initialize GF type registry.
			TypeRegistry::init();
		}

		/**
		 * Throw error on object clone.
		 * The whole idea of the singleton design pattern is that there is a single object
		 * therefore, we don't want the object to be cloned.
		 *
		 * @since  0.10.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'The GF class should not be cloned.', 'wp-graphql-gravity-forms' ), '0.10.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since  0.10.0
		 */
		public function __wakeup(): void {
			// De-serializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'De-serializing instances of the GF class is not allowed.', 'wp-graphql-gravity-forms' ), '0.10.0' );
		}
	}
endif;
