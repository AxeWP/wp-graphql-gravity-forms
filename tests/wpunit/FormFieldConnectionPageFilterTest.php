<?php
/**
 * Test GraphQL FormFieldConnection Queries.
 *
 * @package .
 */

use Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;

/**
 * Class - FormFieldConnectionPageFilterTest
 */
class FormFieldConnectionPageFilterTest extends GFGraphQLTestCase {
	private $form_id;
	private $fields;

	/**
	 * {@inheritDoc}
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->fields  = $this->generate_form_pages( 3 );
		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
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

	private function generate_form_pages( int $count = 1 ): array {
		$fields = [];
		$field_id = 1;

		for ( $i = 0; $i < $count; $i++ ) {
			// Fields should cycle between text, number, and radio fields.
			$property_helper_type = '';

			switch ( $i % 3 ) {
				case 1:
					$property_helper_type = 'NumberField';
					break;
				case 2:
					$property_helper_type = 'RadioField';
					break;
				case 0:
				default:
					$property_helper_type = 'TextField';
					break;
			}

			// Add the form field.
			$fields[] = $this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( $property_helper_type )->values,
					[
						'id'         => $field_id++,
						'pageNumber' => $i + 1,
					]
				)
			);

			// Add a page field if we are not on the last page.
			if ( $i < $count ) {
				$fields[] = $this->factory->field->create(
					array_merge(
						$this->tester->getPropertyHelper( 'PageField' )->values,
						[
							'id'         => $field_id++,
							'pageNumber' => $i + 2,
						]
					)
				);
			}
		}

		return $fields;
	}

	public function getQuery(): string {
		return '
			query FormFields($formId: ID!, $pageNumber: Int!) {
				gfForm(id: $formId, idType: DATABASE_ID) {
					formFields(
						where: {
							pageNumber: $pageNumber
						}
					) {
						pageInfo {
							hasNextPage
							hasPreviousPage
						}
						nodes {
							id
							databaseId
							type
							pageNumber
							... on PageField {
								nextButton{
									text
								}
								previousButton{
									text
								}
							}
						}
					}
				}
			}
		';
	}

	public function testFilterByPageNumber(): void {
		$query = $this->getQuery();

		$form     = GFAPI::get_form( $this->form_id );
		$wp_query = $form['fields'];

		/**
		 * Test with empty offset.
		 */
		$variables = [
			'formId' => $this->form_id,
			'pageNumber' => 0,
		];

		$expected = $wp_query;
		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfForm']['formFields']['nodes'] );

		// Set the variables to use in the GraphQL query.
		$variables['pageNumber'] = 1;

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 0, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPageFields( $expected, $actual );

		/**
		 * Test the next two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['pageNumber'] = 2;

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 2, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPageFields( $expected, $actual );


		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['pageNumber'] = 3;

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 4, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPageFields( $expected, $actual );
	}

	/**
	 * Common assertions for testing pagination.
	 *
	 * @param array $expected Expected results from GFAPI.
	 * @param array $actual Actual results from GraphQL.
	 */
	private function assertValidPageFields( array $expected, array $actual ): void {
		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );

		$this->assertArrayHasKey( 'data', $actual );
		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['nodes'] );

	
		$this->assertEquals( $expected[0]['id'], $actual['data']['gfForm']['formFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( $expected[0]['pageNumber'], $actual['data']['gfForm']['formFields']['nodes'][0]['pageNumber'] );
		$this->assertEquals( $expected[1]['id'], $actual['data']['gfForm']['formFields']['nodes'][1]['databaseId'] );
		$this->assertEquals( $expected[1]['pageNumber'], $actual['data']['gfForm']['formFields']['nodes'][1]['pageNumber'] );
		$this->assertEquals( GFHelpers::get_enum_for_value( FormFieldTypeEnum::$type, $expected[1]['type'] ), $actual['data']['gfForm']['formFields']['nodes'][1]['type'] );
		$this->assertEquals( $expected[1]['nextButton']['text'], $actual['data']['gfForm']['formFields']['nodes'][1]['nextButton']['text'] );
		$this->assertEquals( 
		$expected[1]['previousButton']['text'], $actual['data']['gfForm']['formFields']['nodes'][1]['previousButton']['text'] );
	}
}
