<?php
/**
 * Test CaptchaField.
 */

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

		$this->factory           = new Factories\Factory();
		$this->property_helper   = $this->tester->getCaptchaFieldHelper();
		$this->text_field_helper = $this->tester->getTextFieldHelper( [ 'id' => 2 ] );
		$this->value             = $this->property_helper->dummy->words( 1, 5 );

		// codecept_debug( $this->text_field_helper->values );
		$this->fields[] = $this->factory->field->create( $this->property_helper->values );
		$this->fields[] = $this->factory->field->create( $this->text_field_helper->values );

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

		$this->draft_token = $this->factory->draft->create(
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
						$this->property_helper->getAllActualValues( $form['fields'][0] ),
						// 0 => [
						// 'conditionalLogic'             => null,
						// 'cssClass'                     => $form['fields'][0]->cssClass,
						// 'formId'                       => $form['fields'][0]->formId,
						// 'id'                           => $form['fields'][0]->id,
						// 'layoutGridColumnSpan'         => $form['fields'][0]['layoutGridColumnSpan'],
						// 'layoutSpacerGridColumnSpan'   => $form['fields'][0]['layoutSpacerGridColumnSpan'],
						// 'type'                         => $form['fields'][0]->type,
						// 'captchaLanguage'              => $form['fields'][0]->captchaLanguage,
						// 'captchaType'                  => $this->tester->get_enum_for_value( Enum\CaptchaTypeEnum::$type, $form['fields'][0]->captchaType ?: 'recaptcha' ),
						// 'captchaTheme'                 => $this->tester->get_enum_for_value( Enum\CaptchaThemeEnum::$type, $form['fields'][0]->captchaTheme ),
						// 'description'                  => $form['fields'][0]->description,
						// 'descriptionPlacement'         => $this->tester->get_enum_for_value( Enum\DescriptionPlacementPropertyEnum::$type, $form['fields'][0]->descriptionPlacement ),
						// 'errorMessage'                 => $form['fields'][0]->errorMessage,
						// 'label'                        => $form['fields'][0]->label,
						// 'simpleCaptchaBackgroundColor' => $form['fields'][0]->simpleCaptchaBackgroundColor,
						// 'simpleCaptchaSize'            => $form['fields'][0]->simpleCaptchaSize,
						// 'simpleCaptchaFontColor'       => $form['fields'][0]->simpleCaptchaFontColor,
						// 'size'                         => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
						// ],
						new stdClass(),
					],
				],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $actual, 'Test form has error.' );
		$this->assertEquals( $expected, $actual['data'], 'Test form is not equal.' );
	}

	/**
	 * Test submitting TextField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmitFormWithCaptchaField_draft() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->words( 1, 5 );

		// codecept_debug( $form );

		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => true,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][1]->id,
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
								'fieldValue' => null,
							],
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							new stdClass(),
							[
								'value' => $value,
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
	 * Test submitting TextField with submitGravityFormsForm.
	 */
	public function testSubmitFormWithCaptchaField() : void {
		$form  = $this->factory->form->get_object_by_id( $this->form_id );
		$value = $this->property_helper->dummy->words( 1, 5 );

		// Test entry.
		$actual = graphql(
			[
				'query'     => $this->get_submit_form_query(),
				'variables' => [
					'draft'   => false,
					'formId'  => $this->form_id,
					'fieldId' => $form['fields'][1]->id,
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
								'fieldValue' => null,
							],
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							new stdClass(),
							[
								'value' => $value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'], 'Submit mutation not equal' );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting TextField with updateDraftEntryTextFieldValue.
	 */
	public function testUpdateDraftEntryTextFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );
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

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'fieldId'     => $form['fields'][1]->id,
					'resumeToken' => $resume_token,
					'value'       => $value,
				],
			]
		);

		$expected = [
			'updateDraftEntryTextFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							[
								'fieldValue' => null,
							],
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							new stdClass(),
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
								'fieldValue' => null,
							],
							[
								'fieldValue' => [
									'value' => $value,
								],
							],
						],
						'nodes' => [
							new stdClass(),
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
