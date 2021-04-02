<?php

use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Entry\Entry;

abstract class BaseTestClass extends WP_UnitTestCase {
    /**
     * Form object for the created form.
     */
    protected $form;

    /**
     * Global Relay ID for the created form.
     */
    protected $form_global_id;

    /**
     * Entry data array for the created entry.
     */
    protected $entry;

    /**
     * Global Relay ID for the created entry.
     */
    protected $entry_global_id;

    /**
     * Insert a form with mock data into the database and set related class properties.
     *
     * @param array $fields The fields to include when creating the form.
     */
    protected function create_form( array $fields ) {
        $form = [
            'title'       => 'Form for Field Tests',
            'description' => 'Form for Field Tests Description',
            'fields'      => $fields,
        ];

        $form_id              = \GFAPI::add_form( $form );
        $this->form           = \GFAPI::get_form( $form_id );
        $this->form_global_id = \GraphQLRelay\Relay::toGlobalId( Form::TYPE, $form_id );
    }

    /**
     * Insert an entry with mock data into the database and set related class properties.
     *
     * @param array $field_values Entry field values in this format: [ '2.3' => 'Some value' ]
     */
    protected function create_entry( array $field_values ) {
        $entry = [
            'id'               => 1,
            'form_id'          => $this->form['id'],
            'post_id'          => 3,
            'date_created'     => '2019-06-26 01:53:42',
            'date_updated'     => '2019-06-26 01:53:42',
            'is_starred'       => false,
            'is_read'          => true,
            'ip'               => '172.17.0.1',
            'source_url'       => 'http://example.com/text-fields-form/',
            'user_agent'       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
            'currency'         => 'USD',
            'payment_status'   => '',
            'payment_date'     => '',
            'payment_amount'   => '',
            'payment_method'   => '',
            'transaction_id'   => '',
            'is_fulfilled'     => '',
            'created_by'       => 1,
            'transaction_type' => '',
            'status'           => 'active',
        ];

        $entry_id              = \GFAPI::add_entry( $entry + $field_values );
        $this->entry           = \GFAPI::get_entry( $entry_id );
        $this->entry_global_id = \GraphQLRelay\Relay::toGlobalId( Entry::TYPE, $entry_id );
    }
}
