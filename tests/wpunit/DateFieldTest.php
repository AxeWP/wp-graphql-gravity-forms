<?php
/**
 * Test Date field type.
 */

use Tests\WPGraphQL\GravityForms\Factories;

/**
 * Class -DateFieldTest.
 */
class DateFieldTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	protected $factory;
	private $admin;
	private $fields = [];
	private $form_id;
	private $entry_id;
	private $draft_token;
	private $property_helper;
	private $value;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		$this->admin = $this->factory()->user->create_and_get(
			[
				'role' => 'administrator',
			]
		);
		$this->admin->add_cap( 'gravityforms_view_entries' );
		wp_set_current_user( $this->admin->ID );

		$this->factory         = new Factories\Factory();
		$this->property_helper = $this->tester->getDateFieldHelper();
		$this->value           = $this->property_helper->dummy->ymd();

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

		$this->draft_token = $this->factory->draft->create(
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
		\WPGraphQL::clear_schema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		wp_delete_user( $this->admin->id );
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->draft->delete( $this->draft_token );
		$this->factory->form->delete( $this->form_id );
		GFFormsModel::set_current_lead( null );
		\WPGraphQL::clear_schema();
		// Then...
		parent::tearDown();
	}

	/**
	 * Tests DateField properties and values.
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
							... on DateField {
								adminLabel
								adminOnly
								allowsPrepopulate
								calendarIconType
								calendarIconUrl
								dateFormat
								dateType
								defaultValue
								description
								descriptionPlacement
								errorMessage
								inputName
								isRequired
								label
								noDuplicates
								placeholder
								size
								subLabelPlacement
								value
								visibility
								inputs {
									customLabel
									defaultValue
									id
									label
									placeholder
								}
							}
						}
						edges {
							fieldValue {
								... on DateFieldValue {
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
			$this->draft_token = $this->factory->draft->create(
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
	 * Test submitting DateField asa draft entry with submitGravityFormsForm.
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
					'value'   => $this->value,
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
									'value' => $this->value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $this->value,
							],
						],
					],
				],
			],
		];
		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$this->factory->draft->delete( $resume_token );
	}

	/**
	 * Test submitting DateField with submitGravityFormsForm.
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
					'value'   => $this->value,
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
									'value' => $this->value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $this->value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $this->value, $actualEntry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting AddressField with updateGravityFormsEntry.
	 */
	public function testUpdateEntry() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->ymd();

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
									... on DateFieldValue {
										value
									}
								}
							}
							nodes {
								... on DateField {
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
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );
		$value        = $this->property_helper->dummy->ymd();

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
									... on DateFieldValue {
										value
									}
								}
							}
							nodes {
								... on DateField {
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

		$this->factory->draft->delete( $resume_token );
	}

	/**
	 * Test submitting DateField with updateDraftEntryDateFieldValue.
	 */
	public function testUpdateDraftEntryFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query = '
			mutation updateDraftEntryDateFieldValue( $fieldId: Int!, $resumeToken: String!, $value: String! ){
				updateDraftEntryDateFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on DateFieldValue {
										value
									}
								}
							}
							nodes {
								... on DateField {
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
					'value'       => $this->value,
				],
			]
		);

		$expected = [
			'updateDraftEntryDateFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'value' => $this->value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $this->value,
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
									... on DateFieldValue {
										value
									}
								}
							}
							nodes {
								... on DateField {
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
									'value' => $this->value,
								],
							],
						],
						'nodes' => [
							[
								'value' => $this->value,
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
									... on DateFieldValue {
										value
									}
								}
							}
							nodes {
								... on DateField {
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
