<?php
/**
 * Test CaptchaField.
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class -CaptchaFieldTest
 */
class CaptchaFieldTest extends GFGraphQLTestCase {

	private $fields = [];
	private $form_id;
	private $entry_id;
	private $draft_token;
	private $value;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->property_helper   = $this->tester->getCaptchaFieldHelper();
		$this->text_field_helper = $this->tester->getTextFieldHelper( [ 'id' => 2 ] );

		$this->fields[] = $this->factory->field->create( $this->property_helper->values );
		$this->fields[] = $this->factory->field->create( $this->text_field_helper->values );

		$this->value = $this->property_helper->dummy->words( 1, 5 );

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->entry_id = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[1]['id'] => $this->value,
			]
		);

		$this->draft_token = $this->factory->draft_entry->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => [
					$this->fields[1]['id'] => $this->value,
				],
				'fieldValues' => [
					'input_' . $this->fields[1]['id'] => $this->value,
				],
			]
		);

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->draft_entry->delete( $this->draft_token );
		$this->factory->form->delete( $this->form_id );
		GFFormsModel::set_current_lead( null );
		// Then...
		parent::tearDown();
	}

	/**
	 * Tests CaptchaField properties and values.
	 */
	public function testField() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = '
			query getFormField($id: ID!, $idType: IdTypeEnum) {
				gravityFormsForm(id: $id, idType: $idType ) {
					formFields {
						nodes {
							... on CaptchaField {
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
								formId
								id
								layoutGridColumnSpan
								layoutSpacerGridColumnSpan
								type
								captchaLanguage
								captchaType
								captchaTheme
								description
								descriptionPlacement
								displayOnly
								errorMessage
								label
								simpleCaptchaBackgroundColor
								simpleCaptchaSize
								simpleCaptchaFontColor
								size
							}
						}
					}
				}
			}
		';

		$variables = [
			'id'     => $this->form_id,
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = [
			$this->expectedObject(
				'gravityFormsForm',
				[
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'0',
								$this->property_helper->getAllActualValues( $form['fields'][0] )
							),
						]
					),
				]
			),
		];

		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Test submitting CaptchaField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmit_draft() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->words( 1, 5 );

		$query = $this->get_submit_form_query();

		$variables = [
			'draft'   => true,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][1]->id,
			'value'   => $value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsForm', $value );

		$this->assertQuerySuccessful( $response, $expected );
		$resume_token = $response['data']['submitGravityFormsForm']['resumeToken'];

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting CaptchaField with submitGravityFormsForm.
	 */
	public function testSubmit() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->words( 1, 5 );

		$query     = $this->get_submit_form_query();
		$variables = [
			'draft'   => false,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][1]->id,
			'value'   => $value,
		];
		// Test entry.
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsForm', $value );

		$this->assertQuerySuccessful( $response, $expected );

		$entry_id = $response['data']['submitGravityFormsForm']['entryId'];

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting CaptchaField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->words( 1, 5 );

		$query = '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: String! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on TextFieldValue {
										value
									}
								}
							}
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

		$variables = [
			'entryId' => $this->entry_id,
			'fieldId' => $form['fields'][1]->id,
			'value'   => $value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateGravityFormsEntry', $value );

		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Test submitting CaptchaField with updateGravityFormsEntry.
	 */
	public function testUpdateDraftEntry() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );
		$value        = $this->property_helper->dummy->words( 1, 5 );

		$query = '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: String! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on TextFieldValue {
										value
									}
								}
							}
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

		$variables = [
			'resumeToken' => $resume_token,
			'fieldId'     => $form['fields'][1]->id,
			'value'       => $value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateGravityFormsDraftEntry', $value );

		$this->assertQuerySuccessful( $response, $expected );

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting CaptchaField with updateDraftEntryTextFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );
		$value        = $this->property_helper->dummy->words( 1, 5 );

		// Test draft entry.
		$query = '
			mutation updateDraftEntryTextFieldValue( $fieldId: Int!, $resumeToken: String!, $value: String! ){
				updateDraftEntryTextFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on TextFieldValue {
										value
									}
								}
							}
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

		$variables = [
			'fieldId'     => $form['fields'][1]->id,
			'resumeToken' => $resume_token,
			'value'       => $value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateDraftEntryTextFieldValue', $value );

		$this->assertQuerySuccessful( $response, $expected );

		// Test submitted query.
		$query = '
			mutation( $resumeToken: String!) {
				submitGravityFormsDraftEntry(input: {clientMutationId: "123abc", resumeToken: $resumeToken}) {
					errors {
						id
						message
					}
					entryId
					entry {
						formFields {
							edges {
								fieldValue {
									... on TextFieldValue {
										value
									}
								}
							}
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

		$variables = [
			'resumeToken' => $resume_token,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsDraftEntry', $value );

		$this->assertQuerySuccessful( $response, $expected );

		$entry_id = $response['data']['submitGravityFormsDraftEntry']['entryId'];

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Returns the SubmitForm graphQL query.
	 *
	 * @return string
	 */
	public function get_submit_form_query() : string {
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
							edges {
								fieldValue {
									... on TextFieldValue {
										value
									}
								}
							}
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

	public function get_expected_mutation_response( string $mutationName, $value ) : array {
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
									$this->expectedEdge(
										'1.fieldValue',
										$this->expectedField( 'value', $value ),
									),
									$this->expectedNode(
										'1',
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
}
