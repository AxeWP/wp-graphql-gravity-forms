<?php
/**
 * Test deleteGravityFormsEntry mutation .
 *
 * @package .
 */

use WPGraphQLGravityForms\Tests\Factories;

/**
 * Class - DeleteEntryMutationTest
 */
class DeleteEntryMutationTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	private $admin;
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
		$this->admin = $this->factory()->user->create_and_get(
			[
				'role' => 'administrator',
			]
		);
		$this->admin->add_cap( 'gravityforms_delete_entries' );
		wp_set_current_user( $this->admin->ID );

		$this->factory            = new Factories\Factory();
		$this->text_field_helper  = $this->tester->getTextFieldHelper();
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
		\WPGraphQL::clear_schema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		wp_delete_user( $this->admin->id );
		$this->factory->form->delete( $this->form_id );
		\WPGraphQL::clear_schema();

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `deleteGravityFormsEntry`.
	 */
	public function testDeleteGravityFormsEntry() : void {
		$actual = $this->createMutation();
		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_entry = GFFormsModel::get_draft_submission_values( $actual['data']['deleteGravityFormsEntry']['entryId'] );

		$this->assertNull( $actual_entry );
	}

	/**
	 * Tests `deleteGravityFormsEntry` when a bad entryId is supplied.
	 */
	public function testDeleteGravityFormsEntry_badToken() : void {
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
			mutation deleteGravityFormsEntry(
				$entryId: Int!,
				$clientMutationId: String,
			) {
				deleteGravityFormsEntry(
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
