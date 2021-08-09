<?php
/**
 * Test deleteGravityFormsDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class - DeleteDraftEntryMutationTest
 */
class DeleteDraftEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $client_mutation_id;
	private $draft_token;
	private $text_field_helper;


	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->text_field_helper = $this->tester->getTextFieldHelper();
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );

		$this->form_id            = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->draft_token        = $this->factory->draft_entry->create(
			[ 'form_id' => $this->form_id ]
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
	 * Tests `deleteGravityFormsDraftEntry`.
	 */
	public function testDeleteGravityFormsDraftEntry() : void {
		$actual = $this->createMutation();
		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_draft = GFFormsModel::get_draft_submission_values( $actual['data']['deleteGravityFormsDraftEntry']['resumeToken'] );

		$this->assertNull( $actual_draft );
	}

	/**
	 * Tests `deleteGravityFormsDraftEntry` when a bad resumeToken is supplied.
	 */
	public function testDeleteGravityFormsDraftEntry_badToken() : void {
		$actual = $this->createMutation( [ 'resumeToken' => 'notarealtoken' ] );
		$this->assertArrayHasKey( 'errors', $actual );

		$this->factory->draft_entry->delete( $this->draft_token );
	}
	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function createMutation( array $args = [] ) : array {
		$mutation = '
			mutation deleteGravityFormsDraftEntry(
				$resumeToken: String!,
				$clientMutationId: String,
			) {
				deleteGravityFormsDraftEntry(
					input: {
						resumeToken: $resumeToken
						clientMutationId: $clientMutationId
					}
				) {
					clientMutationId
					resumeToken
  			}
			}
		';

		$variables = [
			'resumeToken'      => $args['resumeToken'] ?? $this->draft_token,
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
