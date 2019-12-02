<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for a GraphQL Type.
 */
interface Type {
    /**
     * Register type in GraphQL schema.
     */
    // TODO: Determine best way to re-implement this
    // now that Types\Union\ObjectFieldUnion::register_type()
    // requires an argument.
    // public function register_type();
}
