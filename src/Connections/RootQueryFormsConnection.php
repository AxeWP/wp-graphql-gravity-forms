<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Enum\FormStatusEnum;

class RootQueryFormsConnection implements Hookable, Connection {
    /**
     * The from field name.
     */
    const FROM_FIELD = 'gravityFormsForms';

    public function register_hooks() {
        add_action('init', [ $this, 'register_connection' ] );
    }

    public function register_connection() {
        register_graphql_connection( [
            'fromType'      => 'RootQuery',
            'toType'        => Form::TYPE,
            'fromFieldName' => self::FROM_FIELD,
            'connectionArgs' => [
                'status' => [
                    'type'        => FormStatusEnum::TYPE,
                    'description' => __( 'Status of the forms to get.', 'wp-graphql-gravity-forms' ),
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
                return ( new RootQueryFormsConnectionResolver() )->resolve( $root, $args, $context, $info );
            },
        ] );
    }
}
