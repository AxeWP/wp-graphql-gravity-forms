<?php
/**
 * Test CheckboxField.
 *
 * @package Tests\WPGraphQL\GravityForms
 */

use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCaseInterface;
/**
 * Class -CheckboxFieldTest
 */
class CheckboxFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Tests the field properties and values.
	 */
	public function testField(): void {
		$this->runTestField();
	}
	/**
	 * Tests submitting the field values as a draft entry with submitGravityFormsForm.
	 */
	public function testSubmitDraft(): void {
		$this->runTestSubmitDraft();
	}
	/**
	 * Tests submitting the field values as an entry with submitGravityFormsForm.
	 */
	public function testSubmit(): void {
		$this->runTestSubmit();
	}
	/**
	 * Tests updating the field value with updateGravityFormsEntry.
	 */
	public function testUpdate(): void {
		$this->runTestUpdate();
	}
	/**
	 * Tests updating the draft field value with updateGravityFormsEntry.
	 */
	public function testUpdateDraft():void {
		$this->runTestUpdateDraft();
	}

	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		return $this->tester->getCheckboxFieldHelper();
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields() : array {
		return [ $this->factory->field->create( $this->property_helper->values ) ];
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function field_value() {
		return [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'text'    => $this->fields[0]['choices'][0]['text'],
				'value'   => $this->fields[0]['choices'][0]['value'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'text'   => $this->fields[0]['choices'][1]['text'],
				'value'   => null,

			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $this->fields[0]['choices'][2]['value'],
				'text'    => $this->fields[0]['choices'][2]['text'],
			],
		];
	}

	public function field_value_input()
	{
		$field_value = $this->field_value();
		return [
			[
				'inputId' => $field_value[0]['inputId'],
				'value'   => $field_value[0]['value'],
			],
			[
				'inputId' => $field_value[1]['inputId'],
				'value'   => $field_value[1]['value'],
			],
			[
				'inputId' => $field_value[2]['inputId'],
				'value'   => $field_value[2]['value'],
			],
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => null,
				'text'    => $this->fields[0]['choices'][0]['text'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'text'    => $this->fields[0]['choices'][1]['text'],
				'value'   => $this->fields[0]['choices'][1]['value'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'text'    => $this->fields[0]['choices'][2]['text'],
				'value'   => $this->fields[0]['choices'][2]['value'],
			],
		];
	}

	public function updated_field_value_input()
	{
		$field_value = $this->updated_field_value();
		return [
			[
				'inputId' => $field_value[0]['inputId'],
				'value'   => $field_value[0]['value'],
			],
			[
				'inputId' => $field_value[1]['inputId'],
				'value'   => $field_value[1]['value'],
			],
			[
				'inputId' => $field_value[2]['inputId'],
				'value'   => $field_value[2]['value'],
			],
		];
	}


	/**
	 * Thehe value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			(string) $this->field_value[0]['inputId'] => $this->field_value[0]['value'],
			(string) $this->field_value[2]['inputId'] => $this->field_value[2]['value'],
		];
	}


	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query():string {
		return '
			query getFieldValue($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType ) {
					formFields {
						nodes {
							cssClass
							formId
							id
							layoutGridColumnSpan
							layoutSpacerGridColumnSpan
							type
							conditionalLogic {
								actionType
								logicType
								rules {
									fieldId
									operator
									value
								}
							}
							... on CheckboxField {
								adminLabel
								adminOnly
								allowsPrepopulate
								description
								descriptionPlacement
								enablePrice
								enableChoiceValue
								enableSelectAll
								errorMessage
								inputName
								isRequired
								label
								size
								type
								visibility
								checkboxValues {
									inputId
									value
									text
								}
								inputs {
									id
									label
									name
								}
								choices {
									isSelected
									text
									value
								}
							}
						}
						edges {
							fieldValue {
								... on CheckboxFieldValue {
									checkboxValues {
										inputId
										value
										text
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
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return '
			mutation ($formId: Int!, $fieldId: Int!, $value: [CheckboxInput]!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, checkboxValues: $value}}) {
					errors {
						id
						message
					}
					entryId
					resumeToken
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
											text
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
										text
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
	 * Returns the UpdateEntry mutation string.
	 */
	public function update_entry_mutation(): string {
		return '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: [CheckboxInput]! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
									checkboxValues {
											inputId
											value
											text
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
										text
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
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: [CheckboxInput]! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
											text
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
										text
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
	public function expected_field_response( array $form ): array {
		return [
			$this->expectedObject(
				'gravityFormsEntry',
				[
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								array_merge_recursive(
									$this->property_helper->getAllActualValues( $form['fields'][0] ),
									[ 'checkboxValues' => $this->field_value ],
								)
							),
							$this->expectedEdge( 'fieldValue', $this->get_expected_fields( $this->field_value ) ),
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
	public function expected_mutation_response( string $mutationName, $value ):array {
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
									$this->expectedEdge(
										'fieldValue',
										$this->get_expected_fields( $value ),
									),
									$this->expectedNode(
										'checkboxValues',
										$this->get_expected_fields( $value ),
									),
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
	public function check_saved_values( $actual_entry, $form ): void {
		$this->assertEquals( $this->field_value[0]['value'], $actual_entry[ $form['fields'][0]['inputs'][0]['id'] ] );
		$this->assertEquals( $this->field_value[1]['value'], $actual_entry[ $form['fields'][0]['inputs'][1]['id'] ] );
		$this->assertEquals( $this->field_value[2]['value'], $actual_entry[ $form['fields'][0]['inputs'][2]['id'] ] );
	}
}
