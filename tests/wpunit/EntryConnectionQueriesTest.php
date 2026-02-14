<?php
/**
 * Test GraphQL EntryConnection Queries.
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - EntryConnectionQueriesTest
 */
class EntryConnectionQueriesTest extends GFGraphQLTestCase {
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

		// Create entries with deterministic timestamps to avoid flaky tests.
		$this->entry_ids = [];
		$base_time       = strtotime( '2000-01-01 00:00:00' );
		for ( $i = 0; $i < 6; $i++ ) {
			$this->entry_ids[] = $this->factory->entry->create(
				[
					'form_id'              => $this->form_id,
					'created_by'           => $this->admin->ID,
					$this->fields[0]['id'] => 'This is a default Text entry.',
					'date_created'         => gmdate( 'Y-m-d H:i:s', $base_time + $i ),
				]
			);
		}

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
	 * Tests `gfEntries`.
	 */
	public function testEntriesQuery(): void {
		wp_set_current_user( $this->admin->ID );

		$query = '
			query {
				gfEntries {
					nodes {
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

		$actual = $this->graphql( [ 'query' => $query ] );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
	}

	/**
	 * Test entries count.
	 */
	public function testEntriesCount() {
		wp_set_current_user( $this->admin->ID );

		$entry_ids = array_reverse( $this->entry_ids );

		$query = '
			query testEntryCount( $status: EntryStatusEnum ) {
				gfForms {
					nodes {
						entries( where: {status: $status} ) {
							count
						}
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First array has errors' );

		$this->assertSame( count( $entry_ids ), $response['data']['gfForms']['nodes'][0]['entries']['count'] );

		$result = GFAPI::update_entry_property( $entry_ids[0], 'status', 'trash' );

		$variables = [
			'status' => 'TRASH',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Second array has errors' );

		$this->assertSame( 1, $response['data']['gfForms']['nodes'][0]['entries']['count'] );
	}

	/**
	 * Tests the form->entries connection only contains entries on the form.
	 */
	public function testFormOnlyContainsRelatedEntries(): void {
		$form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$entry_id = $this->factory->entry->create(
			[
				'form_id'              => $form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);

		$query = '
			query( $id: ID!) {
				gfForm( id: $id, idType: DATABASE_ID ) {
					entries {
						nodes {
							... on GfSubmittedEntry {
								databaseId
								formDatabaseId
							}
						}
					}
				}
			}
		';

		$variables = [
			'id' => $form_id,
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 1, $actual['data']['gfForm']['entries']['nodes'] );
		$this->assertEquals( $entry_id, $actual['data']['gfForm']['entries']['nodes'][0]['databaseId'] );

		// cleanup.
		$this->factory->entry->delete( $entry_id );
		$this->factory->form->delete( $form_id );
	}

	/**
	 * Tests `gfEntries` with `status` connection arg.
	 */
	public function testEntriesQueryWithStatusFilter(): void {
		wp_set_current_user( $this->admin->ID );

		// Trash one entry.
		GFAPI::update_entry_property( $this->entry_ids[0], 'status', 'trash' );

		$query = '
			query testStatusFilter( $status: EntryStatusEnum ) {
				gfEntries( where: { status: $status } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
							status
						}
					}
				}
			}
		';

		// Test without filter - should return active entries (default).
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 5, $actual['data']['gfEntries']['nodes'] );

		// Test filtering for active entries.
		$variables = [ 'status' => 'ACTIVE' ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 5, $actual['data']['gfEntries']['nodes'] );

		// Test filtering for trashed entries.
		$variables = [ 'status' => 'TRASH' ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 1, $actual['data']['gfEntries']['nodes'] );
	}

	/**
	 * Tests `gfEntries` with `formIds` connection arg.
	 */
	public function testEntriesQueryWithFormIdsFilter(): void {
		wp_set_current_user( $this->admin->ID );

		// Create another form with entries.
		$other_form_id  = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);
		$other_entry_id = $this->factory->entry->create(
			[
				'form_id'              => $other_form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'Entry on other form',
			]
		);

		$query = '
			query testFormIdsFilter( $formIds: [ID] ) {
				gfEntries( where: { formIds: $formIds } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
							formDatabaseId
						}
					}
				}
			}
		';

		// Test without filter - should return all entries.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 7, $actual['data']['gfEntries']['nodes'] );

		// Test filtering by form ID.
		$variables = [ 'formIds' => [ $this->form_id ] ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
		foreach ( $actual['data']['gfEntries']['nodes'] as $node ) {
			$this->assertEquals( $this->form_id, $node['formDatabaseId'] );
		}

		// Test filtering by other form ID.
		$variables = [ 'formIds' => [ $other_form_id ] ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 1, $actual['data']['gfEntries']['nodes'] );
		$this->assertEquals( $other_form_id, $actual['data']['gfEntries']['nodes'][0]['formDatabaseId'] );

		// Cleanup.
		$this->factory->entry->delete( $other_entry_id );
		$this->factory->form->delete( $other_form_id );
	}

	/**
	 * Tests `gfEntries` with `isRead` connection arg.
	 */
	public function testEntriesQueryWithIsReadFilter(): void {
		wp_set_current_user( $this->admin->ID );

		// Mark 2 entries as read.
		GFAPI::update_entry_property( $this->entry_ids[0], 'is_read', 1 );
		GFAPI::update_entry_property( $this->entry_ids[1], 'is_read', 1 );

		$query = '
			query testIsReadFilter( $isRead: Boolean ) {
				gfEntries( where: { isRead: $isRead } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
							isRead
						}
					}
				}
			}
		';

		// Test without filter - should return all entries.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test filtering for read entries.
		$variables = [ 'isRead' => true ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 2, $actual['data']['gfEntries']['nodes'] );
		foreach ( $actual['data']['gfEntries']['nodes'] as $node ) {
			$this->assertTrue( $node['isRead'] );
		}

		// Test filtering for unread entries.
		$variables = [ 'isRead' => false ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 4, $actual['data']['gfEntries']['nodes'] );
		foreach ( $actual['data']['gfEntries']['nodes'] as $node ) {
			$this->assertFalse( $node['isRead'] );
		}
	}

	/**
	 * Tests `gfEntries` with `isStarred` connection arg.
	 */
	public function testEntriesQueryWithIsStarredFilter(): void {
		wp_set_current_user( $this->admin->ID );

		// Mark 3 entries as starred.
		GFAPI::update_entry_property( $this->entry_ids[0], 'is_starred', 1 );
		GFAPI::update_entry_property( $this->entry_ids[1], 'is_starred', 1 );
		GFAPI::update_entry_property( $this->entry_ids[2], 'is_starred', 1 );

		$query = '
			query testIsStarredFilter( $isStarred: Boolean ) {
				gfEntries( where: { isStarred: $isStarred } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
							isStarred
						}
					}
				}
			}
		';

