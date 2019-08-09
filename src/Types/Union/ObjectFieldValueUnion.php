<?php

namespace WPGraphQLGravityForms\Types\Union;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\TypeRegistry;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
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

    public function register_type() {
        $field_value_types = $this->get_field_value_types();

        register_graphql_union_type( self::TYPE, [
            'typeNames'   => array_unique( array_values( $field_value_types ) ),
            'resolveType' => function( $object, AppContext $context, ResolveInfo $info ) use ( $field_value_types ) {
                if ( isset( $field_value_types[ $object['type'] ] ) ) {
                    return TypeRegistry::get_type( $field_value_types[ $object['type'] ] );
                }

                return null;
            },
        ] );
    }

    /**
     * Get field types and their related field value types.
     * Example: [ 'AddressField' => 'AddressFieldValues' ]
     *
     * @return array Field value types.
     */
    private function get_field_value_types() : array {
        $fields_with_value_types = array_filter( $this->instances, function( $instance ) {
            return $instance instanceof Field && defined( get_class( $instance ) . '::VALUE_TYPE' );
        } );

        return array_reduce( $fields_with_value_types, function( $value_types, $field ) {
            $value_types[ $field::TYPE ] = $field::VALUE_TYPE;

            return $value_types;
        }, [] );
    }
}
