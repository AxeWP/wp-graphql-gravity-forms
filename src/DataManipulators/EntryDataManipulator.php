<?php

namespace WPGraphQLGravityForms\DataManipulators;

use GraphQLRelay\Relay;
use WPGraphQLGravityForms\Interfaces\DataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;

class EntryDataManipulator implements DataManipulator {
    /**
     * Manipulate entry data.
     *
     * @param array $data The entry data to be manipulated.
     *
     * @return array Manipulated entry data.
     */
    public function manipulate( array $data ) : array {
        $data = $this->set_global_and_entry_ids( $data );
        $data = $this->convert_entry_keys_to_camelcase( $data );

        return $data;
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
        $entry['id']      = Relay::toGlobalId( Entry::TYPE, $entry['entryId'] );

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
            'created_by'       => 'createdById',
            'transaction_type' => 'transactionType',
        ];
    }
}
