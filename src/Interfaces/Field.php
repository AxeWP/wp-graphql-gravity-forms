<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for a GraphQL Field.
 */
interface Field {
    /**
     * Register field in GraphQL schema.
     */
    public function register_field();
}
