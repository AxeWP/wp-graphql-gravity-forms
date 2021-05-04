<?php
/**
 * Test AddressField.
 */

use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class -AddressFieldTest
 */
class AddressFieldTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	protected $factory;
	private $admin;
	private $fields = [];
	private $field_value;
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
		$this->fields[]    = $this->factory->field->create( $this->tester->getAddressFieldDefaultArgs() );
		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id    = $this->factory->entry->create(
			[
				'form_id'                           => $this->form_id,
				$this->fields[0]['inputs'][0]['id'] => '123 Main St.',
				$this->fields[0]['inputs'][1]['id'] => 'Apt. 456',
				$this->fields[0]['inputs'][2]['id'] => 'Rochester Hills',
				$this->fields[0]['inputs'][3]['id'] => 'Michigan',
				$this->fields[0]['inputs'][4]['id'] => '48306',
				$this->fields[0]['inputs'][5]['id'] => 'USA',
			]
		);
		$this->draft_token = $this->factory->draft->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => [
					$this->fields[0]['inputs'][0]['id'] => '123 Main St.',
					$this->fields[0]['inputs'][1]['id'] => 'Apt. 456',
					$this->fields[0]['inputs'][2]['id'] => 'Rochester Hills',
					$this->fields[0]['inputs'][3]['id'] => 'Michigan',
					$this->fields[0]['inputs'][4]['id'] => '48306',
					$this->fields[0]['inputs'][5]['id'] => 'USA',
				],
				'fieldValues' => [
					'input_' . $this->fields[0]['inputs'][0]['id'] => '123 Main St.',
					'input_' . $this->fields[0]['inputs'][1]['id'] => 'Apt. 456',
					'input_' . $this->fields[0]['inputs'][2]['id'] => 'Rochester Hills',
					'input_' . $this->fields[0]['inputs'][3]['id'] => 'Michigan',
					'input_' . $this->fields[0]['inputs'][4]['id'] => '48306',
					'input_' . $this->fields[0]['inputs'][5]['id'] => 'USA',
				],
			]
		);
		$this->field_value = [
			'street'  => '123 Main St.',
			'lineTwo' => 'Apt. 456',
			'city'    => 'Rochester Hills',
			'state'   => 'Michigan',
			'zip'     => '48306',
			'country' => 'USA',
		];
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
	 * Tests AddressField properties and values.
	 */
	public function testAddressField() :void {
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
							... on AddressField {
								addressType
								adminLabel
								adminOnly
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
								id
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
							'conditionalLogic'        => null,
							'cssClass'                => $form['fields'][0]->cssClass,
							'formId'                  => $form['fields'][0]->formId,
							'id'                      => $form['fields'][0]->id,
							'type'                    => $form['fields'][0]->type,
							'addressType'             => $this->tester->get_enum_for_value( Enum\AddressTypeEnum::$type, $form['fields'][0]->addressType ),
							'adminLabel'              => $form['fields'][0]->adminLabel,
							'adminOnly'               => $form['fields'][0]->adminOnly,
							'copyValuesOptionDefault' => (bool) $form['fields'][0]->copyValuesOptionDefault,
							'copyValuesOptionField'   => $form['fields'][0]->copyValuesOptionField,
							'defaultCountry'          => $form['fields'][0]->defaultCountry,
							'defaultProvince'         => $form['fields'][0]->defaultProvince,
							'defaultState'            => $form['fields'][0]->defaultState,
							'description'             => $form['fields'][0]->description,
							'descriptionPlacement'    => $this->tester->get_enum_for_value( Enum\DescriptionPlacementPropertyEnum::$type, $form['fields'][0]->descriptionPlacement ),
							'enableAutocomplete'      => $form['fields'][0]->enableAutocomplete,
							'enableCopyValuesOption'  => (bool) $form['fields'][0]->enableCopyValuesOption,
							'errorMessage'            => $form['fields'][0]->errorMessage,
							'inputs'                  => [
								0 => [
									'customLabel'  => $form['fields'][0]->inputs[0]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[0]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[0]['id'],
									'isHidden'     => $form['fields'][0]->inputs[0]['isHidden'],
									'key'          => 'street',
									'label'        => $form['fields'][0]->inputs[0]['label'],
									'name'         => $form['fields'][0]->inputs[0]['name'],
									'placeholder'  => $form['fields'][0]->inputs[0]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[0]['autocompleteAttribute'],
								],
								1 => [
									'customLabel'  => $form['fields'][0]->inputs[1]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[1]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[1]['id'],
									'isHidden'     => $form['fields'][0]->inputs[1]['isHidden'],
									'key'          => 'lineTwo',
									'label'        => $form['fields'][0]->inputs[1]['label'],
									'name'         => $form['fields'][0]->inputs[1]['name'],
									'placeholder'  => $form['fields'][0]->inputs[1]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[1]['autocompleteAttribute'],
								],
								2 => [
									'customLabel'  => $form['fields'][0]->inputs[2]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[2]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[2]['id'],
									'isHidden'     => $form['fields'][0]->inputs[2]['isHidden'],
									'key'          => 'city',
									'label'        => $form['fields'][0]->inputs[2]['label'],
									'name'         => $form['fields'][0]->inputs[2]['name'],
									'placeholder'  => $form['fields'][0]->inputs[2]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[2]['autocompleteAttribute'],
								],
								3 => [
									'customLabel'  => $form['fields'][0]->inputs[3]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[3]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[3]['id'],
									'isHidden'     => $form['fields'][0]->inputs[3]['isHidden'],
									'key'          => 'state',
									'label'        => $form['fields'][0]->inputs[3]['label'],
									'name'         => $form['fields'][0]->inputs[3]['name'],
									'placeholder'  => $form['fields'][0]->inputs[3]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[3]['autocompleteAttribute'],
								],
								4 => [
									'customLabel'  => $form['fields'][0]->inputs[4]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[4]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[4]['id'],
									'isHidden'     => $form['fields'][0]->inputs[4]['isHidden'],
									'key'          => 'zip',
									'label'        => $form['fields'][0]->inputs[4]['label'],
									'name'         => $form['fields'][0]->inputs[4]['name'],
									'placeholder'  => $form['fields'][0]->inputs[4]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[4]['autocompleteAttribute'],
								],
								5 => [
									'customLabel'  => $form['fields'][0]->inputs[5]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[5]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[5]['id'],
									'isHidden'     => $form['fields'][0]->inputs[5]['isHidden'],
									'key'          => 'country',
									'label'        => $form['fields'][0]->inputs[5]['label'],
									'name'         => $form['fields'][0]->inputs[5]['name'],
									'placeholder'  => $form['fields'][0]->inputs[5]['placeholder'],
									'autocompleteAttribute' => $form['fields'][0]->inputs[5]['autocompleteAttribute'],
								],
							],
							'isRequired'              => $form['fields'][0]->isRequired,
							'label'                   => $form['fields'][0]->label,
							'labelPlacement'          => $this->tester->get_enum_for_value( Enum\LabelPlacementPropertyEnum::$type, $form['fields'][0]->labelPlacement ),
							'size'                    => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
							'subLabelPlacement'       => $form['fields'][0]->subLabelPlacement,
							'addressValues'           => [
								'street'  => $entry[ $form['fields'][0]->inputs[0]['id'] ],
								'lineTwo' => $entry[ $form['fields'][0]->inputs[1]['id'] ],
								'city'    => $entry[ $form['fields'][0]->inputs[2]['id'] ],
								'state'   => $entry[ $form['fields'][0]->inputs[3]['id'] ],
								'zip'     => $entry[ $form['fields'][0]->inputs[4]['id'] ],
								'country' => $entry[ $form['fields'][0]->inputs[5]['id'] ],
							],
							'visibility'              => $this->tester->get_enum_for_value( Enum\VisibilityPropertyEnum::$type, $form['fields'][0]->visibility ),
						],
					],
					'edges' => [
						0 => [
							'fieldValue' => [
								'street'  => $entry[ $form['fields'][0]->inputs[0]['id'] ],
								'lineTwo' => $entry[ $form['fields'][0]->inputs[1]['id'] ],
								'city'    => $entry[ $form['fields'][0]->inputs[2]['id'] ],
								'state'   => $entry[ $form['fields'][0]->inputs[3]['id'] ],
								'zip'     => $entry[ $form['fields'][0]->inputs[4]['id'] ],
								'country' => $entry[ $form['fields'][0]->inputs[5]['id'] ],
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
	 * Test submitting AddressField asa draft entry with submitGravityFormsForm.
	 */
	public function testSubmitFormAddressFieldValue_draft() : void {
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
								'fieldValue' => $this->field_value,
							],
						],
						'nodes' => [
							0 => [
								'addressValues' => $this->field_value,
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
	 * Test submitting AddressField with submitGravityFormsForm.
	 */
	public function testSubmitGravityFormsFormAddressFieldValue() : void {
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
							0 => [
								'fieldValue' => $this->field_value,
							],
						],
						'nodes' => [
							0 => [
								'addressValues' => $this->field_value,
							],
						],
					],
				],
			],
		];

		$this->assertEquals( $expected, $actual['data'] );

		$actualEntry = GFAPI::get_entry( $entry_id );

		$this->assertEquals( $this->field_value['street'], $actualEntry[ $form['fields'][0]['inputs'][0]['id'] ] );
		$this->assertEquals( $this->field_value['lineTwo'], $actualEntry[ $form['fields'][0]['inputs'][1]['id'] ] );
		$this->assertEquals( $this->field_value['city'], $actualEntry[ $form['fields'][0]['inputs'][2]['id'] ] );
		$this->assertEquals( $this->field_value['state'], $actualEntry[ $form['fields'][0]['inputs'][3]['id'] ] );
		$this->assertEquals( $this->field_value['zip'], $actualEntry[ $form['fields'][0]['inputs'][4]['id'] ] );
		$this->assertEquals( $this->field_value['country'], $actualEntry[ $form['fields'][0]['inputs'][5]['id'] ] );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Test submitting AddressField with updateDraftEntryAddressFieldValue.
	 */
	public function testUpdateDraftEntryAddressFieldValue() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft->create( [ 'form_id' => $this->form_id ] );

		// Test draft entry.
		$query = '
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
			'updateDraftEntryAddressFieldValue' => [
				'errors' => null,
				'entry'  => [
					'formFields' => [
						'edges' => [
							0 => [
								'fieldValue' => $this->field_value,
							],
						],
						'nodes' => [
							0 => [
								'addressValues' => $this->field_value,
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
								'fieldValue' => $this->field_value,
							],
						],
						'nodes' => [
							0 => [
								'addressValues' => $this->field_value,
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
}
