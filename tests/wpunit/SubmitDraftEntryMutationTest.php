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
	private $text_field_helper;


	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id           = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->draft_token       = $this->factory->draft_entry->create(
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
		$query = $this->submit_mutation();

		$variables = [
			'id'     => $this->draft_token,
			'idType' => 'RESUME_TOKEN',
		];

		wp_set_current_user( $this->admin->ID );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );

		$actual_entry = $this->factory->entry->get_object_by_id( $response['data']['submitGfDraftEntry']['entry']['databaseId'] ?? null );

		$this->assertEquals( $actual_entry['id'], $response['data']['submitGfDraftEntry']['entry']['databaseId'] );

		$this->assertEquals( 'value1', $response['data']['submitGfDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->assertEquals( "MESSAGE", $response['data']['submitGfDraftEntry']['confirmation']['type'] );
		$this->assertNotEmpty( $response['data']['submitGfDraftEntry']['confirmation']['message'] );
		$this->factory->entry->delete( $actual_entry['id'] );
	}

	/**
	 * Creates the mutation.
	 */
	public function submit_mutation() : string {
		return '
			mutation submitGfDraftEntry (
				$id: ID!
				$idType: DraftEntryIdTypeEnum
			) {
				submitGfDraftEntry (
					input: {
						id: $id
						idType: $idType
					}
				) {
					entry {
						databaseId
						formFields {
							nodes {
								... on TextField {
									value
								}
							}
						}
					}
					errors {
						id
						message
					}
					confirmation {
						message
						type
						url
					}
				}
			}
		';
	}
}
