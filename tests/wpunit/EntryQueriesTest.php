<?php
/**
 * Test GraphQL Entry Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum;
use Helper\GFHelpers\GFHelpers;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
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

		// Test with bad ID.
		$variables = [
			'id'     => 99999999,
			'idType' => 'DATABASE_ID',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNull( $actual['data']['gfEntry'] );

		// Test with Database ID.
		$variables['id'] = $this->entry_ids[0];

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_field_response( $entry, $form );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );

		// Test with bad global ID.
		$variables = [
			'id'     => 'not-a-real-id',
			'idType' => 'ID',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayHasKey( 'errors', $actual );

		// Test with Global Id.
		$variables['id'] = $global_id;
		$actual          = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );
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

		$draft_token = $this->factory->draft_entry->create(
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

		// Test with bad resume token.
		$variables = [
			'id'     => 'not-a-real-id',
			'idType' => 'RESUME_TOKEN',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNull( $actual['data']['gfEntry'] );

		// Test with draft token
		$variables['id'] = $draft_token;
		$actual          = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $draft_token, $actual['data']['gfEntry']['resumeToken'] );

				// Test with bad global ID.
		$variables = [
			'id'     => 'not-a-real-id',
			'idType' => 'ID',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayHasKey( 'errors', $actual );

		// Test with Global Id.
		$variables['id'] = Relay::toGlobalId( DraftEntriesLoader::$name, $draft_token );
		$actual          = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $draft_token, $actual['data']['gfEntry']['resumeToken'] );

		$this->factory->draft_entry->delete( $draft_token );
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
