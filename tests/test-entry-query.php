<?php
require_once __DIR__ . '/base-test-class.php';

class TestEntryQuery extends BaseTestClass {
    protected $fields = [
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
    ];

    protected $field_values = [];

    public function setUp() {
        $this->field_values = [
            $this->fields[0]['id']              => 'This is the single line field value.',
            $this->fields[1]['id']              => 'This is the textarea field value.',
            $this->fields[2]['inputs'][0]['id'] => '123 Main St.',
            $this->fields[2]['inputs'][1]['id'] => 'Apt. 456',
            $this->fields[2]['inputs'][2]['id'] => 'Rochester Hills',
            $this->fields[2]['inputs'][3]['id'] => 'Michigan',
            $this->fields[2]['inputs'][4]['id'] => '48306',
            $this->fields[2]['inputs'][5]['id'] => 'USA',
        ];

        $this->create_form( $this->fields );
        $this->create_entry( $this->field_values );
    }

    public function test_entry_query() {
        $query = "
            query {
                gravityFormsEntry(id: \"{$this->entry_global_id}\") {
                    id
                    entryId
                    formId
                    form {
                        node {
                            id
                            formId
                            title
                            description
                        }
                    }
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
                    fields {
                        ... on TextField {
                            type
                            id
                            value
                        }
                        ... on TextAreaField {
                            type
                            id
                            value
                        }
                        ... on AddressField {
                            type
                            id
                            label
                            values {
                                inputId
                                label
                                key
                                value
                            }
                        }
                    }
                }
            }          
        ";

        $expected = [
            'data' => [
                'gravityFormsEntry' => [
                    'id'          => $this->entry_global_id,
                    'entryId'     => (int) $this->entry['id'],
                    'formId'      => $this->form['id'],
                    'form' => [
                        'node' => [
                            'id'          => $this->form_global_id,
                            'formId'      => $this->form['id'],
                            'title'       => $this->form['title'],
                            'description' => $this->form['description'],
                        ],
                    ],
                    'postId'      => (int) $this->entry['post_id'],
                    'dateCreated' => $this->entry['date_created'],
                    'dateUpdated' => $this->entry['date_updated'],
                    'isStarred'   => (bool) $this->entry['is_starred'],
                    'isRead'      => (bool) $this->entry['is_read'],
                    'ip'          => $this->entry['ip'],
                    'sourceUrl'   => $this->entry['source_url'],
                    'userAgent'   => $this->entry['user_agent'],
                    'status'      => $this->entry['status'],
                    'createdBy'   => (int) $this->entry['created_by'],
                    'status'      => $this->entry['status'],
                    'fields' => [
                        [
                            'type' => $this->form['fields'][0]['type'],
                            'id'   => $this->form['fields'][0]['id'],
                            'value'=> $this->entry[ $this->form['fields'][0]['id'] ],
                        ],
                        [
                            'type' => $this->form['fields'][1]['type'],
                            'id'   => $this->form['fields'][1]['id'],
                            'value'=> $this->entry[ $this->form['fields'][1]['id'] ],
                        ],
                        [
                            'type' => $this->form['fields'][2]['type'],
                            'id'   => $this->form['fields'][2]['id'],
                            'label' => $this->form['fields'][2]['label'],
                            'values'=> [
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][0]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][0]['label'],
                                    'key'     => 'street',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][0]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][1]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][1]['label'],
                                    'key'     => 'street2',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][1]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][2]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][2]['label'],
                                    'key'     => 'city',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][2]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][3]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][3]['label'],
                                    'key'     => 'state',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][3]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][4]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][4]['label'],
                                    'key'     => 'zip',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][4]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][2]['inputs'][5]['id'],
                                    'label'   => $this->form['fields'][2]['inputs'][5]['label'],
                                    'key'     => 'country',
                                    'value'   => $this->entry[ $this->form['fields'][2]['inputs'][5]['id'] ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $actual = graphql( [ 'query' => $query ] );

        $this->assertEquals( $expected, $actual );
    }
}
