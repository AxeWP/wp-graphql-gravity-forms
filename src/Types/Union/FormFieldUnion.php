<?php

namespace WPGraphQLGravityForms\Types\Union;

use GF_Field;
use WPGraphQL\TypeRegistry;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\Field;

/**
 * Union between a Gravity Forms form and a Gravity Forms field.
 */
class FormFieldUnion implements Hookable, Type {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'FormFieldUnion';

    /**
     * WPGraphQL plugin's class instances.
     *
     * @var array
     */
    private $instances;

    /**
     * @param array WPGraphQL plugin's class instances.
     */
    public function __construct( array $instances ) {
        $this->instances = $instances;
    }

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ], 11 );
    }

    public function register_type() {
        $field_mappings = $this->get_field_type_mappings();

        register_graphql_union_type( self::TYPE, [
            'typeNames'   => array_values( $field_mappings ),
            'resolveType' => function( GF_Field $field ) use ( $field_mappings ) {
                if ( isset( $field_mappings[ $field['type'] ] ) ) {
                    return TypeRegistry::get_type( $field_mappings[ $field['type'] ] );
                }

                return null;
            },
        ] );
    }

    /**
     * Get mappings from the field types registered in Gravity Forms
     * to the corresponding field types registered in WPGraphQL.
     * Example: [ 'textarea' => 'TextArea' ]
     *
     * Key values are: html, hidden, section, text, website, phone,
     * number, date, time, textarea, select, checkbox, radio, name,
     * address, fileupload, email, post_title, post_content, post_excerpt,
     * post_tags, post_category, post_image, post_custom_field, captcha
     *
     * @return array Field type mappings.
     */
    private function get_field_type_mappings() {
        $fields = array_filter( $this->instances, function( $instance ) {
			return $instance instanceof Field;
        } );

        return array_reduce( $fields, function( $mappings, $field ) {
            $mappings[ $field::GF_TYPE ] = $field::TYPE;

            return $mappings;
        }, [] );
    }
}
