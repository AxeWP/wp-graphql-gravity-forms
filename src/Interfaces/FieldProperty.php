<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for Gravity Forms field properties.
 */
interface FieldProperty {
    /**
     * Get the field property.
     *
     * @return array Field property data.
     */
    public static function get() : array ;
}
