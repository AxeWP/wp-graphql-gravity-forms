<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for a GraphQL Mutation.
 */
interface Mutation {
    /**
     * Register mutation in GraphQL schema.
     */
    public function register_mutation();
}
