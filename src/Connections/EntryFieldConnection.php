<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Field\Field;
use WPGraphQLGravityForms\Types\Union\ObjectFieldUnion;
use WPGraphQLGravityForms\Types\Union\ObjectFieldValueUnion;

class EntryFieldConnection implements Hookable, Connection {
    /**
     * The from field name.
     */
    const FROM_FIELD = 'fields';

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
        add_action('init', [ $this, 'register_connection' ] );
    }

    public function register_connection() {
        register_graphql_connection( [
            'fromType'      => Entry::TYPE,
            'toType'        => ObjectFieldUnion::TYPE,
            'fromFieldName' => self::FROM_FIELD,
            'edgeFields' => [
                'fieldValue' => [
                    'type'        => ObjectFieldValueUnion::TYPE,
                    'description' => __('Field value.', 'wp-graphql-gravity-forms'),
                    'resolve' => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
                        $field = $this->get_field_from_gf_field_type( $root['node']['type'] );

                        if ( ! $field ) {
                            return null;
                        }

                        // Account for fields that do not have a value type.
                        if ( ! defined( get_class( $field ) . '::VALUE_TYPE' ) ) {
                            return null;
                        }

                        $value_type_class = 'WPGraphQLGravityForms\Types\Field\FieldValue\\' . get_class( $field )::VALUE_TYPE;

                        return array_merge(
                            // 'type' is added here in order to pass it through to the 'resolveType'
                            // callback function in ObjectFieldValueUnion.
                            [ 'type'  => $field::TYPE ],
                            $value_type_class::get( $root['source'], $root['node'] )
                        );
                    }
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                return ( new EntryFieldConnectionResolver( $root, $args, $context, $info ) )->get_connection();
            },
        ] );
    }

    /**
     * @param string $gf_field_type The Gravity Forms field type.
     *
     * @return Field|null The corresponding WPGraphQL field, or null if not found.
     */
    private function get_field_from_gf_field_type( string $gf_field_type ) {
        $fields = array_filter( $this->instances, function( $instance ) use ( $gf_field_type ) {
            return $instance instanceof Field && $instance::GF_TYPE === $gf_field_type;
        } );

        return $fields ? array_values( $fields )[0] : null;
    }
}
