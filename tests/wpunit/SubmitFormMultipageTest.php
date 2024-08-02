<?php
/**
 * Tests submitting a form with multiple pages.
 */

use Tests\WPGraphQL\GF\Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;

/**
 * Class - SubmitFormMultipageTest
 */
class SubmitFormMultipageTest extends GFGraphQLTestCase {
	private $form_id;
	private $fields;

	/**
	 * {@inheritDoc}
	 */
	public function setUp(): void {
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->fields  = $this->generate_form_fields();
		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs(),
			)
		);

		$this->clearSchema();
	}

	/**
	 * {@inheritDoc}
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	private function generate_form_fields(): array {
		// This will increment as we use them.
		$id          = 1;
		$page_number = 1;

		return [
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'NumberField' )->values,
					[
						'id'         => $id++,
						'pageNumber' => $page_number,
						'isRequired' => true,
					]
				)
			),
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PageField' )->values,
					[
						'id'               => $id++,
						'pageNumber'       => ++$page_number,
						'isRequired'       => false,
						'conditionalLogic' => [
							'actionType' => 'show',
							'logicType'  => 'all',
							'rules'      => [
								[
									'fieldId'  => 1,
									'operator' => 'is',
									'value'    => 2,
								],
							],
						],
					]
				)
			),
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'NumberField' )->values,
					[
						'id'         => $id++,
						'pageNumber' => $page_number,
						'isRequired' => true,
					]
				)
			),
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'PageField' )->values,
					[
						'id'         => $id++,
						'pageNumber' => ++$page_number,
					]
				)
			),
			$this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( 'NumberField' )->values,
					[
						'id'           => $id++,
						'pageNumber'   => $page_number,
						'isRequired'   => true,
						'defaultValue' => 'default value',
					]
				)
			),
		];
	}

	private function submit_mutation(): string {
		return '
			mutation SubmitMultipageForm( $input:SubmitGfFormInput! ) {
				submitGfForm( input: $input ) {
					confirmation {
						type
					}
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry {
						... on GfSubmittedEntry {
							databaseId
						}
						... on GfDraftEntry {
							resumeToken
						}
					}
					targetPageNumber
					targetPageFormFields {
						nodes {
							id
							databaseId
							type
						}
					}
				}
			}
		';
	}

	public function testSubmit(): void {
		$query = $this->submit_mutation();

		$variables = [
			'input' => [
				'id'          => $this->form_id,
				'saveAsDraft' => false,
			],
		];

		// Submit the first page with invalid value.
		$variables['input']['sourcePage']  = 1;
		$variables['input']['targetPage']  = 2;
		$variables['input']['fieldValues'] = [];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 1, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the invalid page' );

		$this->clear_form_field_state();

		// On a value that isnt 2, the 3rd page should be the target page.
		$variables['input']['fieldValues'] = [
			[
				'id'    => 1,
				'value' => '0',
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$expected_fields = array_slice( $this->fields, 4, 2, false );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 3, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the last page' );
		$this->assertCount( 1, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );

		// On a 2, the 2nd page should be the target page.
		$this->clear_form_field_state();

		$variables['input']['fieldValues'] = [
			[
				'id'    => 1,
				'value' => '2',
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$expected_fields = array_slice( $this->fields, 2, 2, false );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 2, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the 2nd page' );
		$this->assertCount( 2, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );
		$this->assertEquals( $expected_fields[1]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][1]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[1]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][1]['type'] );

		// Trying to proceed to the 3rd page, should return to the 2nd page.
		$this->clear_form_field_state();

		$variables['input']['sourcePage'] = 2;
		$variables['input']['targetPage'] = 3;

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 2, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the 2nd page' );
		$this->assertCount( 2, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );
		$this->assertEquals( $expected_fields[1]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][1]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[1]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][1]['type'] );

		// With the correct value, the 3rd page should be the target page.
		$this->clear_form_field_state();

		$variables['input']['fieldValues'] = array_merge(
			$variables['input']['fieldValues'],
			[
				[
					'id'    => 3,
					'value' => '2',
				],
			]
		);

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$expected_fields = array_slice( $this->fields, 4, 2, false );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 3, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the 3nd page' );
		$this->assertCount( 1, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );

		// Test a target after our last page.
		$this->clear_form_field_state();

		$variables['input']['targetPage'] = 4;

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 3, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the 3nd page' );
		$this->assertCount( 1, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );

		// Test with the required values.
		$this->clear_form_field_state();

		$variables['input']['fieldValues'] = array_merge(
			$variables['input']['fieldValues'],
			[
				[
					'id'    => 5,
					'value' => '1',
				],
			]
		);

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertNotEmpty( $actual['data']['submitGfForm']['entry']['databaseId'] );
		$this->assertNull( $actual['data']['submitGfForm']['targetPageNumber'] );
		$this->assertEmpty( $actual['data']['submitGfForm']['targetPageFormFields'] );

		// Test as Draft.
		$this->clear_form_field_state();

		$variables['input']['saveAsDraft'] = true;
		$variables['input']['fieldValues'] = [];
		$variables['input']['sourcePage']  = 1;
		$variables['input']['targetPage']  = 3;

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEmpty( $actual['data']['submitGfForm']['errors'] );
		$this->assertEquals( 3, $actual['data']['submitGfForm']['targetPageNumber'], 'The target page number should be the 3nd page' );
		$this->assertCount( 1, $actual['data']['submitGfForm']['targetPageFormFields']['nodes'] );
		$this->assertEquals( $expected_fields[0]['id'], $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected_fields[0]['type'] ), $actual['data']['submitGfForm']['targetPageFormFields']['nodes'][0]['type'] );
	}

	/**
	 * Clears the form field state.
	 */
	private function clear_form_field_state(): void {
		$form = GFAPI::get_form( $this->form_id );

		foreach ( $form['fields'] as $field ) {
			unset( $field->failed_validation );
			unset( $field->validation_message );
		}
	}
}
