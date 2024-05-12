<?php
/**
 * Test deleteGfEntry mutation .
 *
 * @package .
 */

use Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;

/**
 * Class - DeleteEntryMutationTest
 */
class DeleteEntryMutationTest extends GFGraphQLTestCase {
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

		// Your set up methods here.
		wp_set_current_user( $this->admin->ID );

		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id           = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id          = $this->factory->entry->create(
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
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `deleteGfEntry`.
	 */
	public function testDeleteGfEntry(): void {
		$query = $this->delete_mutation();

		$variables = [
			'id'          => $this->entry_id,
			'forceDelete' => false,
		];

		// Test as guest.
		wp_set_current_user( 0 );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayHasKey( 'errors', $response, 'Delete without permissions should fail' );

		// Test database ID.
		wp_set_current_user( $this->admin->ID );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Delete with databaseId has errors' );
		$this->assertEquals( $this->toRelayId( EntriesLoader::$name, $this->entry_id ), $response['data']['deleteGfEntry']['deletedId'], 'Delete with databaseId id mismatch' );
		$this->assertEquals( $this->entry_id, $response['data']['deleteGfEntry']['entry']['databaseId'], 'delete with databaseId mismatch' );
		$this->assertEquals( GFHelpers::get_enum_for_value( EntryStatusEnum::$type, 'trash' ), $response['data']['deleteGfEntry']['entry']['status'], 'delete with databaseId not sent to trash' );

		$actual_entry = GFAPI::get_entry( $response['data']['deleteGfEntry']['entry']['databaseId'] );

		$this->assertNotNull( $actual_entry, 'Trashed entry no longer exists' );

		// Test force delete
		$variables = [
			'id'          => $this->entry_id,
			'forceDelete' => true,
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );

		$actual_entry = GFAPI::get_entry( $response['data']['deleteGfEntry']['entry']['databaseId'] );

		$this->assertWPError( $actual_entry, 'force delete failed' );
	}

	/**
	 * Tests `deleteGfEntry` when a bad entryId is supplied.
	 */
	public function testDeleteGfEntry_badToken(): void {
		$query = $this->delete_mutation();

		// Test Global Id
		$variables = [
			'id'          => $this->toRelayId( EntriesLoader::$name, $this->entry_id ),
			'forceDelete' => false,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Delete with global has errors' );
		$this->assertEquals( $this->toRelayId( EntriesLoader::$name, $this->entry_id ), $response['data']['deleteGfEntry']['deletedId'], 'Delete with global id mismatch' );
		$this->assertEquals( $this->entry_id, $response['data']['deleteGfEntry']['entry']['databaseId'], 'delete with global id databaseId mismatch' );
		$this->assertEquals( GFHelpers::get_enum_for_value( EntryStatusEnum::$type, 'trash' ), $response['data']['deleteGfEntry']['entry']['status'], 'delete with globalId not sent to trash' );

		$actual_entry = GFAPI::get_entry( $response['data']['deleteGfEntry']['entry']['databaseId'] );

		$this->assertNotNull( $actual_entry, 'Trashed entry no longer exists' );
	}

	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function delete_mutation( array $args = [] ): string {
		return '
			mutation deleteGfEntry(
				$id: ID!,
				$forceDelete: Boolean,
			) {
				deleteGfEntry(
					input: {
						id: $id
						forceDelete: $forceDelete
					}
				) {
					deletedId
					entry {
						id
						databaseId
						status
					}
  			}
			}
		';
	}
}
