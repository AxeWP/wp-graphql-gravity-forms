<?php

namespace WPGraphQLGravityForms\Data\Loader;

use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;

class LoadersRegistrar implements Hookable {
    public function register_hooks() {
        add_filter( 'graphql_data_loaders', [ $this, 'register_loaders' ], 10, 2 );
    }

    /**
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
