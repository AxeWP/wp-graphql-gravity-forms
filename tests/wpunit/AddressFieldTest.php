<?php
/**
 * Test AddressField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;

/**
 * Class -AddressFieldTest
 */
class AddressFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
		return $this->tester->getPropertyHelper( 'AddressField' );
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
			'street'  => '123 Main St.',
			'lineTwo' => 'Apt. 456',
			'city'    => 'Rochester Hills',
			'state'   => 'Michigan',
			'zip'     => '48306',
			'country' => 'US',
		];
	}


	/**
	 * Sets the value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			$this->fields[0]['inputs'][0]['id'] => $this->field_value['street'],
			$this->fields[0]['inputs'][1]['id'] => $this->field_value['lineTwo'],
			$this->fields[0]['inputs'][2]['id'] => $this->field_value['city'],
			$this->fields[0]['inputs'][3]['id'] => $this->field_value['state'],
			$this->fields[0]['inputs'][4]['id'] => $this->field_value['zip'],
			$this->fields[0]['inputs'][5]['id'] => 'United States',
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			'street'  => '234 Main St.',
			'lineTwo' => 'Apt. 567',
			'city'    => 'Other Hills',
			'state'   => 'Michigan',
			'zip'     => '48307',
			'country' => 'US',
		];
	}



	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on AddressField {
				addressType
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
				copyValuesOptionFieldId
				cssClass
				defaultCountry
				defaultProvince
				defaultState
				description
				descriptionPlacement
				hasAutocomplete
				shouldCopyValuesOption
				errorMessage
				inputs {
					customLabel
					defaultValue
					id
					isHidden
					key
					label
					name
					placeholder
					autocompleteAttribute
				}
				isRequired
				label
				labelPlacement
				subLabelPlacement
				type
				addressValues {
					street
					lineTwo
					city
					state
					zip
					country
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 *
	 * @return string
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: AddressFieldInput!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, addressValues: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on AddressField {
									addressValues {
										street
										lineTwo
										city
										state
										zip
										country
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
	 *
	 * @return string
	 */
	public function update_entry_mutation(): string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: AddressFieldInput! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, addressValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on AddressField {
									addressValues {
										street
										lineTwo
										city
										state
										zip
										country
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
	 *
	 * @return string
	 */
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: AddressFieldInput! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, addressValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on AddressField {
									addressValues {
										street
										lineTwo
										city
										state
										zip
										country
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
	 * @return array
	 */
	public function expected_field_response( array $form ) : array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expected_field_value( 'addressValues', $this->field_value );

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
											$this->expected_field_value( 'addressValues', $value ),
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
		$this->assertEquals( $this->field_value['street'], $actual_entry[ $form['fields'][0]['inputs'][0]['id'] ], 'Submit mutation entry value 1 not equal' );
		$this->assertEquals( $this->field_value['lineTwo'], $actual_entry[ $form['fields'][0]['inputs'][1]['id'] ], 'Submit mutation entry value 2 not equal' );
		$this->assertEquals( $this->field_value['city'], $actual_entry[ $form['fields'][0]['inputs'][2]['id'] ], 'Submit mutation entry value 3 not equal' );
		$this->assertEquals( $this->field_value['state'], $actual_entry[ $form['fields'][0]['inputs'][3]['id'] ], 'Submit mutation entry value 4 not equal' );
		$this->assertEquals( $this->field_value['zip'], $actual_entry[ $form['fields'][0]['inputs'][4]['id'] ], 'Submit mutation entry value 5 not equal' );
		$this->assertEquals( $this->field_value['country'], GFHelpers::get_enum_for_value( AddressFieldCountryEnum::$type, $actual_entry[ $form['fields'][0]['inputs'][5]['id'] ], 'Submit mutation entry value 6 not equal' ) );
	}
}
