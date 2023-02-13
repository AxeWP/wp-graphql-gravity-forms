<?php
/**
 * Test GraphQL Form Connection Queries.
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - FormConnectionQueriesTest
 */
class FormConnectionQueriesTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_ids;
	private $text_field_helper;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );

		// Form.
		$this->form_ids = $this->factory->form->create_many(
			6,
			array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() )
		);

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->form->delete( $this->form_ids );

		// Then...
		parent::tearDown();
	}

	public function getQuery() {
		return '
			query Forms( $first: Int, $last:Int, $after:String, $before:String, $where:RootQueryToGfFormConnectionWhereArgs) {
				gfForms( first: $first, last: $last, after: $after, before: $before, where: $where ) {
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
						dateCreated
						title
					}
				}
			}
		';
	}

	public function testForwardPagination() {
		$query = $this->getQuery();

		$wp_query = GFAPI::get_forms( null, null, 'id', 'DESC' );

		codecept_debug( array_column( $wp_query, 'id' ) );

		/**
		 * Test the first two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables = [
			'first' => 2,
		];

		// Run the GraphQL Query.
		$expected = array_slice( $wp_query, 0, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

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
		$variables['after'] = $actual['data']['gfForms']['pageInfo']['endCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 2, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );

		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['after'] = $actual['data']['gfForms']['pageInfo']['endCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 4, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results are equal to `last:2`.
		 */
		$variables = [
			'last' => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected, $actual );
	}

	public function testBackwardPagination() {
		$query = $this->getQuery();

		$wp_query = GFAPI::get_forms( null, null, 'id', 'ASC' );

		codecept_debug( array_column( $wp_query, 'id' ) );

		/**
		 * Test the first two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables = [
			'last' => 2,
		];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 0, 2, false );
		$expected = array_reverse( $expected );

		codecept_debug( array_column( $expected, 'id' ) );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

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
		$variables['before'] = $actual['data']['gfForms']['pageInfo']['startCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 2, 2, false );
		$expected = array_reverse( $expected );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['before'] = $actual['data']['gfForms']['pageInfo']['startCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 4, 2, false );
		$expected = array_reverse( $expected );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfForms']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfForms']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results are equal to `last:2`.
		 */
		$variables = [
			'first' => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected['data']['gfForms']['nodes'], $actual['data']['gfForms']['nodes'] );
	}


	public function testFormIdsWhereArgs() {
		$form_id_two = $this->form_ids[2];
		$form_id_one = $this->form_ids[1];

		$query = $this->getQuery();

		$variables = [
			'where' => [
				'formIds' => [ $form_id_one, $form_id_two ],
			],
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 2, $actual['data']['gfForms']['nodes'] );
		$this->assertEquals( $form_id_one, $actual['data']['gfForms']['nodes'][0]['databaseId'] );
		$this->assertEquals( $form_id_two, $actual['data']['gfForms']['nodes'][1]['databaseId'] );
	}

	/**
	 * Test `gfForms` with query args.
	 */
	public function testStatusWhereArgs() {
		// Get form ids in DESC order.
		$form_ids = array_reverse( $this->form_ids );

		// Check `where.status` argument.

		// Deactivate.
		$this->factory->form->update_object( $form_ids[0], [ 'is_active' => 0 ] );
		$this->factory->form->update_object( $form_ids[1], [ 'is_active' => 0 ] );
		// Trash.
		$this->factory->form->update_object( $form_ids[4], [ 'is_trash' => 1 ] );
		$this->factory->form->update_object( $form_ids[5], [ 'is_trash' => 1 ] );
		// Trash & Deactivate.
		$this->factory->form->update_object(
			$form_ids[2],
			[
				'is_active' => 0,
				'is_trash'  => 1,
			]
		);
		$this->factory->form->update_object(
			$form_ids[3],
			[
				'is_active' => 0,
				'is_trash'  => 1,
			]
		);

		$query = '
			query {
				inactive: gfForms(where: {status: INACTIVE}) {
					nodes {
						databaseId
						isActive
						isTrash
					}
				}
				trashed: gfForms(where: {status: TRASHED}) {
					nodes {
						databaseId
						isActive
						isTrash
					}
				}
				inactive_trashed: gfForms(where: {status: INACTIVE_TRASHED}) {
					nodes {
						databaseId
						isActive
						isTrash
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Status query has errors.' );
		// Test inactive.
		$this->assertCount( 2, $response['data']['inactive']['nodes'] );
		$this->assertFalse( $response['data']['inactive']['nodes'][0]['isActive'] );
		$this->assertFalse( $response['data']['inactive']['nodes'][0]['isTrash'] );
		// Test trashed.
		$this->assertCount( 2, $response['data']['trashed']['nodes'] );
		$this->assertTrue( $response['data']['trashed']['nodes'][0]['isActive'] );
		$this->assertTrue( $response['data']['trashed']['nodes'][0]['isTrash'] );
		// Test inactive_trashed.
		$this->assertCount( 2, $response['data']['inactive_trashed']['nodes'] );
		$this->assertFalse( $response['data']['inactive_trashed']['nodes'][0]['isActive'] );
		$this->assertTrue( $response['data']['inactive_trashed']['nodes'][0]['isTrash'] );
	}

	public function testOrderbyWhereArgs() {
		$query = $this->getQuery();

		// test orderby id
		$variables = [
			'first' => 2,
			'where' => [
				'orderby' => [
					'column' => 'ID',
					'order'  => 'DESC',
				],
			],
		];

		$wp_query = \GFAPI::get_forms( null, null, 'id', 'DESC' );
		$expected = array_slice( $wp_query, 0, 2, false );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );

		// test orderby title
		$variables = [
			'first' => 2,
			'where' => [
				'orderby' => [
					'column' => 'TITLE',
					'order'  => 'ASC',
				],
			],
		];

		$wp_query = \GFAPI::get_forms( null, null, 'title', 'ASC' );
		$expected = array_slice( $wp_query, 0, 2, false );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
	}

	/**
	 * Common asserts for testing pagination.
	 *
	 * @param array $expected An array of the results from WordPress. When testing backwards pagination, the order of this array should be reversed.
	 * @param array $actual The GraphQL results.
	 */
	public function assertValidPagination( $expected, $actual ) {
		$this->assertIsValidQueryResponse( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );

		$this->assertEquals( 2, count( $actual['data']['gfForms']['edges'] ) );

		$first_form  = $expected[0]['id'];
		$second_form = $expected[1]['id'];

		$start_cursor = $this->toRelayId( 'arrayconnection', $first_form );
		$end_cursor   = $this->toRelayId( 'arrayconnection', $second_form );

		$this->assertEquals( $first_form, $actual['data']['gfForms']['edges'][0]['node']['databaseId'] );
		$this->assertEquals( $first_form, $actual['data']['gfForms']['nodes'][0]['databaseId'] );
		$this->assertEquals( $start_cursor, $actual['data']['gfForms']['edges'][0]['cursor'] );
		$this->assertEquals( $start_cursor, $actual['data']['gfForms']['pageInfo']['startCursor'] );

		$this->assertEquals( $second_form, $actual['data']['gfForms']['edges'][1]['node']['databaseId'] );
		$this->assertEquals( $second_form, $actual['data']['gfForms']['nodes'][1]['databaseId'] );
		$this->assertEquals( $end_cursor, $actual['data']['gfForms']['edges'][1]['cursor'] );
		$this->assertEquals( $end_cursor, $actual['data']['gfForms']['pageInfo']['endCursor'] );
	}
}
