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
				'form_id' => $this->form_id,
				'entry'   => [
					$this->fields[0]['inputs'][0]['id'] => '123 Main St.',
					$this->fields[0]['inputs'][1]['id'] => 'Apt. 456',
					$this->fields[0]['inputs'][2]['id'] => 'Rochester Hills',
					$this->fields[0]['inputs'][3]['id'] => 'Michigan',
					$this->fields[0]['inputs'][4]['id'] => '48306',
					$this->fields[0]['inputs'][5]['id'] => 'USA',
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
							enableCopyValuesOption
							errorMessage
							id
							inputs {
								customLabel
								defaultValue
								id
								isHidden
								label
								name
								placeholder
							}
							isRequired
							label
							labelPlacement
							size
							subLabelPlacement
							type
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
							'enableCopyValuesOption'  => (bool) $form['fields'][0]->enableCopyValuesOption,
							'errorMessage'            => $form['fields'][0]->errorMessage,
							'inputs'                  => [
								0 => [
									'customLabel'  => $form['fields'][0]->inputs[0]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[0]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[0]['id'],
									'isHidden'     => $form['fields'][0]->inputs[0]['isHidden'],
									'label'        => $form['fields'][0]->inputs[0]['label'],
									'name'         => $form['fields'][0]->inputs[0]['name'],
									'placeholder'  => $form['fields'][0]->inputs[0]['placeholder'],
								],
								1 => [
									'customLabel'  => $form['fields'][0]->inputs[1]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[1]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[1]['id'],
									'isHidden'     => $form['fields'][0]->inputs[1]['isHidden'],
									'label'        => $form['fields'][0]->inputs[1]['label'],
									'name'         => $form['fields'][0]->inputs[1]['name'],
									'placeholder'  => $form['fields'][0]->inputs[1]['placeholder'],
								],
								2 => [
									'customLabel'  => $form['fields'][0]->inputs[2]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[2]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[2]['id'],
									'isHidden'     => $form['fields'][0]->inputs[2]['isHidden'],
									'label'        => $form['fields'][0]->inputs[2]['label'],
									'name'         => $form['fields'][0]->inputs[2]['name'],
									'placeholder'  => $form['fields'][0]->inputs[2]['placeholder'],
								],
								3 => [
									'customLabel'  => $form['fields'][0]->inputs[3]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[3]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[3]['id'],
									'isHidden'     => $form['fields'][0]->inputs[3]['isHidden'],
									'label'        => $form['fields'][0]->inputs[3]['label'],
									'name'         => $form['fields'][0]->inputs[3]['name'],
									'placeholder'  => $form['fields'][0]->inputs[3]['placeholder'],
								],
								4 => [
									'customLabel'  => $form['fields'][0]->inputs[4]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[4]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[4]['id'],
									'isHidden'     => $form['fields'][0]->inputs[4]['isHidden'],
									'label'        => $form['fields'][0]->inputs[4]['label'],
									'name'         => $form['fields'][0]->inputs[4]['name'],
									'placeholder'  => $form['fields'][0]->inputs[4]['placeholder'],
								],
								5 => [
									'customLabel'  => $form['fields'][0]->inputs[5]['customLabel'],
									'defaultValue' => $form['fields'][0]->inputs[5]['defaultValue'],
									'id'           => $form['fields'][0]->inputs[5]['id'],
									'isHidden'     => $form['fields'][0]->inputs[5]['isHidden'],
									'label'        => $form['fields'][0]->inputs[5]['label'],
									'name'         => $form['fields'][0]->inputs[5]['name'],
									'placeholder'  => $form['fields'][0]->inputs[5]['placeholder'],
								],
							],
							'isRequired'              => $form['fields'][0]->isRequired,
							'label'                   => $form['fields'][0]->label,
							'labelPlacement'          => $this->tester->get_enum_for_value( Enum\LabelPlacementPropertyEnum::$type, $form['fields'][0]->labelPlacement ),
							'size'                    => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
							'subLabelPlacement'       => $form['fields'][0]->subLabelPlacement,
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
					'id' => $this->draft_token,
					'idType' => 'ID',
				],
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}
}
