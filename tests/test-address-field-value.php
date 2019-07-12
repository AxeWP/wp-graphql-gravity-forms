<?php
require_once __DIR__ . '/base-test-class.php';

class TestAddressFieldValue extends BaseTestClass {
    private $fields = [
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

    private $field_values = [];

    public function setUp() {
        $this->field_values = [
            $this->fields[0]['inputs'][0]['id'] => '123 Main St.',
            $this->fields[0]['inputs'][1]['id'] => 'Apt. 456',
            $this->fields[0]['inputs'][2]['id'] => 'Rochester Hills',
            $this->fields[0]['inputs'][3]['id'] => 'Michigan',
            $this->fields[0]['inputs'][4]['id'] => '48306',
            $this->fields[0]['inputs'][5]['id'] => 'USA',
        ];

        $this->create_form( $this->fields );
        $this->create_entry( $this->field_values );
    }

    public function test_address_field_value() {
        $query = "
            query {
                gravityFormsEntry(id: \"{$this->entry_global_id}\") {
                    fields {
                        ... on AddressField {
                            type
                            id
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
                    'fields' => [
                        [
                            'type' => $this->fields[0]['type'],
                            'id'   => $this->fields[0]['id'],
                            'values'=> [
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][0]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][0]['label'],
                                    'key'     => 'street',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][0]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][1]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][1]['label'],
                                    'key'     => 'street2',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][1]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][2]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][2]['label'],
                                    'key'     => 'city',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][2]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][3]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][3]['label'],
                                    'key'     => 'state',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][3]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][4]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][4]['label'],
                                    'key'     => 'zip',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][4]['id'] ],
                                ],
                                [
                                    'inputId' => $this->form['fields'][0]['inputs'][5]['id'],
                                    'label'   => $this->form['fields'][0]['inputs'][5]['label'],
                                    'key'     => 'country',
                                    'value'   => $this->field_values[  $this->fields[0]['inputs'][5]['id'] ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // @TODO: Expand test to include all other fields.

        $actual = graphql( [ 'query' => $query ] );

        $this->assertEquals( $expected, $actual );
    }
}
