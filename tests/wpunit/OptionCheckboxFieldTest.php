<?php
/**
 * Test OptionCheckbox type.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -OptionCheckboxFieldTest.
 */
class OptionCheckboxFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	protected $product_field_helper;

	/**
	 * Tests the field properties and values.
	 */
	public function testField(): void {
		$this->runTestField();
	}
	/**
	 * Tests submitting the field values as a draft entry with submitGfForm.
	 */
	public function testSubmitDraft(): void {
		$this->runTestSubmitDraft();
	}
	/**
	 * Tests submitting the field values as an entry with submitGfForm.
	 */
	public function testSubmit(): void {
		$this->runTestSubmit();
	}
	/**
	 * Tests updating the field value with updateGfEntry.
	 */
	public function testUpdate(): void {
		$this->runTestUpdate();
	}
	/**
	 * Tests updating the draft field value with updateGfEntry.
	 */
	public function testUpdateDraft():void {
		$this->runTestUpdateDraft();
	}

	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		$this->product_field_helper = $this->tester->getPropertyHelper(
			'ProductField',
			[
				'inputType'             => 'singleproduct',
				'autocompleteAttribute' => 'autocomplete',
				'disableQuantity'       => true,
			]
		);
		return $this->tester->getPropertyHelper(
			'OptionField',
			[
				'inputType'    => 'checkbox',
				'productField' => 1,
				'id'           => 2,
				'enablePrice'  => true,
			]
		);
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields() : array {
		return [
			$this->factory->field->create(
				array_merge(
					$this->product_field_helper->values,
					[
						'inputs' => [
							[
								'id'    => 1.1,
								'label' => 'Name',
								'name'  => null,
							],
							[
								'id'    => 1.2,
								'label' => 'Price',
								'name'  => null,
							],
							[
								'id'    => 1.3,
								'label' => 'Quantity',
								'name'  => null,
							],
						],
					]
				)
			),
			$this->factory->field->create(
				array_merge(
					$this->property_helper->values,
					[
						'inputs' => [
							[
								'id'    => '2.1',
								'label' => 'Name',
								'name'  => null,
							],
							[
								'id'    => '2.2',
								'label' => 'Price',
								'name'  => null,
							],
							[
								'id'    => '2.3',
								'label' => 'Quantity',
								'name'  => null,
							],
						],
					]
				)
			),
		];
	}

	public function mutation_value_field_id() : int {
		return $this->fields[1]->id;
	}


	/**
	 * The value as expected in GraphQL.
	 */
	public function field_value() {
		return [
			[
				'inputId'         => (float) $this->fields[1]['inputs'][0]['id'],
				'text'            => $this->fields[1]['choices'][0]['text'],
				'value'           => $this->fields[1]['choices'][0]['value'],
				'connectedChoice' => [
					'formattedPrice' => $this->fields[1]['choices'][0]['price'] ?? null,
					'isSelected'     => $this->fields[1]['choices'][0]['isSelected'] ?? null,
					'price'          => floatval( preg_replace( '/[^\d\.]/', '', $this->fields[1]['choices'][0]['price'] ) ) ?? null,
					'text'           => $this->fields[1]['choices'][0]['text'],
					'value'          => (string) $this->fields[1]['choices'][0]['value'],
				],
				'connectedInput'  => [
					'id'    => (float) $this->fields[1]['inputs'][0]['id'],
					'label' => $this->fields[1]['inputs'][0]['label'],
					'name'  => $this->fields[1]['inputs'][0]['name'],
				],
			],
			[
				'inputId'         => (float) $this->fields[1]['inputs'][1]['id'],
				'text'            => $this->fields[1]['choices'][1]['text'],
				'value'           => null,
				'connectedChoice' => [
					'formattedPrice' => $this->fields[1]['choices'][1]['price'] ?? null,
					'isSelected'     => $this->fields[1]['choices'][1]['isSelected'] ?? null,
					'price'          => floatval( preg_replace( '/[^\d\.]/', '', $this->fields[1]['choices'][1]['price'] ) ) ?? null,
					'text'           => $this->fields[1]['choices'][1]['text'],
					'value'          => (string) $this->fields[1]['choices'][1]['value'],
				],
				'connectedInput'  => [
					'id'    => (float) $this->fields[1]['inputs'][1]['id'],
					'label' => $this->fields[1]['inputs'][1]['label'],
					'name'  => $this->fields[1]['inputs'][1]['name'],
				],
			],
			[
				'inputId'         => (float) $this->fields[1]['inputs'][2]['id'],
				'text'            => $this->fields[1]['choices'][2]['text'],
				'value'           => $this->fields[1]['choices'][2]['value'],
				'connectedChoice' => [
					'formattedPrice' => $this->fields[1]['choices'][2]['price'] ?? null,
					'isSelected'     => $this->fields[1]['choices'][2]['isSelected'] ?? null,
					'price'          => floatval( preg_replace( '/[^\d\.]/', '', $this->fields[1]['choices'][2]['price'] ) ) ?? null,
					'text'           => $this->fields[1]['choices'][2]['text'],
					'value'          => (string) $this->fields[1]['choices'][2]['value'],
				],
				'connectedInput'  => [
					'id'    => (float) $this->fields[1]['inputs'][2]['id'],
					'label' => $this->fields[1]['inputs'][2]['label'],
					'name'  => $this->fields[1]['inputs'][2]['name'],
				],
			],
		];
	}

	public function field_value_input() {
		$field_value = $this->field_value();
		return [
			[
				'inputId' => $field_value[0]['inputId'],
				'value'   => (string) $this->fields[1]['choices'][0]['value'],
			],
			[
				'inputId' => $field_value[1]['inputId'],
				'value'   => (string) $field_value[1]['value'],
			],
			[
				'inputId' => $field_value[2]['inputId'],
				'value'   => (string) $this->fields[1]['choices'][2]['value'],
			],
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			[
				'inputId' => (float) $this->fields[1]['inputs'][0]['id'],
				'text'    => $this->fields[1]['choices'][0]['text'],
				'value'   => null,
			],
			[
				'inputId' => (float) $this->fields[1]['inputs'][1]['id'],
				'text'    => $this->fields[1]['choices'][1]['text'],
				'value'   => (string) $this->fields[1]['choices'][1]['value'],
			],
			[
				'inputId' => (float) $this->fields[1]['inputs'][2]['id'],
				'text'    => $this->fields[1]['choices'][2]['text'],
				'value'   => (string) $this->fields[1]['choices'][2]['value'],
			],
		];
	}

	public function updated_field_value_input() {
		$field_value = $this->updated_field_value();
		return [
			[
				'inputId' => $field_value[0]['inputId'],
				'value'   => $field_value[0]['value'],
			],
			[
				'inputId' => $field_value[1]['inputId'],
				'value'   => (string) $this->fields[1]['choices'][1]['value'],
			],
			[
				'inputId' => $field_value[2]['inputId'],
				'value'   => (string) $this->fields[1]['choices'][2]['value'],
			],
		];
	}


	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			(string) $this->fields[1]['inputs'][0]['id'] => $this->fields[1]->label,
			(string) $this->fields[1]['inputs'][1]['id'] => $this->fields[1]->basePrice,
			(string) $this->field_value[0]['inputId']    => (string) $this->field_value[0]['value'],
			(string) $this->field_value[2]['inputId']    => (string) $this->field_value[2]['value'],
		];
	}

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on OptionField {
				adminLabel
				canPrepopulate
				choices{
					formattedPrice
					isSelected
					price
					text
					value
				}
				cssClass
				defaultValue
				description
				descriptionPlacement
				errorMessage
				hasChoiceValue
				inputName
				inputType
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				placeholder
				productField
				type
				... on OptionCheckboxField {
					checkboxValues {
						inputId
						text
						value
						connectedChoice {
							... on OptionFieldChoice {
								formattedPrice
								isSelected
								price
								text
								value
							}
						}
						connectedInput {
							... on OptionCheckboxInputProperty {
								id
								label
								name
							}
						}
					}
					hasSelectAll
					inputs {
						id
						label
						name
					}
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: [CheckboxFieldInput]!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, checkboxValues: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on OptionCheckboxField {
									checkboxValues {
										inputId
										text
										value
										connectedChoice {
											... on OptionFieldChoice {
												formattedPrice
												isSelected
												price
												text
												value
											}
										}
										connectedInput {
											... on OptionCheckboxInputProperty {
												id
												label
												name
											}
										}
									}
								}
							}
						}
						... on GfSubmittedEntry {
							databaseId
						}
						... on GfDraftEntry {
							resumeToken
						}
					}
				}
			}
		';
	}

	/**
	 * Returns the UpdateEntry mutation string.
	 */
	public function update_entry_mutation() : string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: [CheckboxFieldInput]! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								__typename
								... on OptionCheckboxField {
									checkboxValues {
										inputId
										text
										value
									}
								}
							}
						}
					}
				}
			}
		';
	}

	/**
	 * Returns the UpdateDraftEntry mutation string.
	 */
	public function update_draft_entry_mutation() : string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: [CheckboxFieldInput]! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on OptionCheckboxField {
									checkboxValues {
										inputId
										text
										value
									}
								}
							}
						}
					}
				}
			}
		';
	}

	/**
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 */
	public function expected_field_response( array $form ) : array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][1] );
		$expected[] = $this->expected_field_value( 'checkboxValues', $this->field_value );

		return [
			$this->expectedObject(
				'gfEntry',
				[
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								$expected,
								1
							),
						]
					),
				]
			),
		];
	}

	/**
	 * The expected WPGraphQL mutation response.
	 *
	 * @param string $mutationName .
	 * @param mixed  $value .
	 * @return array
	 */
	public function expected_mutation_response( string $mutationName, $value ) : array {
		return [
			$this->expectedObject(
				$mutationName,
				[
					$this->expectedObject(
						'entry',
						[
							$this->expectedObject(
								'formFields',
								[
									$this->expectedField( 'nodes.1.checkboxValues', $value ),
								]
							),
						]
					),
				]
			),
		];
	}

	/**
	 * Checks if values submitted by GraphQL are the same as whats stored on the server.
	 *
	 * @param array $actual_entry .
	 * @param array $form .
	 */
	public function check_saved_values( $actual_entry, $form ) : void {
		$this->assertEquals( $this->field_value[0]['value'], $actual_entry[ $form['fields'][1]['inputs'][0]['id'] ] );
		$this->assertEquals( $this->field_value[1]['value'], $actual_entry[ $form['fields'][1]['inputs'][1]['id'] ] );
		$this->assertEquals( $this->field_value[2]['value'], $actual_entry[ $form['fields'][1]['inputs'][2]['id'] ] );
	}
}
