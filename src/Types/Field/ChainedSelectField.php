<?php

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Chained Select field.
 *
 * @see https://www.gravityforms.com/add-ons/chained-selects/
 * @see https://docs.gravityforms.com/category/add-ons-gravity-forms/chained-selects/
 */
class ChainedSelectField extends Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'ChainedSelectField';

    /**
     * Type registered in Gravity Forms.
     */
    const GF_TYPE = 'chainedselect';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms Chained Select field.', 'wp-graphql-gravity-forms' ),
            'fields'      => array_merge(
                $this->get_global_properties()
                // FieldProperty\IsRequiredProperty::get(),
                // FieldProperty\SizeProperty::get(),
                // FieldProperty\ErrorMessageProperty::get(),
                // FieldProperty\InputsProperty::get()
            ),
        ] );
    }
}
