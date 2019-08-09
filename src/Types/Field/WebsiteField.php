<?php

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Field\FieldValue\StringFieldValue;

/**
 * Website field.
 *
 * @see https://docs.gravityforms.com/gf_field_website/
 */
class WebsiteField extends Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'WebsiteField';

    /**
     * Type registered in Gravity Forms.
     */
    const GF_TYPE = 'website';

    /**
     * Field value type.
     */
    const VALUE_TYPE = StringFieldValue::TYPE;

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms Website field.', 'wp-graphql-gravity-forms' ),
            'fields'      => array_merge(
                $this->get_global_properties(),
                FieldProperty\DefaultValueProperty::get(),
                FieldProperty\ErrorMessageProperty::get(),
                FieldProperty\InputNameProperty::get(),
                FieldProperty\IsRequiredProperty::get(),
                FieldProperty\NoDuplicatesProperty::get(),
                FieldProperty\SizeProperty::get()
            ),
        ] );
    }
}
