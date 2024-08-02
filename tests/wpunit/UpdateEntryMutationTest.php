<?php
/**
 * Test submitGfEntry mutation .
 *
 * @package .
 */

use Tests\WPGraphQL\GF\Helper\GFHelpers\GFHelpers;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;

/**
 * Class - UpdateEntryMutationTest
 */
class UpdateEntryMutationTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
	private $entry_id;
	private $text_field_helper;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		wp_set_current_user( $this->admin->ID );

		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );
		$this->form_id           = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );
		$this->entry_id          = $this->factory->entry->create(
			[
				'form_id'              => $this->form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->form->delete( $this->form_id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `updateGfDraft
	 */
	public function testUpdateGfEntry(): void {
		$query = $this->update_mutation();

		$variables = [
			'id'          => $this->entry_id,
			'idType'      => 'DATABASE_ID',
			'entryMeta'   => [
				'createdById'    => $this->admin->ID,
				'dateCreatedGmt' => '2021-01-01 11:59:59',
				'ip'             => '192.168.0.2',
				'isRead'         => true,
				'isStarred'      => true,
				'sourceUrl'      => 'someSource',
				'status'         => 'TRASH',
				'userAgent'      => 'Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Mobile Safari/537.36',
			],
			'fieldValues' => [
				[
					'id'    => $this->fields[0]['id'],
					'value' => 'value2',
				],
			],
		];

		// Test as guest.
		wp_set_current_user( 0 );
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayHasKey( 'errors', $response, 'Delete without permissions should fail' );

		// Test database ID.
		wp_set_current_user( $this->admin->ID );
		$response = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $response, 'Update  has errors.' );

		$this->assertEquals( $this->toRelayId( EntriesLoader::$name, $this->entry_id ), $response['data']['updateGfEntry']['entry']['id'], 'IDs  dont match' );
		$this->assertEquals( $this->entry_id, $response['data']['updateGfEntry']['entry']['databaseId'], 'DatabaseIds  dont match' );

		$this->assertEquals( $variables['entryMeta']['createdById'], $response['data']['updateGfEntry']['entry']['createdByDatabaseId'], 'Created by doesnt match' );
		$this->assertEquals( $variables['entryMeta']['dateCreatedGmt'], $response['data']['updateGfEntry']['entry']['dateCreatedGmt'], 'Date created doesnt match' );
		$this->assertEquals( $variables['entryMeta']['ip'], $response['data']['updateGfEntry']['entry']['ip'], 'Resume tokens dont match' );
		$this->assertEquals( $variables['entryMeta']['isRead'], $response['data']['updateGfEntry']['entry']['isRead'], 'isRead doesnt match' );
		$this->assertEquals( $variables['entryMeta']['isStarred'], $response['data']['updateGfEntry']['entry']['isStarred'], 'isRead doesnt match' );
		$this->assertEquals( $variables['entryMeta']['sourceUrl'], $response['data']['updateGfEntry']['entry']['sourceUrl'], 'Source Url doesnt match' );
		$this->assertEquals( GFHelpers::get_enum_for_value( EntryStatusEnum::$type, 'trash' ), $response['data']['updateGfEntry']['entry']['status'], 'status doesnt match' );
		$this->assertEquals( $variables['entryMeta']['userAgent'], $response['data']['updateGfEntry']['entry']['userAgent'], 'Source Url doesnt match' );

		$this->assertEquals( $variables['fieldValues'][0]['value'], $response['data']['updateGfEntry']['entry']['formFields']['nodes'][0]['value'], 'Field values dont match' );
	}

	public function update_mutation(): string {
		return '
			mutation updateGfEntry (
				$id: ID!,
				$entryMeta: UpdateEntryMetaInput
				$fieldValues: [FormFieldValuesInput]
			) {
				updateGfEntry (
					input: {
						id: $id
						entryMeta: $entryMeta
						fieldValues: $fieldValues
					}
				) {
					entry {
						createdByDatabaseId
						dateCreatedGmt
						databaseId
						id
						ip
						isRead
						isStarred
						sourceUrl
						status
						userAgent
						formFields {
							nodes {
								... on TextField {
									value
								}
							}
						}
					}
					errors {
						message
						id
					}
  			}
			}
		';
	}
}
