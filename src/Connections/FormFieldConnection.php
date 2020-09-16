<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Field\Field;
use WPGraphQLGravityForms\Types\Union\ObjectFieldUnion;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;

class FormFieldConnection implements Hookable, Connection {
    /**
     * The from field name.
     */
    const FROM_FIELD = 'fields';

    public function register_hooks() {
        add_action('init', [ $this, 'register_connection' ] );
    }

    public function register_connection() {
        register_graphql_connection( [
            'fromType'      => Form::TYPE,
            'toType'        => ObjectFieldUnion::TYPE,
            'fromFieldName' => self::FROM_FIELD,
            'resolve'       => function( array $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                $fields              = ( new FieldsDataManipulator() )->manipulate( $root['fields'] );
                $connection          = Relay::connectionFromArray( $fields, $args );
                $nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
                $connection['nodes'] = $nodes ?: null;

                return $connection;
            },
        ] );
    }
}
