<?php

namespace WPGraphQLGravityForms\Types\Entry;

use GFAPI;
use GraphQLRelay;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\Form\Form;

/**
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 */
class EntryForm implements Hookable, Type, Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'EntryForm';

    /**
     * Field registered in WPGraphQL.
     */
    const FIELD = 'form';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    /**
     * Register new edge type.
     */
    public function register_type() {
        register_graphql_type( self::TYPE, [
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
            'resolve'     => function( array $entry ) : array {
                $form = GFAPI::get_form( $entry['formId'] );

                if ( ! $form ) {
                    throw new UserError( __( 'The form used to generate this entry was not found.', 'wp-graphql-gravity-forms' ) );
                }

                return [
                    'node' => Form::convert_form_keys_to_camelcase( Form::set_global_and_form_ids( $form ) ),
                ];
            }
        ] );
    }
}
