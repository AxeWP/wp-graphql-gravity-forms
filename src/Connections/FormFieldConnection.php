<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Field\Field;
use WPGraphQLGravityForms\Types\Union\ObjectFieldUnion;

class FormFieldConnection implements Hookable, Connection {
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
            'fromType'      => Form::TYPE,
            'toType'        => ObjectFieldUnion::TYPE,
            'fromFieldName' => self::FROM_FIELD,
            'resolve'       => function( array $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                return ( new FormFieldConnectionResolver( $root, $args, $context, $info ) )->get_connection();
            },
        ] );
    }
}
