<?php
/**
 * Test TextField.
 */

use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class -TextFieldTest
 */
class TextFieldTest extends \Codeception\TestCase\WPTestCase {

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
		$this->fields[]    = $this->factory->field->create( $this->tester->getTextFieldDefaultArgs() );
		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id    = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				$this->fields[0]['id'] => 'This is a default Text Entry',
			]
		);
		$this->draft_token = $this->factory->draft->create(
			[
				'form_id' => $this->form_id,
				'entry'   => [
					$this->fields[0]['id'] => 'This is a default Text Entry',
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
	 * Tests TextField properties and values.
	 */
	public function testTextField() :void {
		$entry = $this->factory->entry->get_object_by_id( $this->entry_id );
		$form  = $this->factory->form->get_object_by_id( $this->form_id );


		$query = '
			query getFieldValue($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType ) {
					fields {
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
							... on TextField {
								adminLabel
								adminOnly
								allowsPrepopulate
								defaultValue
								description
								descriptionPlacement
								enablePasswordInput
								errorMessage
								inputName
								isRequired
								label
								maxLength
								noDuplicates
								placeholder
								size
								visibility
							}
						}
						edges {
							fieldValue {
								... on TextFieldValue {
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
				'fields' => [
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
							'enablePasswordInput'  => (bool) $form['fields'][0]->enablePasswordInput,
							'errorMessage'         => $form['fields'][0]->errorMessage,
							'inputName'            => $form['fields'][0]->inputName,
							'isRequired'           => $form['fields'][0]->isRequired,
							'label'                => $form['fields'][0]->label,
							'maxLength'            => (int) $form['fields'][0]->maxLength,
							'noDuplicates'         => $form['fields'][0]->noDuplicates,
							'placeholder'          => $form['fields'][0]->placeholder,
							'size'                 => $this->tester->get_enum_for_value( Enum\SizePropertyEnum::$type, $form['fields'][0]->size ),
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
}
