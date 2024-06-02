<?php
/**
 * Test EmailField type with confirmation.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -EmailFieldWithConfirmationTest.
 */
class EmailFieldWithConfirmationTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
	public function testSubmitForm(): void {
		$this->runtestSubmitForm();
	}

	/**
	 * Tests updating the field value with updateGfEntry.
	 */
	public function testUpdateEntry(): void {
		$this->runtestUpdateEntry();
	}

	/**
	 * Tests updating the draft field value with updateGfEntry.
	 */
	public function testUpdateDraft(): void {
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
	public function generate_fields(): array {
		$field = $this->factory->field->create(
			array_merge(
				$this->property_helper->values,
				[ 'emailConfirmEnabled' => true ],
			)
		);

		$field->inputs = [
			[
				'autocompleteAttribute' => 'email',
				'customLabel'           => 'enter email',
				'defaultValue'          => 'user@someemail.com',
				'id'                    => 1,
				'label'                 => 'Enter Email',
				'name'                  => null,
				'placeholder'           => 'place',
			],
			[
				'autocompleteAttribute' => 'email',
				'customLabel'           => 'confirm email',
				'defaultValue'          => 'user@someemail.com',
				'id'                    => '1.2',
				'label'                 => 'Confirm Email',
				'name'                  => null,
				'placeholder'           => 'holder',
			],
		];

		return [ $field ];
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
		$value = $this->updated_field_value;
		return [
			'value'             => $value,
			'confirmationValue' => $value,
		];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [ $this->fields[0]['id'] => $this->field_value ];
	}

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '
			... on EmailField {
				adminLabel
				canPrepopulate
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
				errorMessage
				hasAutocomplete
				hasEmailConfirmation
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
				labelPlacement
				placeholder
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				shouldAllowDuplicates
				size
				subLabelPlacement
				value
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return '
			mutation ( $formId: ID!, $fieldId: Int!, $value: EmailFieldInput!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, emailValues: $value}}) {
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
	public function update_entry_mutation(): string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: EmailFieldInput! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, emailValues: $value} }) {
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
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: EmailFieldInput! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, emailValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
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
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expected_field_value( 'value', $this->field_value );

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
								0
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
	 */
	public function expected_mutation_response( string $mutationName, $value ): array {
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
										'nodes',
										[
											$this->expected_field_value( 'value', $value ),
										]
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
		$this->assertEquals( $this->field_value, $actual_entry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
	}
}
