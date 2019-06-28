<?php

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Form confirmation.
 *
 * @see https://docs.gravityforms.com/confirmation/
 */
class FormConfirmation implements Hookable {
    const TYPE = 'FormConfirmation';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Properties for all the email notifications which exist for a form.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'id' => [
                    'type'        => 'String',
                    'description' => __( 'ID.', 'wp-graphql-gravityforms' ),
                ],
                'name' => [
                    'type'        => 'String',
                    'description' => __( 'Name.', 'wp-graphql-gravityforms' ),
                ],
                'isDefault' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Whether this is the default confirmation.', 'wp-graphql-gravityforms' ),
                ],
                'type' => [
                    'type'        => 'String',
                    'description' => __( 'Determines the type of confirmation to be used. Possible values: message, page, redirect.', 'wp-graphql-gravityforms' ),
                ],
                'message' => [
                    'type'        => 'String',
                    'description' => __( 'Contains the confirmation message that will be displayed. Only applicable when type is set to message.', 'wp-graphql-gravityforms' ),
                ],
                'url' => [
                    'type'        => 'String',
                    'description' => __( 'Contains the URL that the browser will be redirected to. Only applicable when type is set to redirect.', 'wp-graphql-gravityforms' ),
                ],
                'pageId' => [
                    'type'        => 'Integer',
                    'description' => __( 'Contains the Id of the WordPress page that the browser will be redirected to. Only applicable when type is set to page.', 'wp-graphql-gravityforms' ),
                ],
                'queryString' => [
                    'type'        => 'String',
                    'description' => __( 'Contains the query string to be appended to the redirection url. Only applicable when type is set to redirect.', 'wp-graphql-gravityforms' ),
                ],
            ],
        ] );
    }
}
