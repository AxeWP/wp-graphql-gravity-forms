<?php
/**
 * Test GraphQL FormFieldConnection Queries.
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - FormFieldConnectionQueriesTest
 */
class FormFieldConnectionQueriesTest extends GFGraphQLTestCase {
	private $form_id;
	private $fields;

	/**
	 * run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->fields  = $this->generate_fields( 6 );
		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	private function generate_fields( int $count = 1 ): array {
		$fields = [];

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

			$fields[] = $this->factory->field->create(
				array_merge(
					$this->tester->getPropertyHelper( $property_helper_type )->values,
					[
						'id'         => $i + 1,
						'adminLabel' => lcfirst( $property_helper_type ),
					]
				)
			);
		}

		return $fields;
	}

	public function getQuery(): string {
		return '
			query FormFields($formId: ID!, $first: Int, $last: Int, $after: String, $before: String, $where: GfFormToFormFieldConnectionWhereArgs) {
				gfForm(id: $formId, idType: DATABASE_ID) {
					formFields(
						first: $first
						last: $last
						after: $after
						before: $before
						where: $where
					) {
						pageInfo {
							hasNextPage
							hasPreviousPage
							startCursor
							endCursor
						}
						edges {
							cursor
							node {
								id
								databaseId
							}
						}
						nodes {
							id
							databaseId
							type
							... on GfFieldWithAdminLabelSetting {
								adminLabel
							}
						}
					}
				}
			}
		';
	}

	public function testForwardPagination(): void {
		$query = $this->getQuery();

		$form     = GFAPI::get_form( $this->form_id );
		$wp_query = $form['fields'];

		/**
		 * Test the first two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables = [
			'formId' => $this->form_id,
			'first'  => 2,
		];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 0, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test with empty offset.
		 */
		$variables['after'] = '';
		$expected           = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected, $actual );

		/**
		 * Test the next two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['after'] = $actual['data']['gfForm']['formFields']['pageInfo']['endCursor'];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 2, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['after'] = $actual['data']['gfForm']['formFields']['pageInfo']['endCursor'];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 4, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results are equal to `last:2`.
		 */
		$variables = [
			'formId' => $this->form_id,
			'last'   => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected, $actual );
	}

	public function testBackwardPagination() {
		$query = $this->getQuery();

		$form     = GFAPI::get_form( $this->form_id );
		$wp_query = $form['fields'];
		$wp_query = array_reverse( $wp_query );

		/**
		 * Test the first two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables = [
			'formId' => $this->form_id,
			'last'   => 2,
		];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 0, 2, false );
		$expected = array_reverse( $expected );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test with empty offset.
		 */
		$variables['before'] = '';
		$expected            = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected, $actual );

		/**
		 * Test the next two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['before'] = $actual['data']['gfForm']['formFields']['pageInfo']['startCursor'];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 2, 2, false );
		$expected = array_reverse( $expected );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['before'] = $actual['data']['gfForm']['formFields']['pageInfo']['startCursor'];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 4, 2, false );
		$expected = array_reverse( $expected );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfForm']['formFields']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForm']['formFields']['pageInfo']['hasNextPage'] );

		/**
		 * Test the first two results are equal to `first:2`.
		 */
		$variables = [
			'formId' => $this->form_id,
			'first'  => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected, $actual );
	}

	public function testIdsWhereArgs() {
		$form_field_id_four = $this->fields[3]->id;
		$form_field_id_five = $this->fields[4]->id;

		$query = $this->getQuery();

		codecept_debug( 'form_field_id_four: ' . $form_field_id_four );
		codecept_debug( 'form_field_id_five: ' . $form_field_id_five );

		$variables = [
			'formId' => $this->form_id,
			'where'  => [
				'ids' => [ $form_field_id_four, $form_field_id_five ],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertEquals( $form_field_id_four, $actual['data']['gfForm']['formFields']['edges'][0]['node']['databaseId'] );
		$this->assertEquals( $form_field_id_five, $actual['data']['gfForm']['formFields']['edges'][1]['node']['databaseId'] );
	}

	public function testAdminLabelsWhereArgs() {
		$query = $this->getQuery();

		$variables = [
			'formId' => $this->form_id,
			'where'  => [
				'adminLabels' => [ 'numberField' ],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertEquals( 'numberField', $actual['data']['gfForm']['formFields']['nodes'][0]['adminLabel'] );

		// Test with multiple admin labels.
		$variables['where']['adminLabels'] = [ 'numberField', 'radioField' ];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 4, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertEquals( 'numberField', $actual['data']['gfForm']['formFields']['nodes'][0]['adminLabel'] );
		$this->assertEquals( 'radioField', $actual['data']['gfForm']['formFields']['nodes'][1]['adminLabel'] );
	}

	public function testFieldTypesWhereArgs() {
		$query = $this->getQuery();

		$variables = [
			'formId' => $this->form_id,
			'where'  => [
				'fieldTypes' => [ 'NUMBER' ],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertEquals( 'NUMBER', $actual['data']['gfForm']['formFields']['nodes'][0]['type'] );

		// Test with multiple admin labels.
		$variables['where']['fieldTypes'] = [ 'NUMBER', 'RADIO' ];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 4, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertEquals( 'NUMBER', $actual['data']['gfForm']['formFields']['nodes'][0]['type'] );
		$this->assertEquals( 'RADIO', $actual['data']['gfForm']['formFields']['nodes'][1]['type'] );
	}

	/**
	 * Common assertions for testing pagination.
	 *
	 * @param array $expected Expected results from GFAPI.
	 * @param array $actual Actual results from GraphQL.
	 */
	private function assertValidPagination( array $expected, array $actual ): void {
		$this->assertResponseIsValid( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );

		$this->assertArrayHasKey( 'data', $actual );

		$first_field_id  = $expected[0]['id'];
		$second_field_id = $expected[1]['id'];

		$start_cursor = $this->toRelayId( 'arrayconnection', $first_field_id );
		$end_cursor   = $this->toRelayId( 'arrayconnection', $second_field_id );

		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['edges'] );
		$this->assertCount( 2, $actual['data']['gfForm']['formFields']['nodes'] );

		$this->assertEquals( $first_field_id, $actual['data']['gfForm']['formFields']['edges'][0]['node']['databaseId'] );
		$this->assertEquals( $first_field_id, $actual['data']['gfForm']['formFields']['nodes'][0]['databaseId'] );
		$this->assertEquals( $start_cursor, $actual['data']['gfForm']['formFields']['edges'][0]['cursor'] );
		$this->assertEquals( $start_cursor, $actual['data']['gfForm']['formFields']['pageInfo']['startCursor'] );

		$this->assertEquals( $second_field_id, $actual['data']['gfForm']['formFields']['edges'][1]['node']['databaseId'] );
		$this->assertEquals( $second_field_id, $actual['data']['gfForm']['formFields']['nodes'][1]['databaseId'] );
		$this->assertEquals( $end_cursor, $actual['data']['gfForm']['formFields']['edges'][1]['cursor'] );
		$this->assertEquals( $end_cursor, $actual['data']['gfForm']['formFields']['pageInfo']['endCursor'] );
	}
}
