<?php
/**
 * Test deleteGfEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - DeleteEntryMutationTest
 */
class DeleteEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $entry_id;
	private $client_mutation_id;
	private $text_field_helper;


	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		wp_set_current_user( $this->admin->ID );

		$this->text_field_helper  = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]           = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id            = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id           = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);
		$this->client_mutation_id = 'someUniqueId';

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
	public function testDeletegfEntry() : void {
		$actual = $this->createMutation();
		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_entry = GFFormsModel::get_draft_submission_values( $actual['data']['deleteGfEntry']['entryId'] );

		$this->assertNull( $actual_entry );
	}

	/**
	 * Tests `deleteGfEntry` when a bad entryId is supplied.
	 */
	public function testDeletegfEntry_badToken() : void {
		$actual = $this->createMutation( [ 'entryId' => 0 ] );
		$this->assertArrayHasKey( 'errors', $actual );
	}
	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function createMutation( array $args = [] ) : array {
		$mutation = '
			mutation deleteGfEntry(
				$entryId: Int!,
				$clientMutationId: String,
			) {
				deleteGfEntry(
					input: {
						entryId: $entryId
						clientMutationId: $clientMutationId
					}
				) {
					clientMutationId
					entryId
  			}
			}
		';

		$variables = [
			'entryId'          => $args['entryId'] ?? $this->entry_id,
			'clientMutationId' => $this->client_mutation_id,
		];

		return graphql(
			[
				'query'     => $mutation,
				'variables' => $variables,
			]
		);
	}
}
