<?php
/**
 * Test CaptchaField.
 */

use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class -CaptchaFieldTest
 */
class CaptchaFieldTest extends \Codeception\TestCase\WPTestCase {
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
		$this->fields[]    = $this->factory->field->create( $this->tester->getCaptchaFieldDefaultArgs() );
		$this->fields[]    = $this->factory->field->create( $this->tester->getTextFieldDefaultArgs( [ 'id' => 2 ] ) );
		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id    = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[1]['id'] => 'This is a default Text Entry',
			]
		);
		$this->draft_token = $this->factory->draft->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => [
					$this->fields[1]['id'] => 'This is a default Text Entry',
				],
				'fieldValues' => [
					'input_' . $this->fields[1]['id'] => 'This is a default Text Entry',
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
	 * Tests CaptchaField properties and values.
	 */
	public function testCaptchaField() : void {
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
		// Test entry.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $this->form_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsForm' => [
				'formFields' => [
					'nodes' => [
						0 => [
							'conditionalLogic'             => null,
							'cssClass'                     => $form['fields'][0]->cssClass,
							'formId'                       => $form['fields'][0]->formId,
							'id'                           => $form['fields'][0]->id,
							'layoutGridColumnSpan'         => $form['fields'][0]['layoutGridColumnSpan'],
							'layoutSpacerGridColumnSpan'   => $form['fields'][0]['layoutSpacerGridColumnSpan'],
							'type'                         => $form['fields'][0]->type,
							'captchaLanguage'              => $form['fields'][0]->captchaLanguage,
							'captchaType'                  => $this->tester->get_enum_for_value( Enum\CaptchaTypeEnum::$type, $form['fields'][0]->captchaType ?: 'recaptcha' ),
							'captchaTheme'                 => $this->tester->get_enum_for_value( Enum\CaptchaThemeEnum::$type, $form['fields'][0]->captchaTheme ),
							'description'                  => $form['fields'][0]->description,
							'descriptionPlacement'         => $this->tester->get_enum_for_value( Enum\DescriptionPlacementPropertyEnum::$type, $form['fields'][0]->descriptionPlacement ),
							'errorMessage'                 => $form['fields'][0]->errorMessage,
							'label'                        => $form['fields'][0]->label,
							'simpleCaptchaBackgroundColor' => $form['fields'][0]->simpleCaptchaBackgroundColor,
							'simpleCaptchaSize'            => $form['fields'][0]->simpleCaptchaSize,
							'simpleCaptchaFontColor'       => $form['fields'][0]->simpleCaptchaFontColor,
							'size'                         => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
						],
						1 => new stdClass(),
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}

	/**
	 * Test submitting TextField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmitFormWithCaptchaField_draft() : void {
		$form        = $this->factory->form->get_object_by_id( $this->form_id );
		$field_value = 'value1';

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][1]->id,
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
								'fieldValue' => null,
							],
							1 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => new stdClass(),
							1 => [
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
	 * Test submitting TextField with submitGravityFormsForm.
	 */
	public function testSubmitFormWithCaptchaField() : void {
		$form        = $this->factory->form->get_object_by_id( $this->form_id );
		$field_value = 'value1';

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][1]->id,
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
								'fieldValue' => null,
							],
							1 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => new stdClass(),
							1 => [
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
	 * Test submitting TextField with updateDraftEntryTextFieldValue.
	 */
	public function testUpdateDraftEntryTextFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );
		$field_value  = 'value1';

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

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'fieldId'     => $form['fields'][1]->id,
					'resumeToken' => $resume_token,
					'value'       => $field_value,
				],
			]
		);

		$expected = [
			'updateDraftEntryTextFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							0 => [
								'fieldValue' => null,
							],
							1 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => new stdClass(),
							1 => [
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
								'fieldValue' => null,
							],
							1 => [
								'fieldValue' => [
									'value' => $field_value,
								],
							],
						],
						'nodes' => [
							0 => new stdClass(),
							1 => [
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
}
