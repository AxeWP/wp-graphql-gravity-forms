<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for a GraphQL Input Type.
 */
interface InputType {
    /**
     * Register input type in GraphQL schema.
     */
    public function register_input_type();
}
