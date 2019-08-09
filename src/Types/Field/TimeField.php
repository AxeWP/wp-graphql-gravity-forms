<?php

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Field\FieldValue\TimeFieldValues;

/**
 * Time field.
 *
 * @see https://docs.gravityforms.com/gf_field_time/
 */
class TimeField extends Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'TimeField';

    /**
     * Type registered in Gravity Forms.
     */
    const GF_TYPE = 'time';

    /**
     * Field value type.
     */
    const VALUE_TYPE = TimeFieldValues::TYPE;

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms Time field.', 'wp-graphql-gravity-forms' ),
            'fields'      => array_merge(
                $this->get_global_properties(),
                FieldProperty\ErrorMessageProperty::get(),
                FieldProperty\InputNameProperty::get(),
                FieldProperty\IsRequiredProperty::get(),
                FieldProperty\NoDuplicatesProperty::get(),
                FieldProperty\SizeProperty::get()
            ),
        ] );
    }
}
