<?php
/**
 * Test QuizField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
/**
 * Class -QuizFieldRadioTest
 */
class QuizRadioFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper( 'QuizField' );
	}

	/**
	 * Generates the form fields from factory. Must be wrapped in an array.
	 */
	public function generate_fields(): array {
		return [
			$this->factory->field->create(
				array_merge(
					$this->property_helper->values,
					[ 'inputType' => 'radio' ],
					[ 'gquizFieldType' => 'radio' ]
				)
			),
		];
	}

	public function generate_form_args() {
		return array_merge(
			parent::generate_form_args(),
			[
				'gravityformsquiz' => [
					'shuffleFields'                       => false,
					'instantFeedback'                     => true,
					'grading'                             => 'letter',
					'grades'                              => [
						[
							'text'  => 'B',
							'value' => 2,
						],
						[
							'text'  => 'A',
							'value' => 4,
						],
					],
					'passPercent'                         => 50,
					'passfailDisplayConfirmation'         => true,
					'passConfirmationMessage'             => 'some message',
					'passConfirmationDisableAutoformat'   => true,
					'failConfirmationMessage'             => 'some message',
					'failConfirmationDisableAutoformat'   => false,
					'letterDisplayConfirmation'           => true,
					'letterConfirmationMessage'           => 'someMessage',
					'letterConfirmationDisableAutoformat' => false,
					'maxScore'                            => 6,
				],
			]
		);
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function field_value() {
		return $this->fields[0]['choices'][0]['text'];
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return $this->fields[0]['choices'][0]['text'];
	}

	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		return $this->fields[0]['choices'][2]['text'];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return $this->fields[0]['choices'][2]['text'];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [ (string) $this->fields[0]['id'] => $this->field_value_input ];
	}

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '... on QuizField {
				adminLabel
				answerExplanation
				canPrepopulate
				choices {
					isCorrect
					isOtherChoice
					isSelected
					text
					value
					weight
				}
				conditionalLogic{
					actionType
					logicType
					rules{
						fieldId
						operator
						value
					}
				}
				cssClass
				description
				descriptionPlacement
				errorMessage
				hasChoiceValue
				hasWeightedScore
				inputName
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				shouldRandomizeQuizChoices
				shouldShowAnswerExplanation
				... on QuizRadioField {
					hasOtherChoice
					shouldAllowDuplicates
					value
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: String!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, value: $value}}) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry {
						formFields {
							nodes {
								... on QuizRadioField {
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
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: String! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry {
						formFields {
							nodes {
								... on QuizRadioField {
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
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: String! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on QuizRadioField {
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
		$this->assertEquals( $this->field_value_input, $actual_entry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
	}
}
