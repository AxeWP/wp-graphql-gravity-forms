<?php

namespace WPGraphQLGravityForms\Types\Entry;

use GFAPI;
use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\Union\ObjectFieldUnion;
use WPGraphQLGravityForms\Types\Form\Form;

/**
 * Gravity Forms form entry.
 *
 * @see https://docs.gravityforms.com/entry-object/
 */
class Entry implements Hookable, Type, Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'GravityFormsEntry';

    /**
     * Field registered in WPGraphQL.
     */
    const FIELD = 'gravityFormsEntry';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'id' => [
                    'type'        => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
                ],
                'entryId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The entry ID.', 'wp-graphql-gravity-forms' ),
                ],
                'formId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID of the form that was submitted to generate this entry.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Add field to get post data.
                'postId' => [
                    'type'        => 'Integer',
                    'description' => __( 'For forms with Post fields, this property contains the Id of the Post that was created.', 'wp-graphql-gravity-forms' ),
                ],
                'dateCreated' => [
                    'type'        => 'String',
                    'description' => __( 'The date and time that the entry was created, in the format "yyyy-mm-dd hh:mi:ss" (i.e. 2010-07-15 17:26:58).', 'wp-graphql-gravity-forms' ),
                ],
                'dateUpdated' => [
                    'type'        => 'String',
                    'description' => __( 'The date and time that the entry was updated, in the format "yyyy-mm-dd hh:mi:ss" (i.e. 2010-07-15 17:26:58).', 'wp-graphql-gravity-forms' ),
                ],
                'isStarred' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Indicates if the entry has been starred (i.e marked with a star). 1 for entries that are starred and 0 for entries that are not starred.', 'wp-graphql-gravity-forms' ),
                ],
                'isRead' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Indicates if the entry has been read. 1 for entries that are read and 0 for entries that have not been read.', 'wp-graphql-gravity-forms' ),
                ],
                'ip' => [
                    'type'        => 'String',
                    'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
                ],
                'sourceUrl' => [
                    'type'        => 'String',
                    'description' => __( 'Source URL of page that contained the form when it was submitted.', 'wp-graphql-gravity-forms' ),
                ],
                'userAgent' => [
                    'type'        => 'String',
                    'description' => __( 'Provides the name and version of both the browser and operating system from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
                ],
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'The current status of the entry (ie "Active", "Spam", "Trash").', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Add field to get user data.
                'createdBy' => [
                    'type'        => 'Integer',
                    'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
                ],
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'The current status of the entry (ie "Active", "Spam", "Trash").', 'wp-graphql-gravity-forms' ),
                ],
                /**
                 * @TODO: Add support for these pricing properties that are only relevant
                 * when a Gravity Forms payment gateway add-on is being used:
                 * https://docs.gravityforms.com/entry-object/#pricing-properties
                 */
            ],
        ] );
    }

    public function register_field() {
        register_graphql_field( 'RootQuery', self::FIELD, [
            'description' => __( 'Get a Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
            'type' => self::TYPE,
            'args' => [
                'id' => [
                    'type' => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( "Unique global ID for the object. Base-64 encode a string like this, where '123' is the entry ID: '{self::TYPE}:123'.", 'wp-graphql-gravity-forms' ),
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
                $id_parts = Relay::fromGlobalId( $args['id'] );

                if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
                    throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
                }

                $entry = GFAPI::get_entry( $id_parts['id'] );

                if ( ! $entry ) {
                    throw new UserError( __( 'An entry with this ID was not found.', 'wp-graphql-gravity-forms' ) );
                }

                return $this->convert_entry_keys_to_camelcase( $this->set_global_and_entry_ids( $entry ) );
            }
        ] );
    }

    /**
     * Set 'entryId' to be the entry ID and 'id' to be the global Relay ID.
     *
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data, with the entry ID and global Relay ID set.
     */
    private function set_global_and_entry_ids( array $entry ) : array {
        $entry['entryId'] = $entry['id'];
        $entry['id']      = $args['id'] ?? Relay::toGlobalId( self::TYPE, $entry['entryId'] );

        return $entry;
    }

    /**
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data with keys converted to camelCase.
     */
    private function convert_entry_keys_to_camelcase( array $entry ) : array {
        foreach ( $this->get_key_mappings() as $snake_case_key => $camel_case_key ) {
            $entry[ $camel_case_key ] = $entry[ $snake_case_key ];
            unset( $entry[ $snake_case_key ] );
        }

        return $entry;
    }

    /**
     * @return array Gravity Forms Entry meta keys and their camelCase equivalents.
     */
    private function get_key_mappings() : array {
        return [
            'form_id'          => 'formId',
            'post_id'          => 'postId',
            'date_created'     => 'dateCreated',
            'date_updated'     => 'dateUpdated',
            'is_starred'       => 'isStarred',
            'is_read'          => 'isRead',
            'source_url'       => 'sourceUrl',
            'user_agent'       => 'userAgent',
            'payment_status'   => 'paymentStatus',
            'payment_date'     => 'paymentDate',
            'payment_amount'   => 'paymentAmount',
            'payment_method'   => 'paymentMethod',
            'transaction_id'   => 'transactionId',
            'is_fulfilled'     => 'isFulfilled',
            'created_by'       => 'createdBy',
            'transaction_type' => 'transactionType',
        ];
    }
}
