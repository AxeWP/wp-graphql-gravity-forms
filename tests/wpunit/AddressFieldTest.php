<?php
/**
 * Test AddressField.
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class -AddressFieldTest
 */
class AddressFieldTest extends GFGraphQLTestCase {
	private $fields = [];
	private $field_value;
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

		$this->property_helper = $this->tester->getAddressFieldHelper();

		$this->fields[] = $this->factory->field->create( $this->property_helper->values );

		$this->field_value = [
			'street'  => '123 Main St.',
			'lineTwo' => 'Apt. 456',
			'city'    => 'Rochester Hills',
			'state'   => 'Michigan',
			'zip'     => '48306',
			'country' => 'USA',
		];
		$this->value       = [
			$this->fields[0]['inputs'][0]['id'] => $this->field_value['street'],
			$this->fields[0]['inputs'][1]['id'] => $this->field_value['lineTwo'],
			$this->fields[0]['inputs'][2]['id'] => $this->field_value['city'],
			$this->fields[0]['inputs'][3]['id'] => $this->field_value['state'],
			$this->fields[0]['inputs'][4]['id'] => $this->field_value['zip'],
			$this->fields[0]['inputs'][5]['id'] => $this->field_value['country'],
		];

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->entry_id = $this->factory->entry->create(
			array_merge(
				[ 'form_id' => $this->form_id ],
				$this->value
			)
		);

		$this->draft_token = $this->factory->draft_entry->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => array_merge(
					$this->value,
					[
						'fieldValues' => $this->property_helper->get_field_values( $this->value ),
					]
				),
				'fieldValues' => $this->property_helper->get_field_values( $this->value ),
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
	 * Tests AddressField properties and values.
	 */
	public function testField() :void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = '
			query getFieldValue($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType ) {
					formFields {
						nodes {
							cssClass
							formId
							id
							layoutGridColumnSpan
							layoutSpacerGridColumnSpan
							type
							conditionalLogic {
								actionType
								logicType
								rules {
									fieldId
									operator
									value
								}
							}
							... on AddressField {
								addressType
								adminLabel
								adminOnly
								allowsPrepopulate
								copyValuesOptionDefault
								copyValuesOptionField
								defaultCountry
								defaultProvince
								defaultState
								description
								descriptionPlacement
								enableAutocomplete
								enableCopyValuesOption
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
								size
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
								visibility
							}
						}
						edges {
							fieldValue {
								... on AddressFieldValue {
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
		';

		$variables = [
			'id'     => $this->entry_id,
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = [
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
									[ 'addressValues' => $this->field_value ],
								)
							),
							$this->expectedEdge( 'fieldValue', $this->get_expected_fields( $this->field_value ) ),
						]
					),
				]
			),
		];

		$this->assertQuerySuccessful( $response, $expected );

		// Test Draft entry.
		$variables = [
			'id'     => $this->draft_token,
			'idType' => 'ID',
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertQuerySuccessful( $response, $expected );
	}


	/**
	 * Test submitting AddressField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmit_draft() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->get_submit_form_query();

		$variables = [
			'draft'   => true,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $this->field_value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsForm', $this->field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$resume_token = $response['data']['submitGravityFormsForm']['resumeToken'];

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting AddressField with submitGravityFormsForm.
	 */
	public function testSubmit() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query     = $this->get_submit_form_query();
		$variables = [
			'draft'   => false,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $this->field_value,
		];
		// Test entry.
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsForm', $this->field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$entry_id = $response['data']['submitGravityFormsForm']['entryId'];

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $this->field_value['street'], $actualEntry[ $form['fields'][0]['inputs'][0]['id'] ], 'Submit mutation entry value 1 not equal' );
		$this->assertEquals( $this->field_value['lineTwo'], $actualEntry[ $form['fields'][0]['inputs'][1]['id'] ], 'Submit mutation entry value 2 not equal' );
		$this->assertEquals( $this->field_value['city'], $actualEntry[ $form['fields'][0]['inputs'][2]['id'] ], 'Submit mutation entry value 3 not equal' );
		$this->assertEquals( $this->field_value['state'], $actualEntry[ $form['fields'][0]['inputs'][3]['id'] ], 'Submit mutation entry value 4 not equal' );
		$this->assertEquals( $this->field_value['zip'], $actualEntry[ $form['fields'][0]['inputs'][4]['id'] ], 'Submit mutation entry value 5 not equal' );
		$this->assertEquals( $this->field_value['country'], $actualEntry[ $form['fields'][0]['inputs'][5]['id'] ], 'Submit mutation entry value 6 not equal' );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$field_value = [
			'street'  => '234 Main St.',
			'lineTwo' => 'Apt. 456',
			'city'    => 'Rochester Hills',
			'state'   => 'Michigan',
			'zip'     => '48306',
			'country' => 'USA',
		];

		$query     = '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: AddressInput! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, addressValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on AddressFieldValue {
									street
									lineTwo
									city
									state
									zip
									country
									}
								}
							}
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

		$variables = [
			'entryId' => $this->entry_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $field_value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateGravityFormsEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateDraftEntry() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		$field_value = [
			'street'  => '234 Main St.',
			'lineTwo' => 'Apt. 456',
			'city'    => 'Rochester Hills',
			'state'   => 'Michigan',
			'zip'     => '48306',
			'country' => 'USA',
		];

		$query     = '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: AddressInput! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, addressValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on AddressFieldValue {
									street
									lineTwo
									city
									state
									zip
									country
									}
								}
							}
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
		$variables = [
			'resumeToken' => $resume_token,
			'fieldId'     => $form['fields'][0]->id,
			'value'       => $field_value,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateGravityFormsDraftEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting AddressField with updateDraftEntryAddressFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query     = '
			mutation updateDraftEntryAddressFieldValue( $fieldId: Int!, $resumeToken: String!, $value: AddressInput! ){
				updateDraftEntryAddressFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on AddressFieldValue {
									street
									lineTwo
									city
									state
									zip
									country
									}
								}
							}
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

		$variables = [
			'fieldId'     => $form['fields'][0]->id,
			'resumeToken' => $resume_token,
			'value'       => $this->field_value,
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'updateDraftEntryAddressFieldValue', $this->field_value );

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
									... on AddressFieldValue {
									street
									lineTwo
									city
									state
									zip
									country
									}
								}
							}
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

		$variables = [
			'resumeToken' => $resume_token,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->get_expected_mutation_response( 'submitGravityFormsDraftEntry', $this->field_value );

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
			mutation ($formId: Int!, $fieldId: Int!, $value: AddressInput!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, addressValues: $value}}) {
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
									... on AddressFieldValue {
										street
										lineTwo
										city
										state
										zip
										country
									}
								}
							}
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
										'fieldValue',
										$this->get_expected_fields( $value ),
									),
									$this->expectedNode(
										'addressValues',
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
}
