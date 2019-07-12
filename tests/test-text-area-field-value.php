<?php
require_once __DIR__ . '/base-test-class.php';

class TestTextAreaFieldValue extends BaseTestClass {
    private $fields = [
        [
            'id'   => '2',
            'type' => 'textarea',
        ],
    ];

    private $field_values = [];

    public function setUp() {
        $this->field_values = [
            $this->fields[0]['id'] => 'This is the textarea field value.',
        ];

        $this->create_form( $this->fields );
        $this->create_entry( $this->field_values );
    }

    public function test_text_area_field_value() {
        $query = "
            query {
                gravityFormsEntry(id: \"{$this->entry_global_id}\") {
                    fields {
                        ... on TextAreaField {
                            type
                            id
                            value
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
                            'value'=> $this->field_values[ $this->fields[0]['id'] ],
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
