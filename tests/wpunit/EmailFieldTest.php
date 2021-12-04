<?php
/**
 * Test Email type.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -EmailFieldTest.
 */
class EmailFieldTest  extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper( 'EmailField' );
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
		return $this->property_helper->dummy->email();
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		$value = $this->field_value;
		return [
			'value'             => $value,
			'confirmationValue' => $value,
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return $this->property_helper->dummy->email();
	}

	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		$value = $this->updated_field_value();
		return [
			'value'             => $value,
			'confirmationValue' => $value,
		];
	}


	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [ 'input_' . $this->fields[0]['id'] => $this->field_value ];
	}

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on EmailField {
				adminLabel
				allowsPrepopulate
				conditionalLogic {
					actionType
					logicType
					rules {
						fieldId
						operator
						value
					}
				}
				cssClass
				description
				descriptionPlacement
				emailConfirmEnabled
				enableAutocomplete
				errorMessage
				inputName
				inputs {
					autocompleteAttribute
					customLabel
					defaultValue
					id
					label
					name
					placeholder
				}
				isRequired
				label
				noDuplicates
				pageNumber
				placeholder
				size
				subLabelPlacement
				value
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: Int!, $fieldId: Int!, $value: EmailInput!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, emailValues: $value}}) {
					errors {
						id
						message
					}
					entryId
					resumeToken
					entry {
						formFields {
							nodes {
								... on EmailField {
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
	public function update_entry_mutation() : string {
		return '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: EmailInput! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, emailValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on EmailField {
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
	public function update_draft_entry_mutation() : string {
		return '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: EmailInput! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, emailValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on EmailField {
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
	public function expected_field_response( array $form ) : array {
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
	public function check_saved_values( $actual_entry, $form ) : void {
		$this->assertEquals( $this->field_value, $actual_entry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
	}
}
