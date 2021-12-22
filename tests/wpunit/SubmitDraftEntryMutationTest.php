<?php
/**
 * Test submitGfDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class - SubmitDraftEntryMutationTest
 */
class SubmitDraftEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $draft_token;
	private $client_mutation_id;
	private $text_field_helper;


	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		$this->text_field_helper  = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]           = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id            = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->draft_token        = $this->factory->draft_entry->create(
			[
				'form_id'      => $this->form_id,
				'entry'        => [
					$this->fields[0]['id'] => 'value1',
				],
				'field_values' => [
					'input_' . $this->fields[0]['id'] => 'value1',
				],
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
		$this->factory->draft_entry->delete( $this->draft_token );
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `submitGfDraft
	 */
	public function testSubmitGravityFormsDraftEntry() : void {
		wp_set_current_user( $this->admin->ID );
		$actual = $this->createMutation();
		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_entry = $this->factory->entry->get_object_by_id( $actual['data']['submitGfDraftEntry']['entryId'] );

		$this->assertEquals( $actual_entry['id'], $actual['data']['submitGfDraftEntry']['entryId'] );

		$this->assertEquals( 'value1', $actual['data']['submitGfDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->factory->entry->delete( $actual['data']['submitGfDraftEntry']['entryId'] );
	}

	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function createMutation( array $args = [] ) : array {
		$mutation = '
			mutation submitGfDraftEntry (
				$resumeToken: String!,
				$clientMutationId: String,
			) {
				submitGfDraftEntry (
					input: {
						resumeToken: $resumeToken
						clientMutationId: $clientMutationId
					}
				) {
					entryId
					entry {
						formFields {
							nodes {
								... on TextField {
									value
								}
							}
						}
					}
  			}
			}
		';

		$variables = [
			'resumeToken'      => $args['resumeToken'] ?? $this->draft_token,
			'clientMutationId' => $this->client_mutation_id,
		];

		return $this->graphql(
			[
				'query'     => $mutation,
				'variables' => $variables,
			]
		);
	}
}
