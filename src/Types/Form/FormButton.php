<?php

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Form button.
 *
 * @see https://docs.gravityforms.com/button/
 */
class FormButton implements Hookable {
    const TYPE = 'FormButton';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms form button.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'type'   => [
                    'type'        => 'String',
                    'description' => __( 'Specifies the type of button to be displayed. Possible values: text, image.', 'wp-graphql-gravityforms' ),
                ],
                'text'   => [
                    'type'        => 'String',
                    'description' => __( 'Contains the button text. Only applicable when type is set to text.', 'wp-graphql-gravityforms' ),
                ],
                'imageUrl'   => [
                    'type'        => 'String',
                    'description' => __( 'Contains the URL for the image button. Only applicable when type is set to image.', 'wp-graphql-gravityforms' ),
                ],
                // @TODO - https://docs.gravityforms.com/conditional-logic/
                // 'conditionalLogic'   => [
                //     'type'        => '',
                //     'description' => __( 'Controls when the form button should be visible based on values selected on the form.', 'wp-graphql-gravityforms' ),
                // ],
            ],
        ] );
    }
}
