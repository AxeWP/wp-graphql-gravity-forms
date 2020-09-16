<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Interfaces\FieldValue as FieldValueInterface;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Field\Field;
use WPGraphQLGravityForms\Types\Field\FieldValue;
use WPGraphQLGravityForms\Types\Union\ObjectFieldUnion;
use WPGraphQLGravityForms\Types\Union\ObjectFieldValueUnion;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;

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
                    'description' => __( 'Field value.', 'wp-graphql-gravity-forms' ),
                    'resolve' => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
                        $field = $this->get_field_by_gf_field_type( $root['node']['type'] );

                        if ( ! $field ) {
                            return null;
                        }

                        $value_class = $this->get_field_value_class( $field );

                        // Account for fields that do not have a value class.
                        if ( ! $value_class ) {
                            return null;
                        }

                        return array_merge(
                            // 'value_class' is included here to pass it through to the "resolveType"
                            // callback function in ObjectFieldValueUnion.
                            [ 'value_class' => $value_class ],
                            $value_class::get( $root['source'], $root['node'] )
                        );
                    }
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                $form  = GFAPI::get_form( $root['formId'] );

                if ( ! $form ) {
                    throw new UserError( __( 'The form used to generate this entry was not found.', 'wp-graphql-gravity-forms' ) );
                }

                $fields     = ( new FieldsDataManipulator() )->manipulate( $form['fields'] );
                $connection = Relay::connectionFromArray( $fields, $args );

                // Add the entry to each edge with a key of 'source'. This is needed so that
                // the fieldValue edge field resolver has has access to the form entry.
                $connection['edges'] = array_map( function( $edge ) use ( $root ) {
                    $edge['source'] = $root;
                    return $edge;
                }, $connection['edges'] );

                $nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
                $connection['nodes'] = $nodes ?: null;
                return $connection;
            },
        ] );
    }

    /**
     * @param string $gf_field_type The Gravity Forms field type.
     *
     * @return Field|null The corresponding WPGraphQL field, or null if not found.
     */
    private function get_field_by_gf_field_type( string $gf_field_type ) {
        $fields = array_filter( $this->instances, fn( $instance ) => $instance instanceof Field );

        /**
         * Filter for adding custom field class instances.
         * Classes must extend the WPGraphQLGravityForms\Types\Field\Field class and
         * contain a "GF_TYPE" class constant specifying the Gravity Forms field type.
         *
         * @param array $fields Gravity Forms field class instances.
         */
        $fields = apply_filters( 'wp_graphql_gf_form_field_instances', $fields );

        $field_array = array_filter( $fields, function( $instance ) use ( $gf_field_type ) {
            return $instance instanceof Field && $instance::GF_TYPE === $gf_field_type;
        } );

        return $field_array ? array_values( $field_array )[0] : null;
    }

    /**
     * Get the field value class associated with a form field.
     *
     * @param  Field $field The field class.
     *
     * @return FieldValue|null The field value class or null if not found.
     */
    private function get_field_value_class( Field $field ) {
        $field_values = array_filter( $this->instances, fn( $instance ) => $instance instanceof FieldValueInterface );

        /**
         * Filter for adding custom field value class instances.
         * Classes must implement the WPGraphQLGravityForms\Interfaces\FieldValue interface
         * and contain a "TYPE" class constant string in this format: "<field_name>Value".
         *
         * @param array $field_values Field value class instances.
         */
        $field_values = apply_filters( 'wp_graphql_gf_field_value_instances', $field_values );

        $value_class_array = array_filter( $field_values, function( $instance ) use ( $field ) {
            return $instance instanceof FieldValueInterface && $instance::TYPE === $field::TYPE . 'Value';
        } );

        return $value_class_array ? array_values( $value_class_array )[0] : null;
    }
}
