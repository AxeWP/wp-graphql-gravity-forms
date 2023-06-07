<?php
/**
 * Enables and initializes the Gravity Forms Action Monitor
 *
 * @package WPGraphQL\GF\Extensions\WPGatsby
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\WPGatsby;

use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - WPGatsby
 */
class WPGatsby implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		if ( ! self::is_wp_gatsby_enabled() ) {
			return;
		}

		// Register action monitors.
		add_filter( 'gatsby_action_monitors', [ self::class, 'register_monitors' ], 10, 2 );
		add_action( 'admin_init', [ Settings::class, 'register_settings' ], 11 );
	}

	/**
	 * Returns whether WPGatsby is enabled.
	 */
	public static function is_wp_gatsby_enabled(): bool {
		return class_exists( 'WPGatsby' ) && class_exists( 'WPGraphQL_Settings_API' );
	}

	/**
	 * Registers the custom Action Monitor.
	 *
	 * @param array                                 $monitors .
	 * @param \WPGatsby\ActionMonitor\ActionMonitor $action_monitor .
	 */
	public static function register_monitors( array $monitors, \WPGatsby\ActionMonitor\ActionMonitor $action_monitor ): array {
		$monitors['GravityFormsMonitor'] = new GravityFormsMonitor( $action_monitor );

		return $monitors;
	}
}
