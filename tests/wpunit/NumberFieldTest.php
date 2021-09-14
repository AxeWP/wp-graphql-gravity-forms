<?php
/**
 * Test Number type.
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class -NumberFieldTest.
 */
class NumberFieldTest extends GFGraphQLTestCase {
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

		$this->property_helper = $this->tester->getNumberFieldHelper();
		$this->value           = (string) $this->property_helper->dummy->number();

		$this->fields[] = $this->factory->field->create( $this->property_helper->values );

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->entry_id = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[0]['id'] => $this->value,
			]
		);

		$this->draft_token = $this->factory->draft_entry->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => [
					$this->fields[0]['id'] => $this->value,
					'fieldValues'          => [
						'input_' . $this->fields[0]['id'] => $this->value,
					],
				],
				'fieldValues' => [
					'input_' . $this->fields[0]['id'] => $this->value,
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
	 * Tests NumberField properties and values.
	 */
	public function testField() :void {
		$entry = $this->factory->entry->get_object_by_id( $this->entry_id );
		$form  = $this->factory->form->get_object_by_id( $this->form_id );

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
							... on NumberField {
								adminLabel
								adminOnly
								allowsPrepopulate
								autocompleteAttribute
								calculationFormula
								calculationRounding
								defaultValue
								description
								descriptionPlacement
								enableAutocomplete
								enableCalculation
								errorMessage
								inputName
								isRequired
								label
								noDuplicates
								numberFormat
								placeholder
								rangeMax
								rangeMin
								size
								value
								visibility
							}
						}
						edges {
							fieldValue {
								... on NumberFieldValue {
									value
								}
							}
						}
					}
				}
			}
		';
		// Test entry.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $this->entry_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsEntry' => [
				'formFields' => [
					'nodes' => [
						$this->property_helper->getAllActualValues( $form['fields'][0] )
						+ [
							'value' => $entry[ $form['fields'][0]->id ],
						],
					],
					'edges' => [
						[
							'fieldValue' => [
								'value' => $entry[ $form['fields'][0]->id ],
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual, 'Test entry has error.' );
		$this->assertEquals( $expected, $actual['data'], 'Test entry is not equal' );

		// Ensures draft token is set.
		if ( empty( $this->draft_token ) ) {
			$this->draft_token = $this->factory->draft_entry->create(
				[
					'form_id'     => $this->form_id,
					'entry'       => [
						$this->fields[0]['id'] => $this->value,
					],
					'fieldValues' => [
						'input_' . $this->fields[0]['id'] => $this->value,
					],
				]
			);
		}

		// Test Draft entry.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id' => $this->draft_token,
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual, 'Test draft entry has error.' );
		$this->assertEquals( $expected, $actual['data'], 'Test draft entry is not equal.' );
	}

	/**
	 * Test submitting NumberField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmit_draft() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = (string) $this->property_helper->dummy->number();

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $value,
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual, 'Submit mutation has errors' );

		$entry_id     = $actual['data']['submitGravityFormsForm']['entryId'];
		$resume_token = $actual['data']['submitGravityFormsForm']['resumeToken'];

		$expected = [
			'submitGravityFormsForm' => [
				'errors'      => null,
				'entryId'     => $entry_id,
				'resumeToken' => $resume_token,
				'entry'       => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];
		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting NumberField with submitGravityFormsForm.
	 */
	public function testSubmit() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = (string) $this->property_helper->dummy->number();

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $value,
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual, 'Submit mutation has errors' );

		$entry_id     = $actual['data']['submitGravityFormsForm']['entryId'];
		$resume_token = $actual['data']['submitGravityFormsForm']['resumeToken'];
		$expected     = [
			'submitGravityFormsForm' => [
				'errors'      => null,
				'entryId'     => $entry_id,
				'resumeToken' => $resume_token,
				'entry'       => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $value, $actualEntry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = (string) $this->property_helper->dummy->number();

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
									... on NumberFieldValue {
										value
									}
								}
							}
							nodes {
								... on NumberField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'entryId' => $this->entry_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $value,
				],
			]
		);

		$expected = [
			'updateGravityFormsEntry' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual, 'Update mutation has errors' );
		$this->assertEquals( $expected, $actual['data'], 'Update mutation not equal' );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateDraftEntry() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );
		$value        = (string) $this->property_helper->dummy->number();

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
									... on NumberFieldValue {
										value
									}
								}
							}
							nodes {
								... on NumberField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'resumeToken' => $resume_token,
					'fieldId'     => $form['fields'][0]->id,
					'value'       => $value,
				],
			]
		);

		$expected = [
			'updateGravityFormsDraftEntry' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual, 'Update mutation has errors' );
		$this->assertEquals( $expected, $actual['data'], 'Update mutation not equal' );

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Test submitting NumberField with updateDraftEntryNumberFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );
		$value        = (string) $this->property_helper->dummy->number();

		// Test draft entry.
		$query = '
			mutation updateDraftEntryNumberFieldValue( $fieldId: Int!, $resumeToken: String!, $value: String! ){
				updateDraftEntryNumberFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on NumberFieldValue {
										value
									}
								}
							}
							nodes {
								... on NumberField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'fieldId'     => $form['fields'][0]->id,
					'resumeToken' => $resume_token,
					'value'       => $value,
				],
			]
		);

		$expected = [
			'updateDraftEntryNumberFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual, 'Update mutation has errors' );
		$this->assertEquals( $expected, $actual['data'], 'Update mutation not equal' );

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
									... on NumberFieldValue {
										value
									}
								}
							}
							nodes {
								... on NumberField {
									value
								}
							}
						}
					}
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'resumeToken' => $resume_token,
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual, 'Submit mutation has errors' );

		$entry_id = $actual['data']['submitGravityFormsDraftEntry']['entryId'];

		$expected = [
			'submitGravityFormsDraftEntry' => [
				'errors'  => null,
				'entryId' => $entry_id,
				'entry'   => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];
		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equals' );

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
									... on NumberFieldValue {
										value
									}
								}
							}
							nodes {
								... on NumberField {
									value
								}
							}
						}
					}
				}
			}
		';
	}
}