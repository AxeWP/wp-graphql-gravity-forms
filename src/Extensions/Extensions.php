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
use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - GFSignature
 */
class Extensions implements Hookable {
	/**
	 * Register Gravity Forms Extensions.
	 */
	public static function register_hooks(): void {
		GFChainedSelects::register_hooks();
		GFQuiz::register_hooks();
		GFSignature::register_hooks();
		WPGatsby::register_hooks();
		WPJamstackDeployments::register_hooks();
	}
}
