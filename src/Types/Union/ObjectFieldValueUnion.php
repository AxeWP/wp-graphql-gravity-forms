<?php

namespace WPGraphQLGravityForms\Types\Union;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Registry\TypeRegistry;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\Field\Field;

/**
 * Union between an object and a Gravity Forms field value.
 */
class ObjectFieldValueUnion implements Hookable, Type {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'ObjectFieldValueUnion';

    /**
     * WPGraphQL for Gravity Forms plugin's class instances.
     *
     * @var array
     */
    private $instances;

    /**
     * @param array WPGraphQL for Gravity Forms plugin's class instances.
     */
    public function __construct( array $instances ) {
        $this->instances = $instances;
    }

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ], 11 );
    }

    public function register_type( TypeRegistry $type_registry ) {
        register_graphql_union_type( self::TYPE, [
            'typeNames'   => $this->get_field_value_type_names(),
            'resolveType' => function( $object ) use ( $type_registry ) {
                return $type_registry->get_type( $object['value_class']::TYPE );
            },
        ] );
    }

    private function get_field_value_type_names() : array {
        return array_values( array_map( fn( $class ) => $class::TYPE, $this->get_field_value_classes() ) );
    }

    private function get_field_value_classes() : array {
        $is_field_value_instance = fn( $instance ) => $instance instanceof FieldValue;
        $field_values            = array_filter( $this->instances, $is_field_value_instance );

        /**
         * Filter for adding custom field value class instances.
         * Classes must implement the WPGraphQLGravityForms\Interfaces\FieldValue interface
         * and define a "TYPE" class constant string in this format: "<field_name>Value".
         *
         * @param array $field_values Field value class instances.
         */
        $field_values = apply_filters( 'wp_graphql_gf_field_value_instances', $field_values );

        // Filter the array a second time to guarantee that any classes added are instances of FieldValue.
        return array_filter( $field_values, $is_field_value_instance );
    }
}
