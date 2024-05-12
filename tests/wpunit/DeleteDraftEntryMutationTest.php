<?php
/**
 * Test deleteGfDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;

/**
 * Class - DeleteDraftEntryMutationTest
 */
class DeleteDraftEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $draft_token;
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

		$this->form_id     = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->draft_token = $this->factory->draft_entry->create(
			[
				'form_id'    => $this->form_id,
				'created_by' => $this->admin->ID,
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
	 * Tests `deleteGfDraftEntry`.
	 */
	public function testDeleteGfDraftEntry(): void {
		$query = $this->delete_mutation();

		$variables = [
			'id'     => $this->draft_token,
			'idType' => 'RESUME_TOKEN',
		];

		// Test as guest.
		wp_set_current_user( 0 );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayHasKey( 'errors', $response, 'Delete without permissions should fail' );

		// Test database ID.
		wp_set_current_user( $this->admin->ID );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Delete with resume_token has errors' );
		$this->assertEquals( $this->toRelayId( DraftEntriesLoader::$name, $this->draft_token ), $response['data']['deleteGfDraftEntry']['deletedId'], 'Delete with resume_token id mismatch' );
		$this->assertEquals( $this->draft_token, $response['data']['deleteGfDraftEntry']['draftEntry']['resumeToken'], 'Delete with resume_token token mismatch' );

		$actual_draft = GFFormsModel::get_draft_submission_values( $response['data']['deleteGfDraftEntry']['draftEntry']['resumeToken'] );

		$this->assertNull( $actual_draft, 'delete with resume_token failed' );
	}

	/**
	 * Tests `deleteGfDraftEntry`.
	 */
	public function testDeleteGfDraftEntry_globalId(): void {
		$query = $this->delete_mutation();

		// Test Global Id
		$variables = [
			'id'     => $this->toRelayId( DraftEntriesLoader::$name, $this->draft_token ),
			'idType' => 'ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Delete with id has errors' );
		$this->assertEquals( $this->toRelayId( DraftEntriesLoader::$name, $this->draft_token ), $response['data']['deleteGfDraftEntry']['deletedId'], 'Delete with id id mismatch' );
		$this->assertEquals( $this->draft_token, $response['data']['deleteGfDraftEntry']['draftEntry']['resumeToken'], 'Delete with id token mismatch' );

		$actual_draft = GFFormsModel::get_draft_submission_values( $response['data']['deleteGfDraftEntry']['draftEntry']['resumeToken'] );

		$this->assertNull( $actual_draft, 'delete with id failed' );
	}

	/**
	 * Tests `deleteGfDraftEntry` when a bad resumeToken is supplied.
	 */
	public function testDeleteGfDraftEntry_badToken(): void {
		wp_set_current_user( $this->admin->ID );

		$query = $this->delete_mutation();

		$variables = [
			'id'     => 'not4RealT0ke3n',
			'idType' => 'RESUME_TOKEN',
		];

		$response = graphql( compact( 'query', 'variables' ) );

		$this->assertArrayHasKey( 'errors', $response );

		$this->factory->draft_entry->delete( $this->draft_token );
	}

	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function delete_mutation(): string {
		return '
			mutation deleteGfDraftEntry(
				$id: ID!,
				$idType: DraftEntryIdTypeEnum
			) {
				deleteGfDraftEntry(
					input: {
						id: $id
						idType: $idType
					}
				) {
					deletedId
					draftEntry{
						id
						resumeToken
					}
  			}
			}
		';
	}
}
