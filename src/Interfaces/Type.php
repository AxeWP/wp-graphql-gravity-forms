<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for a GraphQL Type.
 */
interface Type {
    /**
     * Register type in GraphQL schema.
     */
    public function register_type();
}
