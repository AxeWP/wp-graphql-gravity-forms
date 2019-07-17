<?php

namespace WPGraphQLGravityForms\Interfaces;

use GF_Field;

/**
 * Interface for Gravity Forms field values.
 */
interface FieldValue {
    /**
     * Get the field value.
     *
     * @param array    $entry Gravity Forms entry.
     * @param GF_Field $field Gravity Forms field.
     *
     * @return array Entry field value.
     */
    public static function get( array $entry, GF_Field $field ) : array;
}
