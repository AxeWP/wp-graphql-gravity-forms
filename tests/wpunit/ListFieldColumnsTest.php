<?php
/**
 * Test List type with columns enabled
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;


/**
 * Class -ListFieldTest.
 */
class ListFieldColumnsTest  extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper(
			'ListField',
			[
				'enableColumns' => true,
				'choices'       => [
					[
						'text'  => 'firstCol',
						'value' => 'firstCol',
					],
					[
						'text'  => 'secondCol',
						'value' => 'secondCol',
					],
				],
			]
		);
	}

	public function generate_form_args() {
		$args = [
			'confirmations' => null,
			'notifications' => null,
			'button'        => [
				'conditionalLogic' => null,
			],
		];
		return $this->tester->getFormDefaultArgs( $args );
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
			[ 'first', 'second' ],
			[ 'third', 'fourth' ],
		];
	}

	public function field_value_input() {
		$field_value = $this->field_value();
		return [
			[
				'rowValues' => $field_value[0],
			],
			[
				'rowValues' => $field_value[1],
			],
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			[ 'rowValues' => [ 'fifth', 'sixth' ] ],
			[ 'rowValues' => [ 'seventh', 'eight' ] ],
		];
	}


	/**
	 * Thehe value as expected by Gravity Forms.
	 */
	public function value() {
		$field_value = $this->field_value();
		$return      = [
			'input_' . $this->fields[0]['id'] => serialize(
				[
					$field_value[0][0],
					$field_value[0][1],
					$field_value[1][0],
					$field_value[1][1],
				]
			),
		];

		return $return;
	}

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on ListField {
				addIconUrl
				adminLabel
				adminOnly
				allowsPrepopulate
				choices {
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
				deleteIconUrl
				description
				descriptionPlacement
				enableColumns
				errorMessage
				isRequired
				inputName
				label
				labelPlacement
				listValues {
					values
				}
				maxRows
				visibility
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: Int!, $fieldId: Int!, $value: [ListInput]!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, listValues: $value}}) {
					errors {
						id
						message
					}
					entryId
					resumeToken
					entry {
						formFields {
							nodes {
								... on ListField {
									listValues {
										values
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
	public function update_entry_mutation() : string {
		return '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: [ListInput] ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, listValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on ListField {
									listValues {
										values
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
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: [ListInput]! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, listValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on ListField {
									listValues {
										values
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
									[ 'listValues' => $this->field_value ],
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
		$actual_value = maybe_unserialize( $actual_entry[ $form['fields'][0]->id ], true );

		// Convert to GraphQL ListInput
		$converted_value = array_map( fn( $value) => [ 'rowValues' => array_values( $value ) ], $actual_value );

		$this->assertEquals( $this->field_value_input, $converted_value, 'Submit mutation entry value not equal' );
	}
}
