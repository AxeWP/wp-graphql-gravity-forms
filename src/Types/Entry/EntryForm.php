<?php

namespace WPGraphQLGravityForms\Types\Entry;

use GFAPI;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\Form\Form;

/**
 * Form associated with an Entry.
 */
class EntryForm implements Hookable, Type, Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'EntryForm';

    /**
     * Field registered in WPGraphQL.
     */
    const FIELD = 'entryForm';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __('The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'node' => [
                    'type'        => Form::TYPE,
                    'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }

    public function register_field() {
        register_graphql_field( Entry::TYPE, self::FIELD, [
            'type'        => self::TYPE,
            'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
            'resolve'     => function( $form_id ) {
                $form = GFAPI::get_form( $entry['form_id'] );

                // @TODO: resolve this to Form type.
                // Example: https://gist.github.com/jasonbahl/55a6eff4cd67ce639ecd2d9989fef4cc
                return [];
            }
        ] );
    }
}
