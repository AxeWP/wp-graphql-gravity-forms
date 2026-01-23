<?php
/**
 * Test CreditCardField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
/**
 * Class -CreditCardFieldTest
 */
class CreditCardFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Set up.
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! defined( 'WPGRAPHQL_GF_EXPERIMENTAL_FIELDS' ) ) {
			define( 'WPGRAPHQL_GF_EXPERIMENTAL_FIELDS', true );
		}
	}

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
		return $this->tester->getPropertyHelper( 'CreditCardField' );
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
		return [
			'cardNumber'      => '4111111111111111',
			'expirationMonth' => '12',
			'expirationYear'  => '2025',
			'securityCode'    => '123',
			'cardholderName'  => 'John Doe',
			'cardType'        => 'visa',
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			'cardNumber'      => '5555555555554444',
			'expirationMonth' => '11',
			'expirationYear'  => '2026',
			'securityCode'    => '456',
			'cardholderName'  => 'Jane Smith',
			'cardType'        => 'mastercard',
		];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		$value = $this->field_value();
		return [
			$this->fields[0]['id'] . '.1' => $value['cardNumber'],
			$this->fields[0]['id'] . '.2_month' => $value['expirationMonth'],
			$this->fields[0]['id'] . '.2_year' => $value['expirationYear'],
			$this->fields[0]['id'] . '.3' => $value['securityCode'],
			$this->fields[0]['id'] . '.4' => $value['cardholderName'],
			$this->fields[0]['id'] . '.5' => $value['cardType'],
		];
	}

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '
			... on CreditCardField {
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
				inputName
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				supportedCreditCards
				subLabelPlacement
				creditCardValues {
					cardNumber
					expirationMonth
					expirationYear
					securityCode
					cardholderName
					cardType
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $cardNumber: String!, $expirationMonth: String!, $expirationYear: String!, $securityCode: String!, $cardholderName: String!, $cardType: String!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, creditCardValues: {cardNumber: $cardNumber, expirationMonth: $expirationMonth, expirationYear: $expirationYear, securityCode: $securityCode, cardholderName: $cardholderName, cardType: $cardType}}} ) {
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
								... on CreditCardField {
									creditCardValues {
										cardNumber
										expirationMonth
										expirationYear
										securityCode
										cardholderName
										cardType
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
	public function update_entry_mutation(): string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $cardNumber: String!, $expirationMonth: String!, $expirationYear: String!, $securityCode: String!, $cardholderName: String!, $cardType: String! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, creditCardValues: {cardNumber: $cardNumber, expirationMonth: $expirationMonth, expirationYear: $expirationYear, securityCode: $securityCode, cardholderName: $cardholderName, cardType: $cardType}}} ) {
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
								... on CreditCardField {
									creditCardValues {
										cardNumber
										expirationMonth
										expirationYear
										securityCode
										cardholderName
										cardType
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
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $cardNumber: String!, $expirationMonth: String!, $expirationYear: String!, $securityCode: String!, $cardholderName: String!, $cardType: String! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, creditCardValues: {cardNumber: $cardNumber, expirationMonth: $expirationMonth, expirationYear: $expirationYear, securityCode: $securityCode, cardholderName: $cardholderName, cardType: $cardType}}} ) {
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
								... on CreditCardField {
									creditCardValues {
										cardNumber
										expirationMonth
										expirationYear
										securityCode
										cardholderName
										cardType
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
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expectedObject(
			'creditCardValues',
			[
				$this->expected_field_value( 'cardNumber', $this->field_value()['cardNumber'] ),
				$this->expected_field_value( 'expirationMonth', $this->field_value()['expirationMonth'] ),
				$this->expected_field_value( 'expirationYear', $this->field_value()['expirationYear'] ),
				$this->expected_field_value( 'securityCode', $this->field_value()['securityCode'] ),
				$this->expected_field_value( 'cardholderName', $this->field_value()['cardholderName'] ),
				$this->expected_field_value( 'cardType', $this->field_value()['cardType'] ),
			]
		);

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
											$this->expectedObject(
												'creditCardValues',
												[
													$this->expected_field_value( 'cardNumber', $value['cardNumber'] ),
													$this->expected_field_value( 'expirationMonth', $value['expirationMonth'] ),
													$this->expected_field_value( 'expirationYear', $value['expirationYear'] ),
													$this->expected_field_value( 'securityCode', $value['securityCode'] ),
													$this->expected_field_value( 'cardholderName', $value['cardholderName'] ),
													$this->expected_field_value( 'cardType', $value['cardType'] ),
												]
											),
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
		$value = $this->field_value();
		$this->assertEquals( $value['cardNumber'], $actual_entry[ $form['fields'][0]->id . '.1' ], 'Submit mutation entry value not equal' );
		$this->assertEquals( $value['expirationMonth'], $actual_entry[ $form['fields'][0]->id . '.2_month' ], 'Submit mutation entry value not equal' );
		$this->assertEquals( $value['expirationYear'], $actual_entry[ $form['fields'][0]->id . '.2_year' ], 'Submit mutation entry value not equal' );
		$this->assertEquals( $value['securityCode'], $actual_entry[ $form['fields'][0]->id . '.3' ], 'Submit mutation entry value not equal' );
		$this->assertEquals( $value['cardholderName'], $actual_entry[ $form['fields'][0]->id . '.4' ], 'Submit mutation entry value not equal' );
		$this->assertEquals( $value['cardType'], $actual_entry[ $form['fields'][0]->id . '.5' ], 'Submit mutation entry value not equal' );
	}
}