<?php
/**
 * Test submitGfDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use GFAPI;

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
		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );

		$expected = $this->factory->entry->get_object_by_id( $actual['data']['submitGfDraftEntry']['entry']['databaseId'] ?? null );

		$this->assertEquals( $expected['id'], $actual['data']['submitGfDraftEntry']['entry']['databaseId'] );

		$this->assertEquals( 'value1', $actual['data']['submitGfDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->assertEquals( 'MESSAGE', $actual['data']['submitGfDraftEntry']['confirmation']['type'] );
		$this->assertNotEmpty( $actual['data']['submitGfDraftEntry']['confirmation']['message'] );
		$this->factory->entry->delete( $expected['id'] );
	}

	public function testSubmitWithURLConfirmation() : void {
		$form = GFAPI::get_form( $this->form_id );

		$confirmation_id = array_keys( $form['confirmations'] )[0];

		$expected_url = 'https://example.com/test';

		$form['confirmations'][ $confirmation_id ]['type']        = 'REDIRECT';
		$form['confirmations'][ $confirmation_id ]['url']         = $expected_url;
		$form['confirmations'][ $confirmation_id ]['queryString'] = 'foo=bar';

		GFAPI::update_form( $form, $this->form_id );

		$query = $this->submit_mutation();

		$variables = [
			'id'     => $this->draft_token,
			'idType' => 'RESUME_TOKEN',
		];

		wp_set_current_user( $this->admin->ID );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );

		$expected = $this->factory->entry->get_object_by_id( $actual['data']['submitGfDraftEntry']['entry']['databaseId'] ?? null );

		$this->assertEquals( $expected['id'], $actual['data']['submitGfDraftEntry']['entry']['databaseId'] );

		$this->assertEquals( 'value1', $actual['data']['submitGfDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->assertEquals( 'REDIRECT', $actual['data']['submitGfDraftEntry']['confirmation']['type'] );
		$this->assertStringContainsString( $expected_url, $actual['data']['submitGfDraftEntry']['confirmation']['url'] );
		$this->factory->entry->delete( $expected['id'] );
	}

	public function testSubmitWithPageConfirmation() : void {
		$form    = GFAPI::get_form( $this->form_id );
		$page_id = $this->factory()->post->create(
			[
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Testing Submit with Page Confirmation',
			]
		);

		$confirmation_id = array_keys( $form['confirmations'] )[0];

		$form['confirmations'][ $confirmation_id ]['type']        = 'page';
		$form['confirmations'][ $confirmation_id ]['pageId']      = $page_id;
		$form['confirmations'][ $confirmation_id ]['queryString'] = 'foo=bar';

		GFAPI::update_form( $form, $this->form_id );

		$query = $this->submit_mutation();

		$variables = [
			'id'     => $this->draft_token,
			'idType' => 'RESUME_TOKEN',
		];

		wp_set_current_user( $this->admin->ID );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );

		$expected = $this->factory->entry->get_object_by_id( $actual['data']['submitGfDraftEntry']['entry']['databaseId'] ?? null );

		$this->assertEquals( $expected['id'], $actual['data']['submitGfDraftEntry']['entry']['databaseId'] );

		$this->assertEquals( 'value1', $actual['data']['submitGfDraftEntry']['entry']['formFields']['nodes'][0]['value'] );
		$this->assertEquals( 'REDIRECT', $actual['data']['submitGfDraftEntry']['confirmation']['type'] );
		$this->assertEquals( $page_id, $actual['data']['submitGfDraftEntry']['confirmation']['pageId'] );
		$this->assertEquals( $page_id, $actual['data']['submitGfDraftEntry']['confirmation']['page']['node']['databaseId'] );

		$this->factory->entry->delete( $expected['id'] );
		wp_delete_post( $page_id, true );
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
						page {
							node {
								databaseId
							}
						}
						pageId
						queryString
					}
				}
			}
		';
	}
}
