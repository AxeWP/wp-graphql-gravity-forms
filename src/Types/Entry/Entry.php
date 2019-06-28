<?php

namespace WPGraphQLGravityForms\Types\Entry;

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
            'description' => __( 'Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'id' => [
                    'type'        => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'Globally unique ID for the object.', 'wp-graphql-gravity-forms' ),
                ],
                'entryId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The entry ID.', 'wp-graphql-gravity-forms' ),
                ],
                'formId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID of the form from which the entry was submitted.', 'wp-graphql-gravity-forms' ),
                ],
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
                'createdBy' => [
                    'type'        => 'Integer',
                    'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
                ],
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'The current status of the entry (ie "Active", "Spam", "Trash").', 'wp-graphql-gravity-forms' ),
                ],
                'fieldValues' => [
                    'type'        => [ 'list_of' => FieldValue::TYPE ],
                    'description' => __( 'The entry field values.', 'wp-graphql-gravity-forms' ),
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
            'description' => __( 'Get a Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
            'type' => self::TYPE,
            'args' => [
                'id' => [
                    'type' => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'Globally unique ID for the object. Base-64 encode a string like this, where "123" is the entry ID: "gravityformsentry:123".', 'wp-graphql-gravity-forms' ),
                ],
            ],
            'resolve' => function( $root, array $args ) {
                $id_parts = Relay::fromGlobalId( $args['id'] );

                if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
                    throw new UserError( __( 'A valid global ID must be provided.', 'wp-graphql-gravity-forms' ) );
                }

                $entry = GFAPI::get_entry( $id_parts['id'] );

                if ( ! $entry ) {
                    throw new UserError( __( 'An entry with this ID was not found.', 'wp-graphql-gravity-forms' ) );
                }

                // Create a new 'entryId' key to the entry ID and set 'id' to be the global Relay ID.
                $entry['entryId'] = $entry['id'];
                $entry['id']      = $args['id'];

                return $this->nest_entry_field_values( $this->convert_entry_keys_to_camelcase( $entry ) );
            }
        ] );
    }

    /**
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data with keys converted to camelCase.
     */
    private function convert_entry_keys_to_camelcase( array $entry ) : array {
        $entry['formId']          = $entry['form_id'];
        $entry['postId']          = $entry['post_id'];
        $entry['dateCreated']     = $entry['date_created'];
        $entry['dateUpdated']     = $entry['date_updated'];
        $entry['isStarred']       = $entry['is_starred'];
        $entry['isRead']          = $entry['is_read'];
        $entry['sourceUrl']       = $entry['source_url'];
        $entry['userAgent']       = $entry['user_agent'];
        $entry['paymentStatus']   = $entry['payment_status'];
        $entry['paymentDate']     = $entry['payment_date'];
        $entry['paymentAmount']   = $entry['payment_amount'];
        $entry['paymentMethod']   = $entry['payment_method'];
        $entry['transactionId']   = $entry['transaction_id'];
        $entry['isFulfilled']     = $entry['is_fulfilled'];
        $entry['createdBy']       = $entry['created_by'];
        $entry['transactionType'] = $entry['transaction_type'];

        unset(
            $entry['form_id'],
            $entry['post_id'],
            $entry['date_created'],
            $entry['date_updated'],
            $entry['is_starred'],
            $entry['is_read'],
            $entry['source_url'],
            $entry['user_agent'],
            $entry['payment_status'],
            $entry['payment_date'],
            $entry['payment_amount'],
            $entry['payment_method'],
            $entry['transaction_id'],
            $entry['is_fulfilled'],
            $entry['created_by'],
            $entry['transaction_type']
        );

        return $entry;
    }

    /**
     * Remove field values from the top level of the entry array and nest them under
     * a 'fieldValues' array key.
     *
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data with field values nested under a 'fieldValues' key.
     */
    private function nest_entry_field_values( array $entry ) : array {
        $field_values     = $this->extract_field_values_from_entry_data( $entry );
        $non_field_values = array_diff_key( $entry, $field_values );

        return array_merge( $non_field_values, [ 'fieldValues' => $field_values ] );
    }

    /**
     * Remove all non-fields values from entry array to get an array of just the field values.
     *
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data, with all non-field values removed and field values formatted./
     */
    private function extract_field_values_from_entry_data( array $entry ) : array {
        unset(
            $entry['id'],
            $entry['entryId'],
            $entry['formId'],
            $entry['postId'],
            $entry['dateCreated'],
            $entry['dateUpdated'],
            $entry['isStarred'],
            $entry['isRead'],
            $entry['ip'],
            $entry['sourceUrl'],
            $entry['userAgent'],
            $entry['currency'],
            $entry['paymentStatus'],
            $entry['paymentDate'],
            $entry['paymentAmount'],
            $entry['paymentMethod'],
            $entry['transactionId'],
            $entry['isFulfilled'],
            $entry['createdBy'],
            $entry['transactionType'],
            $entry['status']
        );

        return array_map( function( $entry_key ) use ( $entry ) {
            return [
                'key'   => $entry_key,
                'value' => $entry[ $entry_key ],
            ];
        }, array_keys( $entry ) );
    }
}
