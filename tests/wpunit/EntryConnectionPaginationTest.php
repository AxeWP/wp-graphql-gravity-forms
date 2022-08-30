<?php
/**
 * Test GraphQL Entry Queries.
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - EntryQueriesTest
 */
class EntryConnectionPaginationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $entry_ids;
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

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->entry_ids = $this->factory->entry->create_many(
			6,
			[
				'form_id'              => $this->form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->entry->delete( $this->entry_ids );
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	public function getQuery() {
		return '
			query Entries( $first: Int, $last:Int, $after:String, $before:String, $where:RootQueryToGfEntryConnectionWhereArgs) {
				gfEntries( first: $first, last: $last, after: $after, before: $before, where: $where ) {
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
							... on GfSubmittedEntry {
								databaseId
							}
							... on GfDraftEntry {
								resumeToken
							}
						}
					}
					nodes {
						id
						... on GfSubmittedEntry {
							databaseId
						}
						... on GfDraftEntry {
							resumeToken
						}
					}
				}
			}
		';
	}

	public function testForwardPagination() {
		wp_set_current_user( $this->admin->ID );

		$query = $this->getQuery();

		$wp_query = GFAPI::get_entries( 0 );

		codecept_debug( $wp_query );

		/**
		 * Test the first two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables = [
			'first' => 2,
		];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 0, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

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
		$variables['after'] = $actual['data']['gfEntries']['pageInfo']['endCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 2, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );

		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['after'] = $actual['data']['gfEntries']['pageInfo']['endCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 4, 2, false );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results are equal to `last:2`.
		 */
		$variables = [
			'last' => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected['data']['gfEntries']['nodes'], $actual['data']['gfEntries']['nodes'] );
	}

	public function testBackwardPagination() {
		wp_set_current_user( $this->admin->ID );

		$query = $this->getQuery();

		$wp_query = GFAPI::get_entries( 0, [], [ 'direction' => 'ASC' ] );

		codecept_debug( $wp_query );

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
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( false, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

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
		$variables['before'] = $actual['data']['gfEntries']['pageInfo']['startCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 2, 2, false );
		$expected = array_reverse( $expected );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results.
		 */

		// Set the variables to use in the GraphQL query.
		$variables['before'] = $actual['data']['gfEntries']['pageInfo']['startCursor'];

		// Run the GraphQL Query
		$expected = array_slice( $wp_query, 4, 2, false );
		$expected = array_reverse( $expected );
		$actual   = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertValidPagination( $expected, $actual );
		$this->assertEquals( false, $actual['data']['gfEntries']['pageInfo']['hasPreviousPage'] );
		$this->assertEquals( true, $actual['data']['gfEntries']['pageInfo']['hasNextPage'] );

		/**
		 * Test the last two results are equal to `last:2`.
		 */
		$variables = [
			'first' => 2,
		];
		$expected  = $actual;

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertEqualSets( $expected['data']['gfEntries']['nodes'], $actual['data']['gfEntries']['nodes'] );
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

		$this->assertEquals( 2, count( $actual['data']['gfEntries']['edges'] ) );

		$first_entry  = $expected[0];
		$second_entry = $expected[1];

		if ( ! empty( $expected[0]['resumeToken'] ) ) {
			$expected_key = $actual_key = 'resumeToken';
		} else {
			$expected_key = 'id';
			$actual_key   = 'databaseId';
		}

		$this->assertEquals( $first_entry[ $expected_key ], $actual['data']['gfEntries']['edges'][0]['node'][ $actual_key ] );
		$this->assertEquals( $first_entry[ $expected_key ], $actual['data']['gfEntries']['nodes'][0][ $actual_key ] );
		$this->assertEquals( $first_entry[ $expected_key ], $this->get_id_from_cursor( $actual['data']['gfEntries']['edges'][0]['cursor'] ) );
		$this->assertEquals( $first_entry[ $expected_key ], $this->get_id_from_cursor( $actual['data']['gfEntries']['pageInfo']['startCursor'] ) );

		$this->assertEquals( $second_entry[ $expected_key ], $actual['data']['gfEntries']['edges'][1]['node'][ $actual_key ] );
		$this->assertEquals( $second_entry[ $expected_key ], $actual['data']['gfEntries']['nodes'][1][ $actual_key ] );
		$this->assertEquals( $second_entry[ $expected_key ], $this->get_id_from_cursor( $actual['data']['gfEntries']['edges'][1]['cursor'] ) );
		$this->assertEquals( $second_entry[ $expected_key ], $this->get_id_from_cursor( $actual['data']['gfEntries']['pageInfo']['endCursor'] ) );
	}

	private function get_id_from_cursor( string $cursor ) {
		$exploded = explode( ':', base64_decode( $cursor ) );

		return $exploded[2];
	}
}
