<?php
/**
 * Registers Support for Additional Plugins.
 *
 * @package WPGraphQL\GF\Extensions
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions;

use WPGraphQL\GF\Extensions\GFChainedSelects\GFChainedSelects;
use WPGraphQL\GF\Extensions\GFQuiz\GFQuiz;
use WPGraphQL\GF\Extensions\GFSignature\GFSignature;
use WPGraphQL\GF\Extensions\WPGatsby\WPGatsby;
use WPGraphQL\GF\Extensions\WPJamstackDeployments\WPJamstackDeployments;

/**
 * Class - GFSignature
 */
class Extensions {
	/**
	 * Register Gravity Forms Extensions.
	 */
	public static function register() : void {
		GFChainedSelects::register_hooks();
		GFQuiz::register_hooks();
		GFSignature::register_hooks();
		WPGatsby::register_hooks();
		WPJamstackDeployments::register_hooks();
	}
}
