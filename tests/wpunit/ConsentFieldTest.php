<?php
/**
 * Test ConsentField.
 *
 * @package Tests\WPGraphQL\GravityForms
 */

use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCaseInterface;

/**
 * Class -ConsentFieldTest.
 */
class ConsentFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getConsentFieldHelper();
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
		return $this->fields[0]->checkboxLabel;
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return $this->fields[0]->checkboxLabel;
	}

	/**
	 * Thehe value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			(string) $this->fields[0]->inputs[0]['id'] => true,
			(string) $this->fields[0]->inputs[1]['id'] => $this->fields[0]->checkboxLabel,
			(string) $this->fields[0]->inputs[2]['id'] => true,
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
							... on ConsentField {
								adminLabel
								adminOnly
								checkboxLabel
								description
								descriptionPlacement
								errorMessage
								formId
								inputName
								isRequired
								label
								value
								visibility
							}
						}
						edges {
							fieldValue {
								... on ConsentFieldValue {
									value
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
			mutation ($formId: Int!, $fieldId: Int!, $value: String!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, value: $value}}) {
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
									... on ConsentFieldValue {
										value
									}
								}
							}
							nodes {
								... on ConsentField {
									value
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
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: String! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ConsentFieldValue {
										value
									}
								}
							}
							nodes {
								... on ConsentField {
									value
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
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: String! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ConsentFieldValue {
										value
									}
								}
							}
							nodes {
								... on ConsentField {
									value
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
								'0',
								array_merge_recursive(
									$this->property_helper->getAllActualValues( $form['fields'][0] ),
									[ 'value' => $this->field_value ],
								)
							),
							$this->expectedEdge(
								'fieldValue',
								$this->expectedField( 'value', $this->field_value ),
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
										$this->expectedField( 'value', $value ),
									),
									$this->expectedNode(
										'value',
										$this->expectedField( 'value', $value ),
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
		$this->assertEquals( $this->field_value_input, $actual_entry[ $form['fields'][0]->inputs[1]['id'] ], 'Submit mutation entry value not equal' );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->field_value_input;

		$query = '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: String! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ConsentFieldValue {
										value
									}
								}
							}
							nodes {
								... on ConsentField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'entryId' => $this->entry_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $value,
				],
			]
		);

		$expected = [
			'updateGravityFormsEntry' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual, 'Update mutation has errors' );
		$this->assertEquals( $expected, $actual['data'], 'Update mutation not equal' );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateDraftEntry() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );
		$value        = $this->field_value_input;

		$query = '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: String! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ConsentFieldValue {
										value
									}
								}
							}
							nodes {
								... on ConsentField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'resumeToken' => $resume_token,
					'fieldId'     => $form['fields'][0]->id,
					'value'       => $value,
				],
			]
		);

		$expected = [
			'updateGravityFormsDraftEntry' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual, 'Update mutation has errors' );
		$this->assertEquals( $expected, $actual['data'], 'Update mutation not equal' );

		$this->factory->draft_entry->delete( $resume_token );
	}
}
