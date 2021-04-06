<?php
/**
 * DataLoader - Loaders Registrar
 *
 * Adds data loaders to AppContext.
 *
 * @package WPGraphQLGravityForms\Data\Loader
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Data\Loader;

use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Class - LoadersRegistrar
 */
class LoadersRegistrar implements Hookable {
	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_filter( 'graphql_data_loaders', [ $this, 'register_loaders' ], 10, 2 );
	}

	/**
	 * Registers loaders to AppContext.
	 *
	 * @param array      $loaders Data loaders.
	 * @param AppContext $context App context.
	 *
	 * @return array Data loaders, with new ones added.
	 */
	public function register_loaders( array $loaders, AppContext $context ) : array {
		$loaders[ EntriesLoader::NAME ] = new EntriesLoader( $context );

		return $loaders;
	}
}
