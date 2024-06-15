<?php
/**
 * Initializes a singleton instance of WPGraphQL\GF.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

declare( strict_types = 1 );

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
			if ( ! isset( self::$instance ) ) {
				// You cant test a singleton.
				// @codeCoverageIgnoreStart .
				self::$instance = new self();
				self::$instance->setup();
				// @codeCoverageIgnoreEnd
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
		 * Sets up the schema.
		 */
		private function setup(): void {
			// Setup Plugin.
			Extensions::register_hooks();
			CoreSchemaFilters::register_hooks();
			UpdateChecker::register_hooks();
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
