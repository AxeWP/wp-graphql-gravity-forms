<?php
/**
 * Test GraphQL Entry Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use WPGraphQLGravityForms\Tests\Factories;
use WPGraphQLGravityForms\Types\Enum;

/**
 * Class - EntryQueriesTest
 */
class EntryQueriesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	protected $factory;
	private $admin;
	private $fields = [];
	private $form_id;
	private $entry_ids;

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

		$this->factory  = new Factories\Factory();
		$this->fields[] = $this->factory->field->create( $this->tester->getTextFieldDefaultArgs() );
		$this->form_id  = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );

		$this->entry_ids = $this->factory->entry->create_many(
			2,
			[
				'form_id'              => $this->form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		wp_delete_user( $this->admin->id );
		$this->factory->entry->delete( $this->entry_ids );
		$this->factory->form->delete( $this->form_id );
		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `gravityFormsEntry`.
	 */
	public function testGravityFormsEntryQuery() : void {
		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $this->entry_ids[0] );
		$entry     = GFAPI::get_entry( $this->entry_ids[0] );
		$form      = GFAPI::get_form( $this->form_id );

		$query = $this->get_entry_query();

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $this->entry_ids[0],
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
					'node' => [
						'databaseId' => (int) $entry['created_by'],
					],
				],
				'dateCreated' => $entry['date_created'],
				'dateUpdated' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'fields'      => [
					'edges' => [
						[
							'fieldValue' => [
								'value' => $entry[ $form['fields'][0]['id'] ],
							],
							'node'       => [
								'id' => $form['fields'][0]['id'],
							],
						],
					],
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'node' => [
						'formId' => $form['id'],
					],
				],
				'formId'      => $form['id'],
				'id'          => $global_id,
				'ip'          => $entry['ip'],
				'isDraft'     => (bool) null,
				'isRead'      => (bool) $entry['is_read'],
				'isStarred'   => (bool) $entry['is_starred'],
				'postId'      => $entry['post_id'],
				'resumeToken' => null,
				'sourceUrl'   => $entry['source_url'],
				'status'      => $this->tester->get_enum_for_value( Enum\EntryStatusEnum::$type, $entry['status'] ),
				'userAgent'   => $entry['user_agent'],
			],
		];
		// Test with Database Id.
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

		// Test with Global Id.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $global_id,
					'idType' => 'ID',
				],
			],
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}

	/**
	 * Tests `gravityFormsEntry` with no setup variables.
	 */
	public function testGravityFormsEntryQuery_empty() {
		$entry_id  = $this->factory->entry->create(
			[ 'form_id' => $this->form_id ]
		);
		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $entry_id );
		$entry     = GFAPI::get_entry( $entry_id );
		$form      = GFAPI::get_form( $this->form_id );
		$query     = $this->get_entry_query();

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $entry_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
					'node' => [
						'databaseId' => (int) $entry['created_by'],
					],
				],
				'dateCreated' => $entry['date_created'],
				'dateUpdated' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'fields'      => [
					'edges' => [
						[
							'fieldValue' => [
								'value' => null,
							],
							'node'       => [
								'id' => $form['fields'][0]['id'],
							],
						],
					],
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'node' => [
						'formId' => $form['id'],
					],
				],
				'formId'      => $form['id'],
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
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Tests `gravityFormsEntry` with draft entry.
	 */
	public function testGravityFormsEntryQuery_draft() : void {
		$draft_tokens = $this->factory->draft->create_many( 2, [ 'form_id' => $this->form_id ] );

		$query = '
			query( $id: ID! ) {
				gravityFormsEntry( id: $id ) {
					resumeToken
				}
			}
		';

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id' => $draft_tokens[0],
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $draft_tokens[0], $actual['data']['gravityFormsEntry']['resumeToken'] );

		$this->factory->draft->delete( $draft_tokens );
	}

	/**
	 * Tests `gravityFormsEntries`.
	 */
	public function testGravityFormsEntriesQuery() : void {
		$query = '
			query {
				gravityFormsEntries {
					nodes {
						entryId
					}
				}
			}
		';

		$actual = graphql( [ 'query' => $query ] );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( 2, count( $actual['data']['gravityFormsEntries']['nodes'] ) );
	}

	/**
	 * Tests `gravityFormsEntries` with query args .
	 */
	public function testGravityFormsEntriesQueryArgs() {
		$entry_ids = $this->factory->entry->create_many(
			20,
			[
				'form_id'              => $this->form_id,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);

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

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'first'  => 10,
					'after'  => null,
					'last'   => null,
					'before' => null,
				],
			]
		);

		// Check `first` argument.
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( 10, count( $actual['data']['gravityFormsEntries']['nodes'] ) );

		// Check `after` argument.
		$expected_ids = wp_list_pluck( $actual['data']['gravityFormsEntries']['nodes'], 'entryId' );

		$cursor = $actual['data']['gravityFormsEntries']['edges'][4]['cursor'];

		$actual     = graphql(
			[
				'query'     => $query,
				'variables' => [
					'first' => 5,
					'after' => $cursor,
				],
			]
		);
		$actual_ids = wp_list_pluck( $actual['data']['gravityFormsEntries']['nodes'], 'entryId' );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertSame( array_slice( $expected_ids, 5, 5 ), $actual_ids );

		$this->factory->entry->delete( $entry_ids );
	}

	/**
	 * Returns the full entry query for reuse.
	 *
	 * @return string
	 */
	private function get_entry_query() : string {
		return '
			query getEntry($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType) {
					createdById
					createdBy {
						node {
							databaseId
						}
					}
					dateCreated
					dateUpdated
					entryId
					fields {
						edges {
							fieldValue {
								... on TextFieldValue {
									value
								}
							}
							node {
								id
							}
						}
						nodes {
							id
						}
					}
					form {
						node {
							formId
						}
					}
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
