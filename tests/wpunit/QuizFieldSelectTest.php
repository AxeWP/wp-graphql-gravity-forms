<?php
/**
 * Test QuizField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
/**
 * Class -QuizFieldSelectTest
 */
class QuizFieldSelectTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper( 'QuizField' );
	}

	/**
	 * Generates the form fields from factory. Must be wrapped in an array.
	 */
	public function generate_fields() : array {
		return [
			$this->factory->field->create(
				array_merge(
					$this->property_helper->values,
					[ 'inputType' => 'select' ],
					[ 'gquizFieldType' => 'select' ]
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
		return [
			$this->fields[0]['choices'][0]['text'],
		];
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return $this->fields[0]['choices'][0]['value'];
	}
	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		return $this->fields[0]['choices'][2]['value'];
	}



	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			$this->fields[0]['choices'][2]['text'],
		];
	}


	/**
	 * Thehe value as expected by Gravity Forms.
	 */
	public function value() {
		return [ (string) $this->fields[0]['id'] => $this->field_value_input ];
	}


	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query():string {
		return '... on QuizField {
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
				gquizAnswerExplanation: answerExplanation
				autocompleteAttribute
				cssClass
				defaultValue
				description
				enableAutocomplete
				enableEnhancedUI
				gquizEnableRandomizeQuizChoices: enableRandomizeQuizChoices
				enableSelectAll
				gquizWeightedScoreEnabled: enableWeightedScore
				errorMessage
				inputName
				inputs {
					id
				}
				isRequired
				label
				labelPlacement
				placeholder
				gquizFieldType: quizFieldType
				gquizShowAnswerExplanation: showAnswerExplanation
				size
				type
				values
				choices {
					gquizIsCorrect: isCorrect
					text
					value
					gquizWeight: weight
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
							nodes {
								... on QuizField {
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
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: String! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on QuizField {
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
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: String! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on QuizField {
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
									$this->expectedNode(
										'values',
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
		$this->assertEquals( $this->field_value_input, $actual_entry[ $form['fields'][0]->id ] );
	}
}
