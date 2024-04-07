<?php
/**
 * Test CaptchaField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -CaptchaFieldTest
 */
class CaptchaFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	protected $test_draft = true;
	protected $captcha_field_helper;

	public function setUp(): void {
		// Before...
		parent::setUp();

		update_option( 'rg_gforms_captcha_private_key', GF_RECAPTCHA_PRIVATE_KEY );
		update_option( 'rg_gforms_captcha_public_key', GF_RECAPTCHA_PUBLIC_KEY );
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
		$this->captcha_field_helper = $this->tester->getPropertyHelper( 'CaptchaField' );
		return $this->tester->getPropertyHelper(
			'TextField',
			[
				'id'             => 2,
				'inputMaskValue' => '',
				'hasInputMask'   => false,
				'noDuplicates'   => false,
				'errorMessage'   => null,
			]
		);
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields(): array {
		return [
			$this->factory->field->create( $this->property_helper->values ),
			$this->factory->field->create( $this->captcha_field_helper->values ),
		];
	}

	/**
	 * Sets the value as expected by Gravity Forms.
	 */
	public function field_value() {
		return 'Some value';
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return 'Some updated value';
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function value() {
		return [ $this->fields[0]['id'] => $this->field_value ];
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function updated_value() {
		return [ $this->fields[0]['id'] => $this->updated_field_value ];
	}

		/**
		 * The entire GraphQL query with the form field values added.
		 */
	protected function entry_query(): string {
		return "
			query getFieldValue(\$id: ID!, \$idType: EntryIdTypeEnum) {
				gfEntry(id: \$id, idType: \$idType ) {
					formFields {
						nodes {
							{$this->field_query}
						}
					}
				}
			}
		";
	}

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '
			... on CaptchaField {
				displayOnly
				inputType
				layoutGridColumnSpan
				layoutSpacerGridColumnSpan
				pageNumber
				type
				visibility
				# type-specific
 				captchaBadgePosition
				captchaLanguage
				captchaTheme
				captchaType
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
				label
				labelPlacement
				simpleCaptchaBackgroundColor
				simpleCaptchaFontColor
				simpleCaptchaSize
			}
		';
	}

	/**
	 * Returns the SubmitForm graphQL query.
	 *
	 * The $value can be anything since we're using google's test keys.
	 */
	public function submit_form_mutation(): string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: String!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: [{id: $fieldId, value: $value}, {id:' . $this->fields[1]->id . ' value:"123456"}]}) {
					errors {
						id
						message
					}
					entry {
						formFields(where:{fieldTypes:TEXT}) {
							nodes {
								... on TextField {
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
					}
					entry {
						formFields {
							nodes {
								... on TextField {
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
					}
					entry: draftEntry {
						formFields (where:{fieldTypes:TEXT}){
							nodes {
								... on TextField {
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
		$expected = $this->getExpectedFormFieldValues( $form['fields'][1] );

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
								1
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
									$this->expectedField( 'nodes.0.value', $value ),
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
	public function check_saved_values( $actual_entry, $form ): void {}
}
