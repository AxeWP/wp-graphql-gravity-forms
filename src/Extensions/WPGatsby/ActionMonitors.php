<?php
/**
 * Enables and initializes the Gravity Forms Action Monitor
 *
 * @package WPGraphQLGravityForms\Extensions\WPGatsby
 * @since 0.9.2
 */

namespace WPGraphQLGravityForms\Extensions\WPGatsby;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Class - ActionMonitors
 */
class ActionMonitors implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function register_hooks(): void {
		add_filter( 'gatsby_action_monitors', [ $this, 'register_monitors' ], 10, 2 );
	}

	/**
	 * Registers the custom Action Monitor.
	 *
	 * @param array                                 $monitors .
	 * @param \WPGatsby\ActionMonitor\ActionMonitor $action_monitor .
	 */
	public function register_monitors( array $monitors, \WPGatsby\ActionMonitor\ActionMonitor $action_monitor ) : array {
		$monitors['GravityFormsMonitor'] = new GravityFormsMonitor( $action_monitor );

		return $monitors;
	}
}
