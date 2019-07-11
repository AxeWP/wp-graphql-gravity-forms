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
                    'description' => __( 'Globally unique ID for the object.', 'wp-graphql-gravity-forms' ),
                ],
                'entryId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The entry ID.', 'wp-graphql-gravity-forms' ),
                ],
                'formId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID of the form that was submitted to generate this entry.', 'wp-graphql-gravity-forms' ),
                ],
                'form' => [
                    'type'        => EntryForm::TYPE,
                    'description' => __( 'The form that was submitted to generate this entry.', 'wp-graphql-gravity-forms' ),
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
                // @TODO: Consider making this a connection rather than a list.
                'fields' => [
                    'type'        => [ 'list_of' => ObjectFieldUnion::TYPE ],
                    'description' => __( 'The entry fields and their values.', 'wp-graphql-gravity-forms' ),
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
        register_graphql_field( 'RootQuery', self::FIELD, [
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
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
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

                $field_values = $this->extract_field_values_from_entry( $entry );

                if ( $this->were_fields_requested( $info ) ) {
                    // @TODO: Maybe try to get this from this field value: wp-content/plugins/wp-graphql-gravity-forms/src/Types/Entry/EntryForm.php
                    // That would avoid querying for the form data twice.
                    $form = GFAPI::get_form( $entry['form_id'] );

                    if ( ! $form ) {
                        throw new UserError( __( 'The form used to generate this entry was not found.', 'wp-graphql-gravity-forms' ) );
                    }

                    $entry = $this->add_fields_data_to_entry( $entry, $field_values, $form );
                }

                $entry = $this->remove_top_level_field_values_from_entry( $entry, $field_values );
                $entry = $this->convert_entry_keys_to_camelcase( $entry );

                return $entry;
            }
        ] );
    }

    /**
     * @param array $entry Entry data.
     *
     * @return array $entry Entry data, with all non-field values removed and field values formatted./
     */
    private function extract_field_values_from_entry( array $entry ) : array {
        $non_field_value_keys = $this->get_non_field_value_keys();

        return array_filter( $entry, function( $key ) use ( $non_field_value_keys ) {
            return ! in_array( $key, $non_field_value_keys );
        }, ARRAY_FILTER_USE_KEY );
    }

    /**
     * @param array $entry        Entry data.
     * @param array $field_values Field values from entry.
     *
     * @return array The entry, with top-level field values removed.
     */
    private function remove_top_level_field_values_from_entry( array $entry, array $field_values ) : array {
        return array_diff_key( $entry, $field_values );
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

    /**
     * @return array All non-field value entry keys.
     */
    private function get_non_field_value_keys() : array {
        return array_merge(
            array_keys( $this->get_key_mappings() ),
            ['id', 'entryId', 'ip', 'currency', 'status']
        );
    }

    /**
     * @param ResolveInfo $info Request info.
     *
     * @return bool Whether fields data was requested.
     */
    private function were_fields_requested( ResolveInfo $info ) : bool {
        return ! empty( $info->getFieldSelection( 1 )['fields'] );
    }

    /**
     * @param array $entry        Entry data.
     * @param array $field_values Field values from entry.
     * @param array $form         Form meta array.
     *
     * @return array Entry with field value data added.
     */
    private function add_fields_data_to_entry( array $entry, array $field_values, array $form ) : array {
        $entry['fields'] = array_reduce( $form['fields'], function( $fields, $field ) use ( $entry, $field_values ) {
            if ( 'text' === $field['type'] || 'textarea' === $field['type'] ) {
                $field['value'] = $entry[ $field['id'] ];
            }

            if ( 'address' === $field['type'] ) {
                $values = [];

                foreach ( ['street', 'street2', 'city', 'state', 'zip', 'country'] as $index => $key ) {
                    $values[] = [
                        'inputId' => $field['inputs'][ $index ]['id'],
                        'label'   => $field['inputs'][ $index ]['label'],
                        'key'     => $key,
                        'value'   => $field_values[ $field['inputs'][ $index ]['id'] ],
                    ];
                }

                $field['values'] = $values;
            }

            // @TODO - Add all other fields.

            $fields[] = $field;

            return $fields;
        }, [] );

        return $entry;
    }
}
