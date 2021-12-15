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
	 * Tests `gravityFormsEntry`.
	 */
	public function testGravityFormsEntryQuery() : void {
		wp_set_current_user( $this->admin->ID );

		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $this->entry_ids[0] );
		$entry     = $this->factory->entry->get_object_by_id( $this->entry_ids[0] );
		$form      = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->get_entry_query();

		$variables = [
			'id'     => $this->entry_ids[0],
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
						'databaseId' => (int) $entry['created_by'],
				],
				'dateCreated' => get_date_from_gmt( $entry['date_created'] ),
				'dateCreatedGmt' => $entry['date_created'],
				'dateUpdated' => get_date_from_gmt( $entry['date_updated'] ),
				'dateUpdatedGmt' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'formFields'  => [
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'databaseId' => $form['id'],
				],
				'formDatabaseId' => $form['id'],
				'formId'      => Relay::toGlobalId( Form::$type, $form['id'] ),
				'id'          => $global_id,
				'ip'          => $entry['ip'],
				'isDraft'     => (bool) null,
				'isRead'      => ! empty( $entry['is_read'] ),
				'isStarred'   => (bool) $entry['is_starred'],
				'postId'      => $entry['post_id'],
				'resumeToken' => null,
				'sourceUrl'   => $entry['source_url'],
				'status'      => $this->tester->get_enum_for_value( Enum\EntryStatusEnum::$type, $entry['status'] ),
				'userAgent'   => $entry['user_agent'],
			],
		];
		// Test with Database Id.
		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( $expected, $response['data'] );

		// Test with Global Id.
		$variables = [
			'id'     => $global_id,
			'idType' => 'ID',
		];
		$response = $this->graphql( compact( 'query', 'variables' ) );


		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( $expected, $response['data'] );
	}

	/**
	 * Tests `gravityFormsEntry` with no setup variables.
	 */
	public function testGravityFormsEntryQuery_empty() {
		wp_set_current_user( $this->admin->ID );

		$entry_id  = $this->factory->entry->create(
			[ 'form_id' => $this->form_id ]
		);
		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $entry_id );
		$entry     = $this->factory->entry->get_object_by_id( $entry_id );
		$form      = $this->factory->form->get_object_by_id(  $this->form_id );

		$query     = $this->get_entry_query();

		$variables = [
			'id'     => $entry_id,
			'idType' => 'DATABASE_ID',
		];
		$response = $this->graphql( compact( 'query', 'variables' ) );


		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
						'databaseId' => (int) $entry['created_by'],
				],
				'dateCreated' => get_date_from_gmt( $entry['date_created'] ),
				'dateCreatedGmt' => $entry['date_created'],
				'dateUpdated' => get_date_from_gmt( $entry['date_updated'] ),
				'dateUpdatedGmt' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'formFields'  => [
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'databaseId' => $form['id'],
				],
				'formId'      => Relay::toGlobalId( Form::$type, $form['id'] ),
				'formDatabaseId' => $form['id'],
				'id'          => $global_id,
				'ip'          => $entry['ip'],
				'isDraft'     => (bool) null,
				'isRead'      => (bool) null,
				'isStarred'   => (bool) null,
				'postId'      => null,
				'resumeToken' => null,
				'sourceUrl'   => $entry['source_url'],
				'status'      => $this->tester->get_enum_for_value( Enum\EntryStatusEnum::$type, $entry['status'] ),
				'userAgent'   => $entry['user_agent'],
			],
		];
		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( $expected, $response['data'] );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Tests `gravityFormsEntry` with draft entry.
	 */
	public function testGravityFormsEntryQuery_draft() : void {
		wp_set_current_user( $this->admin->ID );

		$draft_tokens = $this->factory->draft_entry->create_many( 2, [ 'form_id' => $this->form_id ] );

		$query = '
			query( $id: ID!, $idType: EntryIdTypeEnum) {
				gravityFormsEntry( id: $id, idType: $idType ) {
					resumeToken
				}
			}
		';

		$actual = $this->graphql(
			[
				'query'     => $query,
				'variables' => [
					'id' => $draft_tokens[0],
					'idType' => 'RESUME_TOKEN',
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $draft_tokens[0], $actual['data']['gravityFormsEntry']['resumeToken'] );

		$this->factory->draft_entry->delete( $draft_tokens );
	}

	/**
	 * Tests `gravityFormsEntries`.
	 */
	public function testEntriesQuery() : void {
		wp_set_current_user( $this->admin->ID );

		$query = '
			query {
				gravityFormsEntries {
					nodes {
						entryId
					}
				}
			}
		';

		$actual = $this->graphql( [ 'query' => $query ] );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gravityFormsEntries']['nodes'] );
	}

	/**
	 * Tests `gravityFormsEntries` with query args .
	 */
	public function testEntriesQueryArgs() {
		wp_set_current_user( $this->admin->ID );

		$entry_ids = array_reverse( $this->entry_ids );

		$query = '
				query( $first: Int, $after: String, $last:Int, $before: String ) {
					gravityFormsEntries( first: $first, after: $after, last: $last, before: $before ) {
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
							entryId
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

		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'First does not return correct amount.' );
		$this->assertSame( $entry_ids[0], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'First - node 0 is not same.' );
		$this->assertSame( $entry_ids[1], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'First - node 1 is not same.' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'First does not have next page.' );
		$this->assertFalse( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'First has previous page.' );

		// Check `after` argument.
		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gravityFormsEntries']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #1 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'First/after #1 does not return correct amount.' );
		$this->assertSame( $entry_ids[2], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'First/after #1- node 0 is not same.' );
		$this->assertSame( $entry_ids[3], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'First/after #1 - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'First/after #1 does not have next page.' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'First/after #1 does not have previous page.' );

		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gravityFormsEntries']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'First/after #2 does not return correct amount.' );
		$this->assertSame( $entry_ids[4], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'First/after #2 - node 0 is not same' );
		$this->assertSame( $entry_ids[5], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'First/after #2 - node 1 is not same.' );
		$this->assertFalse( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'First/after #2 has next page.' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'First/after #2 does not have previous page.' );

		// Check last argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'Last does not return correct amount.' );
		$this->assertSame( $entry_ids[4], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'Last - node 0 is not same' );
		$this->assertSame( $entry_ids[5], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'Last - node 1 is not same.' );
		$this->assertFalse( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'Last has next page.' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'Last does not have previous page.' );

		// Check `before` argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gravityFormsEntries']['pageInfo']['endCursor'],
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last/before #1 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'last/before does not return correct amount.' );
		$this->assertSame( $entry_ids[2], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'last/before #1 - node 0 is not same' );
		$this->assertSame( $entry_ids[3], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'last/before #1 - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'Last/before #1 does not have next page.' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'Last/before #1 does not have previous page.' );

		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gravityFormsEntries']['pageInfo']['endCursor'],
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last/before #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsEntries']['nodes'], 'last/before does not return correct amount.' );
		$this->assertSame( $entry_ids[0], $response['data']['gravityFormsEntries']['nodes'][0]['entryId'], 'last/before #2 - node 0 is not same' );
		$this->assertSame( $entry_ids[1], $response['data']['gravityFormsEntries']['nodes'][1]['entryId'], 'last/before #2 - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsEntries']['pageInfo']['hasNextPage'], 'Last/before #2 does not have next page.' );
		$this->assertFalse( $response['data']['gravityFormsEntries']['pageInfo']['hasPreviousPage'], 'Last/before #2 has previous page.' );
	}

	/**
	 * Returns the full entry query for reuse.
	 *
	 * @return string
	 */
	private function get_entry_query() : string {
		return '
			query getEntry($id: ID!, $idType: EntryIdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType) {
					createdById
					createdBy {
						databaseId
					}
					dateCreated
					dateCreatedGmt
					dateUpdated
					dateUpdatedGmt
					entryId
					formFields {
						nodes {
							id
						}
					}
					form {
						databaseId
					}
					formDatabaseId
					formId
					id
					ip
					isDraft
					isRead
					isStarred
					postId
					resumeToken
					sourceUrl
					status
					userAgent
				}
			}
		';
	}
}
