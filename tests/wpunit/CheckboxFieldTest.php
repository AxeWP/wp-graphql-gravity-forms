<?php
/**
 * Test CheckboxField.
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class -CheckboxFieldTest
 */
class CheckboxFieldTest extends GFGraphQLTestCase {
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

		$this->property_helper = $this->tester->getCheckboxFieldHelper();

		$this->fields[] = $this->factory->field->create( $this->property_helper->values );

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->field_value = [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => $this->fields[0]['choices'][0]['value'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value'   => null,
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $this->fields[0]['choices'][2]['value'],
			],
		];

		$this->value = [
			(string) $this->field_value[0]['inputId'] => $this->field_value[0]['value'],
			(string) $this->field_value[2]['inputId'] => $this->field_value[2]['value'],
		];

		$this->entry_id = $this->factory->entry->create(
			array_merge(
				[
					'form_id' => $this->form_id,
				],
				$this->value,
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
	 * Tests CheckboxField properties and values.
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
							... on CheckboxField {
								adminLabel
								adminOnly
								allowsPrepopulate
								description
								descriptionPlacement
								enablePrice
								enableChoiceValue
								enableSelectAll
								errorMessage
								inputName
								isRequired
								label
								size
								type
								visibility
								checkboxValues {
									inputId
									value
								}
								inputs {
									id
									label
									name
								}
								choices {
									isSelected
									text
									value
								}
							}
						}
						edges {
							fieldValue {
								... on CheckboxFieldValue {
									checkboxValues {
										inputId
										value
									}
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
						array_merge_recursive(
							$this->property_helper->getAllActualValues( $form['fields'][0] ),
							[ 'checkboxValues' => $this->field_value ],
						),
					],
					'edges' => [
						[
							'fieldValue' => [
								'checkboxValues' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual, 'Test entry has error.' );
		$this->assertEquals( $expected, $actual['data'], 'Test entry is not equal' );

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
	 * Test submitting CheckboxField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmit_draft() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $this->field_value,
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
									'checkboxValues' => $this->field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $this->field_value,
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
	 * Test submitting CheckboxField with submitGravityFormsForm.
	 */
	public function testSubmit() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $this->field_value,
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );

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
									'checkboxValues' => $this->field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $this->field_value[0]['value'], $actualEntry[ $form['fields'][0]['inputs'][0]['id'] ] );
		$this->assertEquals( $this->field_value[1]['value'], $actualEntry[ $form['fields'][0]['inputs'][1]['id'] ] );
		$this->assertEquals( $this->field_value[2]['value'], $actualEntry[ $form['fields'][0]['inputs'][2]['id'] ] );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting CheckboxField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$field_value = [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => null,
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value'   => $this->fields[0]['choices'][1]['value'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $this->fields[0]['choices'][2]['value'],
			],
		];

		$query = '
			mutation updateGravityFormsEntry( $entryId: Int!, $fieldId: Int!, $value: [CheckboxInput]! ){
				updateGravityFormsEntry(input: {clientMutationId: "abc123", entryId: $entryId, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
									checkboxValues {
											inputId
											value
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
									}
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
					'value'   => $field_value,
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
									'checkboxValues' => $field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $field_value,
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
	 * Test submitting CheckboxField with updateGravityFormsEntry.
	 */
	public function testUpdateDraftEntry() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		$field_value = [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value'   => null,
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value'   => $this->fields[0]['choices'][1]['value'],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value'   => $this->fields[0]['choices'][2]['value'],
			],
		];

		$query = '
			mutation updateGravityFormsDraftEntry( $resumeToken: String!, $fieldId: Int!, $value: [CheckboxInput]! ){
				updateGravityFormsDraftEntry(input: {clientMutationId: "abc123", resumeToken: $resumeToken, fieldValues: {id: $fieldId, checkboxValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
									}
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
					'value'       => $field_value,
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
									'checkboxValues' => $field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $field_value,
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
	 * Test submitting CheckboxField with updateDraftEntryCheckboxFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query = '
			mutation updateDraftEntryCheckboxFieldValue( $fieldId: Int!, $resumeToken: String!, $value: [CheckboxInput]! ){
				updateDraftEntryCheckboxFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
									}
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
					'value'       => $this->field_value,
				],
			]
		);

		$expected = [
			'updateDraftEntryCheckboxFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'checkboxValues' => $this->field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual, 'Update has errors.' );
		$this->assertEquals( $expected, $actual['data'], 'Update isnt equal.' );

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
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
									}
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
		$this->assertArrayNotHasKey( 'errors', $actual, 'submit has errors' );

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
									'checkboxValues' => $this->field_value,
								],
							],
						],
						'nodes' => [
							[
								'checkboxValues' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'], 'Submit isnt equals.' );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Returns the SubmitForm graphQL query.
	 *
	 * @return string
	 */
	public function get_submit_form_query() : string {
		return '
			mutation ($formId: Int!, $fieldId: Int!, $value: [CheckboxInput]!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, checkboxValues: $value}}) {
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
									... on CheckboxFieldValue {
										checkboxValues {
											inputId
											value
										}
									}
								}
							}
							nodes {
								... on CheckboxField {
									checkboxValues {
										inputId
										value
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
