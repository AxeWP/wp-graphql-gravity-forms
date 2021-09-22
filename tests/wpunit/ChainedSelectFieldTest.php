<?php
/**
 * Test ChainedSelectField.
 *
 * @package Tests\WPGraphQL\GravityForms
 */

use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GravityForms\TestCase\FormFieldTestCaseInterface;

/**
 * Class -ChainedSelectFieldTest
 */
class ChainedSelectFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getChainedSelectFieldHelper();
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
		return [ '2015', 'Acura', 'MDX' ];
	}
	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => $this->field_value[0],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value'   => $this->field_value[1],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $this->field_value[2],
			],
		];
	}


	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [ '2016', 'Acura', 'ILX' ];
	}

	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		$field_value = $this->updated_field_value();

		return [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => $field_value[0],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value'   => $field_value[1],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $field_value[2],
			],
		];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			$this->fields[0]['inputs'][0]['id'] => $this->field_value[0],
			$this->fields[0]['inputs'][1]['id'] => $this->field_value[1],
			$this->fields[0]['inputs'][2]['id'] => $this->field_value[2],
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
							... on ChainedSelectField {
								adminLabel
								adminOnly
								allowsPrepopulate
								chainedSelectsAlignment
								chainedSelectsHideInactive
								description
								descriptionPlacement
								errorMessage
								isRequired
								label
								noDuplicates
								size
								subLabelPlacement
								values
								visibility
								inputs {
									id
									label
									name
								}
								choices {
									choices {
										choices {
											isSelected
											text
											value
										}
										isSelected
										text
										value
									}
									isSelected
									text
									value
								}
							}
						}
						edges {
							fieldValue {
								... on ChainedSelectFieldValue {
									values
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
		return 'mutation ($formId: Int!, $fieldId: Int!, $value: [ChainedSelectInput]!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, chainedSelectValues: $value}}) {
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
									... on ChainedSelectFieldValue {
										values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
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
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: [ChainedSelectInput]! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, chainedSelectValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ChainedSelectFieldValue {
										values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
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
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: [ChainedSelectInput]! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, chainedSelectValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ChainedSelectFieldValue {
										values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
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
									[ 'values' => $this->field_value ],
								)
							),
							$this->expectedEdge(
								'fieldValue',
								[
									$this->expectedField( 'values', $this->field_value ),
								]
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
										$this->expectedField( 'values', $value ),
									),
									$this->expectedNode(
										'0',
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
		$this->assertEquals( $this->field_value[0], $actual_entry[ $form['fields'][0]['inputs'][0]['id'] ], 'Submit mutation entry value 1 not equal.' );
		$this->assertEquals( $this->field_value[1], $actual_entry[ $form['fields'][0]['inputs'][1]['id'] ], 'Submit mutation entry value 2 not equal.' );
		$this->assertEquals( $this->field_value[2], $actual_entry[ $form['fields'][0]['inputs'][2]['id'] ], 'Submit mutation entry value 3 not equal.' );
	}

	/**
	 * Test submitting ChainedSelectField with updateDraftEntryChainedSelectFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query = '
			mutation updateDraftEntryChainedSelectFieldValue( $fieldId: Int!, $resumeToken: String!, $value: [ChainedSelectInput]! ){
				updateDraftEntryChainedSelectFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ChainedSelectFieldValue {
									values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
								}
							}
						}
					}
				}
			}
		';

		$variables = [
			'fieldId'     => $form['fields'][0]->id,
			'resumeToken' => $resume_token,
			'value'       => $this->field_value_input,
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'updateDraftEntryAddressFieldValue', $this->field_value );

		$this->assertQuerySuccessful( $response, $expected );

		// Test submitted query.
		$query = '
			mutation( $resumeToken: String!) {
				submitGravityFormsDraftEntry(input: {clientMutationId: "123abc", resumeToken: $resumeToken}) {
					errors {
						id
						message
					}
					entryId
					entry {
						formFields {
							edges {
								fieldValue {
									... on ChainedSelectFieldValue {
									values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
								}
							}
						}
					}
				}
			}
		';

		$variables = [
			'resumeToken' => $resume_token,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'submitGravityFormsDraftEntry', $this->field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$entry_id = $response['data']['submitGravityFormsDraftEntry']['entryId'];

		$this->factory->entry->delete( $entry_id );
	}
}
