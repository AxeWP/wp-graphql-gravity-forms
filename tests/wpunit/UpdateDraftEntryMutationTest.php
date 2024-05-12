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
	public function testUpdateGfDraftEntry(): void {
		$query = $this->update_mutation();

		$variables = [
			'id'          => $this->draft_token,
			'idType'      => 'RESUME_TOKEN',
			'entryMeta'   => [
				'createdById' => $this->admin->ID,
				'ip'          => '192.168.0.2',
				'sourceUrl'   => 'someSource',
				'userAgent'   => 'Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Mobile Safari/537.36',
			],
			'fieldValues' => [
				[
					'id'    => $this->fields[0]['id'],
					'value' => 'value2',
				],
			],
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Update draft has errors.' );
		$this->assertEquals( $this->toRelayId( DraftEntriesLoader::$name, $this->draft_token ), $response['data']['updateGfDraftEntry']['draftEntry']['id'], 'IDs  dont match' );
		$this->assertEquals( $this->draft_token, $response['data']['updateGfDraftEntry']['draftEntry']['resumeToken'], 'DatabaseIds  dont match' );

		$this->assertEquals( $variables['entryMeta']['createdById'], $response['data']['updateGfDraftEntry']['draftEntry']['createdByDatabaseId'], 'Created by doesnt match' );
		$this->assertEquals( $variables['entryMeta']['ip'], $response['data']['updateGfDraftEntry']['draftEntry']['ip'], 'Resume tokens dont match' );
		$this->assertEquals( $variables['entryMeta']['sourceUrl'], $response['data']['updateGfDraftEntry']['draftEntry']['sourceUrl'], 'Source urls dont match' );
		$this->assertEquals( $variables['entryMeta']['userAgent'], $response['data']['updateGfDraftEntry']['draftEntry']['userAgent'], 'User agent doesnt match' );

		$this->assertEquals( GFUtils::get_resume_url( $this->draft_token, $variables['entryMeta']['sourceUrl'] ), $response['data']['updateGfDraftEntry']['resumeUrl'], 'Field values dont match' );
		$this->assertEquals( $variables['fieldValues'][0]['value'], $response['data']['updateGfDraftEntry']['draftEntry']['formFields']['nodes'][0]['value'], 'Field values dont match' );
	}

	public function update_mutation(): string {
		return '
			mutation updateGfDraftEntry (
				$id: ID!,
				$entryMeta: UpdateDraftEntryMetaInput
				$idType: DraftEntryIdTypeEnum
				$fieldValues: [FormFieldValuesInput]
			) {
				updateGfDraftEntry (
					input: {
						id: $id
						idType: $idType
						entryMeta: $entryMeta
						fieldValues: $fieldValues
					}
				) {
					draftEntry {
						createdByDatabaseId
						id
						ip
						resumeToken
						sourceUrl
						userAgent
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
