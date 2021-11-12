<?php
/**
 * Test submitGravityFormsDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

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
	 * Tests `submitGravityFormsDraftEntry`.
	 */
	public function testSubmitGravityFormsDraftEntry() : void {
		$actual = $this->createMutation();
		codecept_debug( $actual );
		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_entry = $this->factory->entry->get_object_by_id( $actual['data']['submitGravityFormsDraftEntry']['entryId'] );

		$this->assertEquals( $actual_entry['id'], $actual['data']['submitGravityFormsDraftEntry']['entryId'] );

		$this->assertEquals( 'value1', $actual['data']['submitGravityFormsDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->factory->entry->delete( $actual['data']['submitGravityFormsDraftEntry']['entryId'] );
	}

	/**
	 * Tests `submitGravityFormsDraftEntry` when a field doesnt validate correctly.
	 *
	 * @TODO .
	 */
	// public function testSubmitGravityFormsDraftEntry_badValue() : void {
	// $draft_token = $this->factory->draft_entry->create(
	// [
	// 'form_id'      => $this->form_id,
	// 'entry'        => [
	// $this->fields[0]['id'] => 'not value1',
	// ],
	// 'field_values' => [
	// 'input_' . $this->fields[0]['id'] => 'not value1',
	// ],
	// ]
	// );

	// $entry = $this->factory->draft_entry->get_object_by_id( $draft_token);
	// codecept_debug($entry);
	// $submission = json_decode($entry['submission'], true);
	// codecept_debug($submission);
	// $actual      = $this->createMutation( [ 'resumeToken' => $draft_token ] );
	// codecept_debug( $actual );
	// $this->assertArrayNotHasKey( 'errors', $actual );

	// $this->factory->draft_entry->delete( $draft_token );
	// $this->factory->entry->delete( $actual['data']['submitGravityFormsDraftEntry']['entryId'] );
	// }

	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function createMutation( array $args = [] ) : array {
		$mutation = '
			mutation submitGravityFormsDraftEntry(
				$resumeToken: String!,
				$clientMutationId: String,
			) {
				submitGravityFormsDraftEntry(
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

		return graphql(
			[
				'query'     => $mutation,
				'variables' => $variables,
			]
		);
	}
}
