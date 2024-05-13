<?php
/**
 * Test ChainedSelectField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

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
		return $this->tester->getPropertyHelper( 'ChainedSelectField' );
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields(): array {
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
	 */
	public function field_query(): string {
		return '
			... on ChainedSelectField {
				adminLabel
				canPrepopulate
				chainedSelectsAlignment
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
				descriptionPlacement
				description
				errorMessage
				hasChoiceValue
				inputName
				inputs {
					label
					id
					name
				}
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				shouldAllowDuplicates
				shouldHideInactiveChoices
				subLabelPlacement
				values
				visibility
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return 'mutation ($formId: ID!, $fieldId: Int!, $value: [ChainedSelectFieldInput]!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, chainedSelectValues: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on ChainedSelectField {
									values
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
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: [ChainedSelectFieldInput]! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, chainedSelectValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
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
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: [ChainedSelectFieldInput]! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, chainedSelectValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
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
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expectedField( 'values', $this->field_value() );

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
											$this->expectedField( 'values', $value ),
										],
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
}
