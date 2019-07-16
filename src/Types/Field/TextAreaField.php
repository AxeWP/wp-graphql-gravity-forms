<?php

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Field\FieldValue\StringFieldValue;

/**
 * Text Area (Paragraph Text) field.
 *
 * @see https://docs.gravityforms.com/gf_field_textarea/
 */
class TextAreaField extends Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'TextAreaField';

    /**
     * Type registered in Gravity Forms.
     */
    const GF_TYPE = 'textarea';

    /**
     * Field value type.
     */
    const VALUE_TYPE = StringFieldValue::TYPE;

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms Textarea (Paragraph Text) field.', 'wp-graphql-gravity-forms' ),
            'fields'      => array_merge(
                $this->get_global_properties(),
                FieldProperty\DefaultValueProperty::get(),
                FieldProperty\ErrorMessageProperty::get(),
                FieldProperty\InputNameProperty::get(),
                FieldProperty\IsRequiredProperty::get(),
                FieldProperty\NoDuplicatesProperty::get(),
                FieldProperty\SizeProperty::get(),
                FieldProperty\MaxLengthProperty::get()
            ),
        ] );
    }
}
