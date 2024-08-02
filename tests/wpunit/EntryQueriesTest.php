<?php
/**
 * Test GraphQL Entry Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;
use WPGraphQL\GF\Type\Enum;

/**
 * Class - EntryQueriesTest
 */
class EntryQueriesTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $entry_id;
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

		$this->entry_id = $this->factory->entry->create(
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
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Returns the full entry query for reuse.
	 */
	private function get_entry_query(): string {
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
							databaseId
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
	 * Tests `gfEntry`.
	 */
	public function testEntryQuery(): void {
		wp_set_current_user( $this->admin->ID );

		$global_id = Relay::toGlobalId( EntriesLoader::$name, $this->entry_id );
		$entry     = $this->factory->entry->get_object_by_id( $this->entry_id );
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
		$variables['id'] = $this->entry_id;

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
	public function testEmptyEntryQuery() {
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
	public function testDraftEntryQuery(): void {
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
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $entry, array $form ): array {
		return [
			$this->expectedObject(
				'gfEntry',
				[
					$this->expectedField( 'createdByDatabaseId', ! empty( $entry['created_by'] ) ? (int) $entry['created_by'] : self::IS_NULL ),
					$this->expectedField( 'createdById', ! empty( $entry['created_by'] ) ? $this->toRelayId( 'user', $entry['created_by'] ) : self::IS_NULL ),
					$this->expectedObject(
						'createdBy',
						[
							$this->expectedField( 'databaseId', ! empty( $entry['created_by'] ) ? (int) $entry['created_by'] : self::IS_NULL ),
						]
					),
					$this->expectedField( 'databaseId', ! empty( $entry['id'] ) ? (int) $entry['id'] : self::IS_NULL ),
					$this->expectedField( 'dateCreated', ! empty( $entry['date_created'] ) ? get_date_from_gmt( $entry['date_created'] ) : self::IS_NULL ),
					$this->expectedField( 'dateCreatedGmt', ! empty( $entry['date_created'] ) ? $entry['date_created'] : self::IS_NULL ),
					$this->expectedField( 'dateUpdated', ! empty( $entry['date_updated'] ) ? get_date_from_gmt( $entry['date_updated'] ) : self::IS_NULL ),
					$this->expectedField( 'dateUpdatedGmt', ! empty( $entry['date_updated'] ) ? $entry['date_updated'] : self::IS_NULL ),
					$this->expectedField( 'formDatabaseId', ! empty( $form['id'] ) ? (int) $form['id'] : self::IS_NULL ),
					$this->expectedObject(
						'form',
						[
							$this->expectedField( 'databaseId', isset( $form['id'] ) ? (int) $form['id'] : self::IS_NULL ),
						]
					),
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								[
									$this->expectedField( 'id', Relay::toGlobalId( FormFieldsLoader::$name, (string) $form['id'] . ':' . (string) $form['fields'][0]['id'] ) ),
									$this->expectedField( 'databaseId', (int) $form['fields'][0]['id'] ),
								]
							),
						]
					),
					$this->expectedField( 'id', $this->toRelayId( EntriesLoader::$name, $entry['id'] ) ),
					$this->expectedField( 'ip', ! empty( $entry['ip'] ) ? $entry['ip'] : self::IS_NULL ),
					$this->expectedField( 'isDraft', ! empty( $entry['resume_token'] ) ),
					$this->expectedField( 'isSubmitted', ! empty( $entry['id'] ) ),
					$this->expectedField( 'isRead', ! empty( $entry['is_read'] ) ),
					$this->expectedField( 'isStarred', ! empty( $entry['isStarred'] ) ),
					$this->expectedField( 'resumeToken', ! empty( $entry['resumeToken'] ) ? $entry['resumeToken'] : self::IS_NULL ),
					$this->expectedField( 'sourceUrl', ! empty( $entry['source_url'] ) ? $entry['source_url'] : self::IS_NULL ),
					$this->expectedField( 'status', ! empty( $entry['status'] ) ? GFHelpers::get_enum_for_value( Enum\EntryStatusEnum::$type, $entry['status'] ) : self::IS_NULL ),
					$this->expectedField( 'userAgent', ! empty( $entry['user_agent'] ) ? $entry['user_agent'] : self::IS_NULL ),
				]
			),
		];
	}
}
