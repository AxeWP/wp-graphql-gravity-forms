<?php
/**
 * Test GraphQL Entry Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use Helper\GFHelpers\GFHelpers;
use WPGraphQL\GF\Data\Loader\EntriesLoader;

/**
 * Class - EntryQueriesTest
 */
class EntryQueriesTest extends GFGraphQLTestCase {
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

	/**
	 * Tests `gfEntry`.
	 */
	public function testEntryQuery() : void {
		wp_set_current_user( $this->admin->ID );

		$global_id = Relay::toGlobalId( EntriesLoader::$name, $this->entry_ids[0] );
		$entry     = $this->factory->entry->get_object_by_id( $this->entry_ids[0] );
		$form      = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->get_entry_query();

		$variables = [
			'id'     => $this->entry_ids[0],
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_field_response( $entry, $form );

		// Test with Database Id.
		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertQuerySuccessful( $response, $expected );

		// Test with Global Id.
		$variables = [
			'id'     => $global_id,
			'idType' => 'ID',
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Tests `gfEntry` with no setup variables.
	 */
	public function testEntryQuery_empty() {
		wp_set_current_user( $this->admin->ID );

		$entry_id  = $this->factory->entry->create(
			[ 'form_id' => $this->form_id ]
		);
		$global_id = Relay::toGlobalId( EntriesLoader::$name, $entry_id );
		$entry     = $this->factory->entry->get_object_by_id( $entry_id );
		$form      = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->get_entry_query();

		$variables = [
			'id'     => $entry_id,
			'idType' => 'DATABASE_ID',
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_field_response( $entry, $form );
		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertQuerySuccessful( $response, $expected );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Tests `gfEntry` with draft entry.
	 */
	public function testEntryQuery_draft() : void {
		wp_set_current_user( $this->admin->ID );

		$draft_tokens = $this->factory->draft_entry->create_many(
			2,
			[
				'form_id'    => $this->form_id,
				'created_by' => $this->admin->ID,
			]
		);

		$query = '
			query( $id: ID!, $idType: EntryIdTypeEnum) {
				gfEntry( id: $id, idType: $idType ) {
					... on GfDraftEntry {
						resumeToken
					}
				}
			}
		';

		$actual = $this->graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $draft_tokens[0],
					'idType' => 'RESUME_TOKEN',
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $draft_tokens[0], $actual['data']['gfEntry']['resumeToken'] );

		$this->factory->draft_entry->delete( $draft_tokens );
	}

	/**
	 * Tests `gfEntries`.
	 */
	public function testEntriesQuery() : void {
		wp_set_current_user( $this->admin->ID );

		$query = '
			query {
				gfEntries {
					nodes {
						... on GfSubmittedEntry {
							databaseId
						}
					}
				}
			}
		';

		$actual = $this->graphql( [ 'query' => $query ] );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
	}

	/**
	 * Tests `gfEntries` with query args .
	 */
	public function testEntriesQueryArgs() {
		wp_set_current_user( $this->admin->ID );

		$entry_ids = array_reverse( $this->entry_ids );

		$query = '
				query( $first: Int, $after: String, $last:Int, $before: String ) {
					gfEntries( first: $first, after: $after, last: $last, before: $before ) {
						pageInfo{
							hasNextPage
							hasPreviousPage
							startCursor
							endCursor
						}
						edges {
							cursor
						}
						nodes {
							... on GfSubmittedEntry {
								databaseId
							}
						}
					}
				}
		';

		$variables = [
			'first'  => 2,
			'after'  => null,
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		// Check `first` argument.
		$this->assertArrayNotHasKey( 'errors', $response, 'First array has errors.' );

		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'First does not return correct amount.' );
		$this->assertSame( $entry_ids[0], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'First - node 0 is not same.' );
		$this->assertSame( $entry_ids[1], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'First - node 1 is not same.' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'First does not have next page.' );
		$this->assertFalse( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'First has previous page.' );

		// Check `after` argument.
		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gfEntries']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #1 array has errors.' );
		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'First/after #1 does not return correct amount.' );
		$this->assertSame( $entry_ids[2], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'First/after #1- node 0 is not same.' );
		$this->assertSame( $entry_ids[3], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'First/after #1 - node 1 is not same' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'First/after #1 does not have next page.' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'First/after #1 does not have previous page.' );

		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gfEntries']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'First/after #2 does not return correct amount.' );
		$this->assertSame( $entry_ids[4], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'First/after #2 - node 0 is not same' );
		$this->assertSame( $entry_ids[5], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'First/after #2 - node 1 is not same.' );
		$this->assertFalse( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'First/after #2 has next page.' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'First/after #2 does not have previous page.' );

		// Check last argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last array has errors.' );
		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'Last does not return correct amount.' );
		$this->assertSame( $entry_ids[4], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'Last - node 0 is not same' );
		$this->assertSame( $entry_ids[5], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'Last - node 1 is not same.' );
		$this->assertFalse( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'Last has next page.' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'Last does not have previous page.' );

		// Check `before` argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gfEntries']['pageInfo']['endCursor'],
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last/before #1 array has errors.' );
		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'last/before does not return correct amount.' );
		$this->assertSame( $entry_ids[2], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'last/before #1 - node 0 is not same' );
		$this->assertSame( $entry_ids[3], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'last/before #1 - node 1 is not same' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'Last/before #1 does not have next page.' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'Last/before #1 does not have previous page.' );

		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gfEntries']['pageInfo']['endCursor'],
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last/before #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gfEntries']['nodes'], 'last/before does not return correct amount.' );
		$this->assertSame( $entry_ids[0], $response['data']['gfEntries']['nodes'][0]['databaseId'], 'last/before #2 - node 0 is not same' );
		$this->assertSame( $entry_ids[1], $response['data']['gfEntries']['nodes'][1]['databaseId'], 'last/before #2 - node 1 is not same' );
		$this->assertTrue( $response['data']['gfEntries']['pageInfo']['hasNextPage'], 'Last/before #2 does not have next page.' );
		$this->assertFalse( $response['data']['gfEntries']['pageInfo']['hasPreviousPage'], 'Last/before #2 has previous page.' );
	}

