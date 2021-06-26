<?php
/**
 * Test ChainedSelectField.
 */

use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class -ChainedSelectFieldTest
 */
class ChainedSelectFieldTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	protected $factory;
	private $admin;
	private $fields = [];
	private $field_value;
	private $field_value_input;
	private $form_id;
	private $entry_id;
	private $draft_token;

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

		$this->factory     = new Factories\Factory();
		$this->fields[]    = $this->factory->field->create( $this->tester->getChainedSelectFieldDefaultArgs() );
		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->field_value = [ '2015', 'Acura', 'MDX' ];
		$this->field_value_input = [
			[
				'inputId' => (float) $this->fields[0]['inputs'][0]['id'],
				'value' => $this->field_value[0],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][1]['id'],
				'value' => $this->field_value[1],
			],
			[
				'inputId' => (float) $this->fields[0]['inputs'][2]['id'],
				'value' => $this->field_value[2],
			],
		];
		$this->entry_id    = $this->factory->entry->create(
			[
				'form_id'                           => $this->form_id,
				$this->fields[0]['inputs'][0]['id'] => $this->field_value[0],
				$this->fields[0]['inputs'][1]['id'] => $this->field_value[1],
				$this->fields[0]['inputs'][2]['id'] => $this->field_value[2],
			]
		);
		$this->draft_token = $this->factory->draft->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => [
					$this->fields[0]['inputs'][0]['id'] => $this->field_value[0],
					$this->fields[0]['inputs'][1]['id'] => $this->field_value[1],
					$this->fields[0]['inputs'][2]['id'] => $this->field_value[2],
				],
				'fieldValues' => [
					'input_' . $this->fields[0]['inputs'][0]['id'] => $this->field_value[0],
					'input_' . $this->fields[0]['inputs'][1]['id'] => $this->field_value[1],
					'input_' . $this->fields[0]['inputs'][2]['id'] => $this->field_value[2],
				],
			]
		);
		
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
		// Then...
		parent::tearDown();
	}

	/**
	 * Tests ChainedSelectField properties and values.
	 */
	public function testChainedSelectField() :void {
		$entry = $this->factory->entry->get_object_by_id( $this->entry_id );
		$form  = $this->factory->form->get_object_by_id( $this->form_id );

		$query = '
			query getFieldValue($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType ) {
					formFields {
						nodes {
							id
							... on ChainedSelectField {
								chainedSelectsAlignment
								chainedSelectsHideInactive
								errorMessage
								isRequired
								label
								type
								values
								inputs {
									id
									label
									name
								}
								choices {
									choices {
										choices {
											isSelected
											text
											value
										}
										isSelected
										text
										value
									}
									isSelected
									text
									value
								}
							}
						}
						edges {
							fieldValue {
								... on ChainedSelectFieldValue {
									values
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
						 [
							'id'                      => $form['fields'][0]->id,
							'type'                    => $form['fields'][0]->type,
							'chainedSelectsAlignment' => $this->tester->get_enum_for_value( Enum\ChainedSelectsAlignmentEnum::$type, $form['fields'][0]->chainedSelectsAlignment ),
							'chainedSelectsHideInactive' => $form['fields'][0]->chainedSelectsHideInactive,
							'choices' => [
								[
									'text' => $form['fields'][0]->choices[0]['text'],
									'value' => $form['fields'][0]->choices[0]['value'],
									'isSelected' => $form['fields'][0]->choices[0]['isSelected'],
									'choices' => [
										[
											'text' => $form['fields'][0]->choices[0]['choices'][0]['text'],
											'value' => $form['fields'][0]->choices[0]['choices'][0]['value'],
											'isSelected' => $form['fields'][0]->choices[0]['choices'][0]['isSelected'],
											'choices' => [
												[
													'text' => $form['fields'][0]->choices[0]['choices'][0]['choices'][0]['text'],
													'value' => $form['fields'][0]->choices[0]['choices'][0]['choices'][0]['value'],
													'isSelected' => $form['fields'][0]->choices[0]['choices'][0]['choices'][0]['isSelected'],
												],
												[
													'text' => $form['fields'][0]->choices[0]['choices'][0]['choices'][1]['text'],
													'value' => $form['fields'][0]->choices[0]['choices'][0]['choices'][1]['value'],
													'isSelected' => $form['fields'][0]->choices[0]['choices'][0]['choices'][1]['isSelected'],
												]
											]
										],
										[
											'text' => $form['fields'][0]->choices[0]['choices'][1]['text'],
											'value' => $form['fields'][0]->choices[0]['choices'][1]['value'],
											'isSelected' => $form['fields'][0]->choices[0]['choices'][1]['isSelected'],
											'choices' => [
												[
													'text' => $form['fields'][0]->choices[0]['choices'][1]['choices'][0]['text'],
													'value' => $form['fields'][0]->choices[0]['choices'][1]['choices'][0]['value'],
													'isSelected' => $form['fields'][0]->choices[0]['choices'][1]['choices'][0]['isSelected'],
												],
												[
													'text' => $form['fields'][0]->choices[0]['choices'][1]['choices'][1]['text'],
													'value' => $form['fields'][0]->choices[0]['choices'][1]['choices'][1]['value'],
													'isSelected' => $form['fields'][0]->choices[0]['choices'][1]['choices'][1]['isSelected'],
												]
											]
										]
									]
								],
								[
									'text' => $form['fields'][0]->choices[1]['text'],
									'value' => $form['fields'][0]->choices[1]['value'],
									'isSelected' => $form['fields'][0]->choices[1]['isSelected'],
									'choices' => [
										[
											'text' => $form['fields'][0]->choices[1]['choices'][0]['text'],
											'value' => $form['fields'][0]->choices[1]['choices'][0]['value'],
											'isSelected' => $form['fields'][0]->choices[1]['choices'][0]['isSelected'],
											'choices' => [
												[
													'text' => $form['fields'][0]->choices[1]['choices'][0]['choices'][0]['text'],
													'value' => $form['fields'][0]->choices[1]['choices'][0]['choices'][0]['value'],
													'isSelected' => $form['fields'][0]->choices[1]['choices'][0]['choices'][0]['isSelected'],
												],
												[
													'text' => $form['fields'][0]->choices[1]['choices'][0]['choices'][1]['text'],
													'value' => $form['fields'][0]->choices[1]['choices'][0]['choices'][1]['value'],
													'isSelected' => $form['fields'][0]->choices[1]['choices'][0]['choices'][1]['isSelected'],
												]
											]
										],
										[
											'text' => $form['fields'][0]->choices[1]['choices'][1]['text'],
											'value' => $form['fields'][0]->choices[1]['choices'][1]['value'],
											'isSelected' => $form['fields'][0]->choices[1]['choices'][1]['isSelected'],
											'choices' => [
												[
													'text' => $form['fields'][0]->choices[1]['choices'][1]['choices'][0]['text'],
													'value' => $form['fields'][0]->choices[1]['choices'][1]['choices'][0]['value'],
													'isSelected' => $form['fields'][0]->choices[1]['choices'][1]['choices'][0]['isSelected'],
												],
												[
													'text' => $form['fields'][0]->choices[1]['choices'][1]['choices'][1]['text'],
													'value' => $form['fields'][0]->choices[1]['choices'][1]['choices'][1]['value'],
													'isSelected' => $form['fields'][0]->choices[1]['choices'][1]['choices'][1]['isSelected'],
												]
											]
										]
									]
								]
							],
							'errorMessage'            => $form['fields'][0]->errorMessage,
							'inputs'                  => [
								[
									'id'           => $form['fields'][0]->inputs[0]['id'],
									'label'        => $form['fields'][0]->inputs[0]['label'],
									'name'         => $form['fields'][0]->inputs[0]['name'],
								],
								[
									'id'           => $form['fields'][0]->inputs[1]['id'],
									'label'        => $form['fields'][0]->inputs[1]['label'],
									'name'         => $form['fields'][0]->inputs[1]['name'],
								],
								[
									'id'           => $form['fields'][0]->inputs[2]['id'],
									'label'        => $form['fields'][0]->inputs[2]['label'],
									'name'         => $form['fields'][0]->inputs[2]['name'],
								],
							],
							'isRequired'              => (bool) $form['fields'][0]->isRequired,
							'label'                   => $form['fields'][0]->label,
							'type'                   => $form['fields'][0]->type,
							'values'  => [
								$entry[ $form['fields'][0]->inputs[0]['id'] ],
								$entry[ $form['fields'][0]->inputs[1]['id'] ],
								$entry[ $form['fields'][0]->inputs[2]['id'] ],
							],
							
						],
					],
					'edges' => [
						[
							'fieldValue' => [
								'values' => [
									$entry[ $form['fields'][0]->inputs[0]['id'] ],
									$entry[ $form['fields'][0]->inputs[1]['id'] ],
									$entry[ $form['fields'][0]->inputs[2]['id'] ],
								],
							],
						],
					],
				],
			],
		];

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

		// Test Draft entry.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $this->draft_token,
					'idType' => 'ID',
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

		// Test Draft entry.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id' => $this->draft_token,
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}


	/**
	 * Test submitting ChainedSelectField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmitFormChainedSelectFieldValue_draft() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $this->field_value_input,
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );

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
									'values' => $this->field_value,
								]
							],
						],
						'nodes' => [
							[
								'values' => $this->field_value,
							],
						],
					],
				],
			],
		];
		$this->assertEquals( $expected, $actual['data'] );

		$this->factory->draft->delete( $resume_token );
	}

	/**
	 * Test submitting ChainedSelectField with submitGravityFormsForm.
	 */
	public function testSubmitGravityFormsFormChainedSelectFieldValue() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $this->field_value_input,
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
									'values' => $this->field_value,
								]
							],
						],
						'nodes' => [
							[
								'values' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'] );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $this->field_value[0], $actualEntry[ $form['fields'][0]['inputs'][0]['id'] ] );
		$this->assertEquals( $this->field_value[1], $actualEntry[ $form['fields'][0]['inputs'][1]['id'] ] );
		$this->assertEquals( $this->field_value[2], $actualEntry[ $form['fields'][0]['inputs'][2]['id'] ] );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting ChainedSelectField with updateDraftEntryChainedSelectFieldValue.
	 */
	public function testUpdateDraftEntryChainedSelectFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query = '
			mutation updateDraftEntryChainedSelectFieldValue( $fieldId: Int!, $resumeToken: String!, $value: [ChainedSelectInput]! ){
				updateDraftEntryChainedSelectFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on ChainedSelectFieldValue {
									values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
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
					'value'       => $this->field_value_input,
				],
			]
		);

		$expected = [
			'updateDraftEntryChainedSelectFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => [
									'values' => $this->field_value,
								]
							],
						],
						'nodes' => [
							[
								'values' => $this->field_value,
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
									... on ChainedSelectFieldValue {
									values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
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
									'values' => $this->field_value,
								],
							],
						],
						'nodes' => [
							[
								'values' => $this->field_value,
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
			mutation ($formId: Int!, $fieldId: Int!, $value: [ChainedSelectInput]!, $draft: Boolean) {
				submitGravityFormsForm(input: {formId: $formId, clientMutationId: "123abc", saveAsDraft: $draft, fieldValues: {id: $fieldId, chainedSelectValues: $value}}) {
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
									... on ChainedSelectFieldValue {
										values
									}
								}
							}
							nodes {
								... on ChainedSelectField {
									values
								}
							}
						}
					}
				}
			}
		';
	}
}
