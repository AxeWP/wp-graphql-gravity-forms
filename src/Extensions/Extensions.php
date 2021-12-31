<?php
/**
 * Registers Support for Additional Plugins.
 *
 * @package WPGraphQL\GF\Extensions
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions;

use WPGraphQL\GF\Extensions\GFSignature\GFSignature;

/**
 * Class - GFSignature
 */
class Extensions {

	/**
	 * Register Gravity Forms Extensions.
	 */
	public static function register() : void {
		GFSignature::register_hooks();
	}
}
