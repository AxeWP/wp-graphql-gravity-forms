<?php

class TestEntryQuery extends WP_UnitTestCase {
    private $form_id;

    public function setUp() {
        $form = [
            'title'       => 'Blank Form Title',
            'description' => 'Blank Form Description',
            'fields'      => [
                [
                    'id'   => 1,
                    'type' => 'text',
                ],
                [
                    'id'   => 2,
                    'type' => 'textarea',
                ],
                [
                    'id'   => 3,
                    'type' => 'address',
                    'label' => 'Address',
                    'adminLabel' => '',
                    'isRequired' => 'false',
                    'size' => 'medium',
                    'errorMessage' =>' ',
                    'visibility' => 'visible',
                    'addressType' => 'international',
                    'inputs' => [
                        [
                            'id'    => '3.1',
                            'label' => 'Street Address',
                            'name'  => '',
                        ],
                        [
                            'id'    => '3.2',
                            'label' => 'Address Line 2',
                            'name'  => '',
                        ],
                        [
                            'id'    => '3.3',
                            'label' => 'City',
                            'name'  => '',
                        ],
                        [
                            'id'    => '3.4',
                            'label' => 'State / Province',
                            'name'  => '',
                        ],
                        [
                            'id'    => '3.5',
                            'label' => 'ZIP / Postal Code',
                            'name'  => '',
                        ],
                        [
                            'id'    => '3.6',
                            'label' => 'Country',
                            'name'  => '',
                        ],
                    ],
                ],
            ],
        ];

        // Insert form into the DB using the mock data above.
        $this->form_id = \GFAPI::add_form( $form );
    }

    /**
	 * Test Gravity Forms entry query.
	 */
    public function test_form_query() {
        $entry = [
            'id'               => 1,
            'form_id'          => $this->form_id,
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
            '1'                => 'This is the single line field value.',
            '2'                => 'This is the textarea field value.',
            '3.1'              => '123 Main St.',
            '3.2'              => 'Apt. 456',
            '3.3'              => 'Rochester Hills',
            '3.4'              => 'Michigan',
            '3.5'              => '48306',
            '3.6'              => 'USA',
        ];

        // Insert entry into the DB using the mock data above.
        $entry_id  = \GFAPI::add_entry( $entry );
        $global_id = \GraphQLRelay\Relay::toGlobalId( 'gravityformsentry', $entry_id );

        $query = "
            query {
                gravityFormsEntry(id: \"{$global_id}\") {
                    id
                    entryId
                    formId
                    postId
                    dateCreated
                    dateUpdated
                    isStarred
                    isRead
                    ip
                    sourceUrl
                    userAgent
                    status
                    createdBy
                    status
                    fieldValues {
                        key
                        value
                    }
                }
            }          
        ";

        // @TODO Add pricing fields to test query once plugin supports them.

        $expected = [
            'data' => [
                'gravityFormsEntry' => [
                    'id'          => $global_id,
                    'entryId'     => $entry_id,
                    'formId'      => $entry['form_id'],
                    'postId'      => $entry['post_id'],
                    'dateCreated' => $entry['date_created'],
                    'dateUpdated' => $entry['date_updated'],
                    'isStarred'   => $entry['is_starred'],
                    'isRead'      => $entry['is_read'],
                    'ip'          => $entry['ip'],
                    'sourceUrl'   => $entry['source_url'],
                    'userAgent'   => $entry['user_agent'],
                    'status'      => $entry['status'],
                    'createdBy'   => $entry['created_by'],
                    'status'      => $entry['status'],
                    'fieldValues' => [
                        [
                            'key'   => '1',
                            'value' => $entry['1'],
                        ],
                        [
                            'key'   => '2',
                            'value' => $entry['2'],
                        ],
                        [
                            'key'   => '3.1',
                            'value' => $entry['3.1'],
                        ],
                        [
                            'key'   => '3.2',
                            'value' => $entry['3.2'],
                        ],
                        [
                            'key'   => '3.3',
                            'value' => $entry['3.3'],
                        ],
                        [
                            'key'   => '3.4',
                            'value' => $entry['3.4'],
                        ],
                        [
                            'key'   => '3.5',
                            'value' => $entry['3.5'],
                        ],
                        [
                            'key'   => '3.6',
                            'value' => $entry['3.6'],
                        ],
                    ],
                ],
            ],
        ];

        $actual = graphql( [ 'query' => $query ] );

        $this->assertEquals( $expected, $actual );
    }
}
