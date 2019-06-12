<?php

namespace WPGraphQLGravityForms\Types;

use GFAPI;
use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Gravity Forms form entry.
 *
 * @see https://docs.gravityforms.com/entry-object/
 */
class Entry implements Hookable, Type {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'GravityFormsEntry';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms entry.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'id' => [
                    'type'        => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'The globally unique ID for the object', 'wp-graphql-gravityforms' ),
                ],
                'formId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID of the form from which the entry was submitted.', 'wp-graphql-gravityforms' ),
                ],
                'createdBy' => [
                    'type'        => 'Integer',
                    'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravityforms' ),
                ],
                'dateCreated' => [
                    'type'        => 'String',
                    'description' => __( 'The date and time that the entry was created, in the format "yyyy-mm-dd hh:mi:ss" (i.e. 2010-07-15 17:26:58).', 'wp-graphql-gravityforms' ),
                ],
                'isStarred' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Indicates if the entry has been starred (i.e marked with a star). 1 for entries that are starred and 0 for entries that are not starred.', 'wp-graphql-gravityforms' ),
                ],
                'isRead' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravityforms' ),
                ],
                'ip' => [
                    'type'        => 'String',
                    'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravityforms' ),
                ],
                'sourceUrl' => [
                    'type'        => 'String',
                    'description' => __( 'Source URL of page that contained the form when it was submitted.', 'wp-graphql-gravityforms' ),
                ],
                'postId' => [
                    'type'        => 'Integer',
                    'description' => __( 'For forms with Post fields, this property contains the Id of the Post that was created.', 'wp-graphql-gravityforms' ),
                ],
                'userAgent' => [
                    'type'        => 'String',
                    'description' => __( 'Provides the name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravityforms' ),
                ],
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'The current status of the entry (ie "Active", "Spam", "Trash").', 'wp-graphql-gravityforms' ),
                ],
                'postId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID number of the post created as result of the form submission.', 'wp-graphql-gravityforms' ),
                ],
                'userAgent' => [
                    'type'        => 'String',
                    'description' => __( 'Provides the name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravityforms' ),
                ],
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'The current status of the entry (ie "Active", "Spam", "Trash").', 'wp-graphql-gravityforms' ),
                ],
                /**
                 * @TODO: Add support for getting field values by their IDs ( or getting all)
                 * https://docs.gravityforms.com/entry-object/#field-values
                 */

                /**
                 * @TODO: Add support for these pricing properties that are only relevant
                 * when a Gravity Forms payment gateway add-on is being used:
                 * https://docs.gravityforms.com/entry-object/#pricing-properties
                 */
            ],
        ] );
    }

    public function register_field() {
        register_graphql_field( 'RootQuery', 'gravityFormsEntry', [
            'description' => __( 'Get a Gravity Forms entry.', 'wp-graphql-gravityforms' ),
            'type' => self::TYPE,
            'args' => [
                'id' => [
                    'type' => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'The globally unique ID for the object', 'wp-graphql-gravityforms' ),
                ],
            ],
            'resolve' => function( $root, array $args ) {
                $id_parts = Relay::fromGlobalId( $args['id'] );

                if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
                    throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravityforms' ) );
                }

                $entry = GFAPI::get_entry( $id_parts['id'] );

                if ( ! $entry ) {
                    throw new UserError( __( 'A valid entry ID must be provided.', 'wp-graphql-gravityforms' ) );
                }

                // Set 'entryId' to be the entry ID and 'id' to be the global Relay ID.
                $form['entryId'] = $entry['id'];
                $form['id']      = $args['id'];

                return $this->convert_form_keys_to_camelcase( $entry );
            }
        ] );
    }

    /**
     * @param  GF_Entry $entry Form object.
     *
     * @return GF_Entry $entry Form object with keys converted to camelCase.
     */
    private function convert_form_keys_to_camelcase( GF_Entry $entry ) : GF_Entry {
        // @TODO

        // $form['']    = $form[''];
        // $form[''] = $form[''];
        // $form['']     = $form[''];

        // unset(
        //     $form[''],
        //     $form[''],
        //     $form['']
        // );

        return $form;
    }
}