		// Test without filter - should return all entries.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test filtering for starred entries.
		$variables = [ 'isStarred' => true ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 3, $actual['data']['gfEntries']['nodes'] );
		foreach ( $actual['data']['gfEntries']['nodes'] as $node ) {
			$this->assertTrue( $node['isStarred'] );
		}

		// Test filtering for unstarred entries.
		$variables = [ 'isStarred' => false ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 3, $actual['data']['gfEntries']['nodes'] );
		foreach ( $actual['data']['gfEntries']['nodes'] as $node ) {
			$this->assertFalse( $node['isStarred'] );
		}
	}

	/**
	 * Tests `gfEntries` with `dateFilters` connection arg.
	 */
	public function testEntriesQueryWithDateFilters(): void {
		wp_set_current_user( $this->admin->ID );

		// Get the date of the first entry.
		$first_entry = GFAPI::get_entry( $this->entry_ids[0] );
		$entry_date  = $first_entry['date_created']; // Format: Y-m-d H:i:s

		$query = '
			query testDateFilters( $startDate: String, $endDate: String ) {
				gfEntries( where: { dateFilters: { startDate: $startDate, endDate: $endDate } } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
							dateCreated
						}
					}
				}
			}
		';

		// Test without filter - should return all entries.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test filtering with start date (tomorrow relative to the first entry - should return 0 entries).
		$tomorrow  = gmdate( 'Y-m-d H:i:s', strtotime( $entry_date . ' +1 day' ) );
		$variables = [ 'startDate' => $tomorrow ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 0, $actual['data']['gfEntries']['nodes'] );

		// Test filtering with end date (yesterday relative to the first entry - should return 0 entries).
		$yesterday = gmdate( 'Y-m-d H:i:s', strtotime( $entry_date . ' -1 day' ) );
		$variables = [ 'endDate' => $yesterday ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 0, $actual['data']['gfEntries']['nodes'] );

		// Test filtering with start date in the past relative to the first entry - should return all entries.
		$past_date = gmdate( 'Y-m-d H:i:s', strtotime( $entry_date . ' -1 week' ) );
		$variables = [ 'startDate' => $past_date ];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
	}

	/**
	 * Tests `gfEntries` with `fieldFilters` connection arg.
	 */
	public function testEntriesQueryWithFieldFilters(): void {
		wp_set_current_user( $this->admin->ID );

		$field_id = (string) $this->fields[0]['id'];

		$query = '
			query testFieldFilters( $fieldFilters: [EntriesFieldFiltersInput] ) {
				gfEntries( where: { fieldFilters: $fieldFilters } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
						}
					}
				}
			}
		';

		// Test without filter - should return all entries.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test filtering by field value (contains).
		$variables = [
			'fieldFilters' => [
				[
					'key'          => $field_id,
					'operator'     => 'CONTAINS',
					'stringValues' => [ 'default Text' ],
				],
			],
		];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test filtering by field value (not contains).
		$variables = [
			'fieldFilters' => [
				[
					'key'          => $field_id,
					'operator'     => 'IS_NOT',
					'stringValues' => [ 'This is a default Text entry.' ],
				],
			],
		];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 0, $actual['data']['gfEntries']['nodes'] );
	}

	/**
	 * Tests `gfEntries` with `orderby` connection arg.
	 */
	public function testEntriesQueryWithOrderby(): void {
		wp_set_current_user( $this->admin->ID );

		$query = '
			query testOrderby( $orderby: EntriesConnectionOrderbyInput ) {
				gfEntries( where: { orderby: $orderby } ) {
					nodes {
						... on GfSubmittedEntry {
							databaseId
						}
					}
				}
			}
		';

		// Test without orderby - should return entries in default order.
		$variables = [];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );

		// Test ordering by date_created ascending.
		$variables = [
			'orderby' => [
				'field' => 'date_created',
				'order' => 'ASC',
			],
		];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
		// First entry should be the first created.
		$this->assertEquals( $this->entry_ids[0], $actual['data']['gfEntries']['nodes'][0]['databaseId'] );

		// Test ordering by date_created descending.
		$variables = [
			'orderby' => [
				'field' => 'date_created',
				'order' => 'DESC',
			],
		];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 6, $actual['data']['gfEntries']['nodes'] );
		// First entry should be the last created.
		$this->assertEquals( $this->entry_ids[5], $actual['data']['gfEntries']['nodes'][0]['databaseId'] );
	}

	/**
	 * Tests `gfSubmittedEntries` with combined connection args.
	 */
	public function testSubmittedEntriesQueryWithCombinedFilters(): void {
		wp_set_current_user( $this->admin->ID );

		// Mark 1 entry as read and starred.
		GFAPI::update_entry_property( $this->entry_ids[0], 'is_read', 1 );
		GFAPI::update_entry_property( $this->entry_ids[0], 'is_starred', 1 );

		$query = '
			query testCombinedFilters( $formIds: [ID], $isRead: Boolean, $isStarred: Boolean ) {
				gfSubmittedEntries( where: { formIds: $formIds, isRead: $isRead, isStarred: $isStarred } ) {
					nodes {
						databaseId
						isRead
						isStarred
					}
				}
			}
		';

		// Test filtering for read AND starred entries.
		$variables = [
			'formIds'   => [ $this->form_id ],
			'isRead'    => true,
			'isStarred' => true,
		];
		$actual    = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertCount( 1, $actual['data']['gfSubmittedEntries']['nodes'] );
		$this->assertTrue( $actual['data']['gfSubmittedEntries']['nodes'][0]['isRead'] );
		$this->assertTrue( $actual['data']['gfSubmittedEntries']['nodes'][0]['isStarred'] );
	}
}