	/**
	 * Returns the full entry query for reuse.
	 *
	 * @return string
	 */
	private function get_entry_query() : string {
		return '
			query getEntry($id: ID!, $idType: EntryIdTypeEnum) {
				gfEntry(id: $id, idType: $idType) {
					createdBy {
						databaseId
					}
					createdById
					createdByDatabaseId
					dateCreated
					dateUpdated
					dateCreatedGmt
					dateUpdatedGmt
					formDatabaseId
					form {
						databaseId
					}
					formFields {
						nodes {
							id
						}
					}
					id
					ip
					isDraft
					isSubmitted
					sourceUrl
					userAgent
					... on GfDraftEntry {
						resumeToken
					}
					... on GfSubmittedEntry {
						databaseId
						isStarred
						isRead
						postDatabaseId
						status
					}
				}
			}
		';
	}

	/**
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 * @return array
	 */
	public function expected_field_response( array $entry, array $form ) : array {
		return [
			$this->expectedObject(
				'gfEntry',
				[
					$this->expectedField( 'createdByDatabaseId', ! empty( $entry['created_by'] ) ? (int) $entry['created_by'] : static::IS_NULL ),
					$this->expectedField( 'createdById', ! empty( $entry['created_by'] ) ? $this->toRelayId( 'user', $entry['created_by'] ) : static::IS_NULL ),
					$this->expectedObject(
						'createdBy',
						[
							$this->expectedField( 'databaseId', ! empty( $entry['created_by'] ) ? (int) $entry['created_by'] : static::IS_NULL ),
						]
					),
					$this->expectedField( 'databaseId', ! empty( $entry['id'] ) ? (int) $entry['id'] : static::IS_NULL ),
					$this->expectedField( 'dateCreated', ! empty( $entry['date_created'] ) ? get_date_from_gmt( $entry['date_created'] ) : static::IS_NULL ),
					$this->expectedField( 'dateCreatedGmt', ! empty( $entry['date_created'] ) ? $entry['date_created'] : static::IS_NULL ),
					$this->expectedField( 'dateUpdated', ! empty( $entry['date_updated'] ) ? get_date_from_gmt( $entry['date_updated'] ) : static::IS_NULL ),
					$this->expectedField( 'dateUpdatedGmt', ! empty( $entry['date_updated'] ) ? $entry['date_updated'] : static::IS_NULL ),
					$this->expectedField( 'formDatabaseId', ! empty( $form['id'] ) ? (int) $form['id'] : static::IS_NULL ),
					$this->expectedObject(
						'form',
						[
							$this->expectedField( 'databaseId', isset( $form['id'] ) ? (int) $form['id'] : static::IS_NULL ),
						]
					),
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								[
									$this->expectedField( 'id', (int) $form['fields'][0]['id'] ),
								]
							),
						]
					),
					$this->expectedField( 'id', $this->toRelayId( EntriesLoader::$name, $entry['id'] ) ),
					$this->expectedField( 'ip', ! empty( $entry['ip'] ) ? $entry['ip'] : static::IS_NULL ),
					$this->expectedField( 'isDraft', ! empty( $entry['resume_token'] ) ),
					$this->expectedField( 'isSubmitted', ! empty( $entry['id'] ) ),
					$this->expectedField( 'isRead', ! empty( $entry['is_read'] ) ),
					$this->expectedField( 'isStarred', ! empty( $entry['isStarred'] ) ),
					$this->expectedField( 'resumeToken', ! empty( $entry['resumeToken'] ) ? $entry['resumeToken'] : static::IS_NULL ),
					$this->expectedField( 'sourceUrl', ! empty( $entry['source_url'] ) ? $entry['source_url'] : static::IS_NULL ),
					$this->expectedField( 'status', ! empty( $entry['status'] ) ? GFHelpers::get_enum_for_value( Enum\EntryStatusEnum::$type, $entry['status'] ) : static::IS_NULL ),
					$this->expectedField( 'userAgent', ! empty( $entry['user_agent'] ) ? $entry['user_agent'] : static::IS_NULL ),
				]
			),
		];
	}
}
