<?php
/**
 * Test TextArea type.
 */

use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class -TextAreaFieldTest.
 */
class TextAreaFieldTest extends \Codeception\TestCase\WPTestCase {

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
		$this->fields[]    = $this->factory->field->create( $this->tester->getTextAreaFieldDefaultArgs() );
		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id    = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[0]['id'] => 'This is a default Text Area Entry',
			]
		);
		$this->draft_token = $this->factory->draft->create(
			[
				'form_id' => $this->form_id,
				'entry'   => [
					$this->fields[0]['id'] => 'This is a default Text Area Entry',
					'fieldValues'          => [
						'input_' . $this->fields[0]['id'] => 'This is a default Text Area Entry',
					],
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
	 * Tests TextAreaField properties and values.
	 */
	public function testTextAreaField() :void {
		$entry = $this->factory->entry->get_object_by_id( $this->entry_id );
		$form  = $this->factory->form->get_object_by_id( $this->form_id );

		$query = '
			query getFieldValue($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType ) {
					formFields {
						nodes {
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
							type
							... on TextAreaField {
								adminLabel
								adminOnly
								allowsPrepopulate
								defaultValue
								description
								descriptionPlacement
								errorMessage
								inputName
								isRequired
								label
								maxLength
								noDuplicates
								placeholder
								size
								useRichTextEditor
								value
								visibility
							}
						}
						edges {
							fieldValue {
								... on TextAreaFieldValue {
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
						0 => [
							'conditionalLogic'     => null,
							'cssClass'             => $form['fields'][0]->cssClass,
							'formId'               => $form['fields'][0]->formId,
							'id'                   => $form['fields'][0]->id,
							'type'                 => $form['fields'][0]->type,
							'adminLabel'           => $form['fields'][0]->adminLabel,
							'adminOnly'            => (bool) $form['fields'][0]->adminOnly,
							'allowsPrepopulate'    => $form['fields'][0]->allowsPrepopulate,
							'defaultValue'         => $form['fields'][0]->defaultValue,
							'description'          => $form['fields'][0]->description,
							'descriptionPlacement' => $this->tester->get_enum_for_value( Enum\DescriptionPlacementPropertyEnum::$type, $form['fields'][0]->descriptionPlacement ),
							'errorMessage'         => $form['fields'][0]->errorMessage,
							'inputName'            => $form['fields'][0]->inputName,
							'isRequired'           => $form['fields'][0]->isRequired,
							'label'                => $form['fields'][0]->label,
							'maxLength'            => (int) $form['fields'][0]->maxLength,
							'noDuplicates'         => $form['fields'][0]->noDuplicates,
							'placeholder'          => $form['fields'][0]->placeholder,
							'size'                 => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
							'useRichTextEditor'    => $form['fields'][0]->useRichTextEditor,
							'value'                => $entry[ $form['fields'][0]->id ],
							'visibility'           => $this->tester->get_enum_for_value( Enum\VisibilityPropertyEnum::$type, $form['fields'][0]->visibility ),
						],
					],
					'edges' => [
						0 => [
							'fieldValue' => [
								'value' => $entry[ $form['fields'][0]->id ],
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
					'id' => $this->draft_token,
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}

	/**
	 * Test submitting TextAreaField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmitFormTextAreaFieldValue_draft() : void {
		$form        = $this->factory->form->get_object_by_id( $this->form_id );
		$field_value = 'value1';

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $field_value,
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
							0 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => [
								'value' => $field_value,
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
	 * Test submitting TextAreaField with submitGravityFormsForm.
	 */
	public function testSubmitGravityFormsFormTextAreaFieldValue() : void {
		$form        = $this->factory->form->get_object_by_id( $this->form_id );
		$field_value = 'value1';

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][0]->id,
					'value'   => $field_value,
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
							0 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => [
								'value' => $field_value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'] );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $field_value, $actualEntry[ $form['fields'][0]->id ] );
		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting TextAreaField with updateDraftEntryTextAreaFieldValue.
	 */
	public function testUpdateDraftEntryTextAreaFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );
		$field_value  = 'value1';

		// Test draft entry.
		$query = '
			mutation updateDraftEntryTextAreaFieldValue( $fieldId: Int!, $resumeToken: String!, $value: String! ){
				updateDraftEntryTextAreaFieldValue(input: {clientMutationId: "abc123", fieldId: $fieldId, resumeToken: $resumeToken, value: $value}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							edges {
								fieldValue {
									... on TextAreaFieldValue {
										value
									}
								}
							}
							nodes {
								... on TextAreaField {
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
					'value'       => $field_value,
				],
			]
		);

		$expected = [
			'updateDraftEntryTextAreaFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							0 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => [
								'value' => $field_value,
							],
						],
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

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
									... on TextAreaFieldValue {
										value
									}
								}
							}
							nodes {
								... on TextAreaField {
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
		$this->assertArrayNotHasKey( 'errors', $actual );

		$entry_id = $actual['data']['submitGravityFormsDraftEntry']['entryId'];

		$expected = [
			'submitGravityFormsDraftEntry' => [
				'errors'  => null,
				'entryId' => $entry_id,
				'entry'   => [
					'formFields' => [
						'edges' => [
							0 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => [
								'value' => $field_value,
							],
						],
					],
				],
			],
		];
		$this->assertEquals( $expected, $actual['data'] );

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
									... on TextAreaFieldValue {
										value
									}
								}
							}
							nodes {
								... on TextAreaField {
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
