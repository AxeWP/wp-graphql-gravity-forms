<?php
/**
 * Test GraphQL EntryConnection Queries.
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
	 * Tests the form->entries connection only contains entries on the form.
	 */
	public function testFormOnlyContainsRelatedEntries() : void {
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
}
