<?php
/**
 * Test submitGfDraftEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - UpdateDraftEntryMutationTest
 */
class UpdateDraftEntryMutationTest extends GFGraphQLTestCase {
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
	 * Tests `updateGfDraft
	 */
	public function testUpdateGfDraftEntry() : void {
		$query = $this->update_mutation();

		$variables = [
			'id' => $this->draft_token,
			'idType' => 'RESUME_TOKEN',
			'createdById' => $this->admin->ID,
			'fieldValues' => [[
				'id' => $this->fields[0]['id'],
				'value' => 'value2',
			]],
			'ip' => '192.168.0.2',
			'sourceUrl' => 'someSource'
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Update draft has errors.' );
		$this->assertEquals( $this->toRelayId( DraftEntriesLoader::$name, $this->draft_token ), $response['data']['updateGfDraftEntry']['draftEntry']['id'], 'IDs  dont match' );
		$this->assertEquals( $this->draft_token, $response['data']['updateGfDraftEntry']['draftEntry']['resumeToken'], 'DatabaseIds  dont match' );
		$this->assertEquals( $variables['createdById'], $response['data']['updateGfDraftEntry']['draftEntry']['createdByDatabaseId'], 'Created by doesnt match' );
		$this->assertEquals( $variables['ip'], $response['data']['updateGfDraftEntry']['draftEntry']['ip'], 'Resume tokens dont match' );
		$this->assertEquals( $variables['sourceUrl'], $response['data']['updateGfDraftEntry']['draftEntry']['sourceUrl'], 'Source urls dont match' );
		$this->assertEquals( $variables['fieldValues'][0]['value'], $response['data']['updateGfDraftEntry']['draftEntry']['formFields']['nodes'][0]['value'], 'Field values dont match' );
		$this->assertEquals( GFUtils::get_resume_url( $variables['sourceUrl'], $this->draft_token ), $response['data']['updateGfDraftEntry']['resumeUrl'], 'Field values dont match' );
	}

	public function update_mutation() : string {
		return '
			mutation updateGfDraftEntry (
				$id: ID!,
				$idType: DraftEntryIdTypeEnum
				$createdById: Int
				$fieldValues: [FormFieldValuesInput]
				$ip: String
				$sourceUrl: String
			) {
				updateGfDraftEntry (
					input: {
						id: $id
						idType: $idType
						createdById: $createdById
						fieldValues: $fieldValues
						ip: $ip
						sourceUrl: $sourceUrl
					}
				) {
					draftEntry {
						createdByDatabaseId
						id
						ip
						resumeToken
						sourceUrl
						formFields {
							nodes {
								... on TextField {
									value
								}
							}
						}
					}
					resumeUrl
  			}
			}
		';
	}
}
