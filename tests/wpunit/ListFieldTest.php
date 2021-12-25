<?php
/**
 * Test List type.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;


/**
 * Class -ListFieldTest.
 */
class ListFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper( 'ListField' );
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
			[ 'values' => [ 'first' ] ],
			[ 'values' => [ 'second' ] ],
		];
	}

	public function field_value_input() {
		$field_value = $this->field_value;
		return [
			[
				'rowValues' => $field_value[0]['values'],
			],
			[
				'rowValues' => $field_value[1]['values'],
			],
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
			return [
				[ 'values' => [ 'third' ] ],
				[ 'values' => [ 'fourth' ] ],
			];
	}

	public function updated_field_value_input() {
		$field_value = $this->updated_field_value;
		return [
			[
				'rowValues' => $field_value[0]['values'],
			],
			[
				'rowValues' => $field_value[1]['values'],
			],
		];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		$field_value = $this->field_value;
		return [ $this->fields[0]['id'] => serialize( [ 'first', 'second' ] ) ];
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
				canPrepopulate
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
				errorMessage
				hasColumns
				inputName
				isRequired
				label
				labelPlacement
				listValues {
					values
				}
				maxRows
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: [ListFieldInput]!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, listValues: $value}}) {
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
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: [ListFieldInput] ){
				updateGfEntry( input: { id: $entryId, fieldValues: {id: $fieldId, listValues: $value} }) {
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
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: [ListFieldInput]! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, fieldValues: {id: $fieldId, listValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
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
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expected_field_value( 'listValues', $this->field_value );

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
										'nodes',
										[
											$this->expected_field_value( 'listValues', $value ),
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
	public function check_saved_values( $actual_entry, $form ) : void {
		$actual_value = maybe_unserialize( $actual_entry[ $form['fields'][0]->id ], true );

		// Convert to GraphQL ListFieldInput
		$converted_value = array_map( fn( $value) => [ 'values' => [ $value ] ], $actual_value );
		$this->assertEquals( $this->field_value, $converted_value, 'Submit mutation entry value not equal' );
	}
}
