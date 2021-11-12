<?php
/**
 * Test createGravityFormsDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GravityForms\TestCase\GFGraphQLTestCase;

/**
 * Class - CreateDraftEntryMutationTest
 */
class CreateDraftEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $client_mutation_id;
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

		$this->form_id            = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
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
	 * Tests `createGravityFormsDraftEntry`.
	 */
	public function testCreateGravityFormsDraftEntry() : void {
		$actual = $this->createMutation(
			[
				'fromEntryId' => null,
				'ip'          => '192.168.0.1',
				'pageNumber'  => 2,
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertIsString( $actual['data']['createGravityFormsDraftEntry']['resumeToken'] );

		$actual_draft      = $this->factory->draft_entry->get_object_by_id( $actual['data']['createGravityFormsDraftEntry']['resumeToken'] );
		$actual_submission = json_decode( $actual_draft['submission'], true );

		$this->assertEquals( $this->form_id, $actual_draft['form_id'] );
		$this->assertEquals( '192.168.0.1', $actual_submission['partial_entry']['ip'] );
		$this->assertEquals( 2, $actual_submission['page_number'] );

		$this->factory->draft_entry->delete( $actual['data']['createGravityFormsDraftEntry']['resumeToken'] );
	}

	/**
	 * Tests `createGravityFormsDraftEntry` from an existing entry.
	 */
	public function testCreateGravityFormsDraftEntry_fromEntry() : void {
		$entry_id = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				'created_by'           => 1,
				'source_url'           => 'test.com',
				$this->fields[0]['id'] => 'This is a default text entry',
			]
		);

		$actual = $this->createMutation(
			[
				'fromEntryId' => $entry_id,
				'ip'          => null,
				'pageNumber'  => null,
			]
		);

		$this->assertArrayNotHasKey( 'errors', $actual );

		$actual_draft = $this->factory->draft_entry->get_object_by_id( $actual['data']['createGravityFormsDraftEntry']['resumeToken'] );

		$actual_submission = json_decode( $actual_draft['submission'], true );

		$this->assertEquals( 'This is a default text entry', $actual_submission['partial_entry'][ $this->fields[0]['id'] ] );

		$this->factory->draft_entry->delete( $actual['data']['createGravityFormsDraftEntry']['resumeToken'] );
		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Creates the mutation.
	 *
	 * @param array $args .
	 */
	public function createMutation( array $args ) : array {
		$mutation = '
			mutation createGravityFormsDraftEntry(
				$id: Int!,
				$clientMutationId: String,
				$fromEntryId: Int,
				$ip: String,
				$pageNumber: Int,
			) {
				createGravityFormsDraftEntry(
					input: {
						formId: $id
						clientMutationId: $clientMutationId
						fromEntryId: $fromEntryId
						ip: $ip
						pageNumber: $pageNumber
					}
				) {
					clientMutationId
					resumeToken
					resumeUrl
  			}
			}
		';

		$variables = [
			'id'               => (int) $this->form_id,
			'clientMutationId' => $this->client_mutation_id,
			'fromEntryId'      => $args['fromEntryId'],
			'ip'               => $args['ip'],
			'pageNumber'       => $args['pageNumber'],
		];

		return graphql(
			[
				'query'     => $mutation,
				'variables' => $variables,
			]
		);
	}


}
