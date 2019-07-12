<?php
require_once __DIR__ . '/base-test-class.php';

class TestTextFieldValue extends BaseTestClass {
    private $fields = [
        [
            'type' => 'text',
            'id' => 1,
            'label' => 'Single Line Text',
            'adminLabel' => '',
            'isRequired' => false,
            'size' => 'medium',
            'errorMessage' => '',
            'visibility' => 'visible',
            'inputs' => null,
            'formId' => 2,
            'description' => 'I am a single line text field.',
            'allowsPrepopulate' => false,
            'inputMask' => false,
            'inputMaskValue' => '',
            'inputMaskIsCustom' => false,
            'maxLength' => '',
            'inputType' => '',
            'labelPlacement' => '',
            'descriptionPlacement' => '',
            'subLabelPlacement' => '',
            'placeholder' => '',
            'cssClass' => '',
            'inputName' => '',
            'noDuplicates' => false,
            'defaultValue' => '',
            'choices' => '',
            'productField' => '',
            'enablePasswordInput' => '',
            'multipleFiles' => false,
            'maxFiles' => '',
            'calculationFormula' => '',
            'calculationRounding' => '',
            'enableCalculation' => '',
            'disableQuantity' => false,
            'displayAllCategories' => false,
            'useRichTextEditor' => false,
            'checkboxLabel' => '',
            'pageNumber' => 1,
            'fields' => '',
            'displayOnly' => ''
        ],
    ];

    private $field_values = [];

    public function setUp() {
        $this->field_values = [
            $this->fields[0]['id'] => 'This is the text field value.',
        ];

        $this->create_form( $this->fields );
        $this->create_entry( $this->field_values );
    }

    public function test_text_field_value() {
        $query = "
            query {
                gravityFormsEntry(id: \"{$this->entry_global_id}\") {
                    fields {
                        ... on TextField {
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
