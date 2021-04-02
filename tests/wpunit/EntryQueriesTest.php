<?php

use GraphQLRelay\Relay;
use WPGraphQLGravityForms\Tests\Factories;
use WPGraphQLGravityForms\Types\Enum;

class EntryQueriesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	protected $factory;
	private $admin;
	private $fields = [];
	private $form_id;
	private $entry_ids;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		$this->admin = $this->factory()->user->create_and_get(
			[
				'role' => 'administrator',
			]
		);
		$this->admin->add_cap( 'gravityforms_view_entries' );
		wp_set_current_user( $this->admin->ID );

		$this->factory  = new Factories\Factory();
		$this->fields[] = $this->factory->field->create( $this->tester->getTextFieldDefaultArgs() );
		$this->form_id  = $this->factory->form->create( array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() ) );

		$this->entry_ids = $this->factory->entry->create_many(
			2,
			[
				'form_id'              => $this->form_id,
				'created_by'           => $this->admin->ID,
				$this->fields[0]['id'] => 'This is a default Text entry.',
			]
		);
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function testGravityFormsEntryQuery() {
		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $this->entry_ids[0] );
		$entry     = GFAPI::get_entry( $this->entry_ids[0] );
		$form      = GFAPI::get_form( $this->form_id );

		$query = $this->get_entry_query();

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $this->entry_ids[0],
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
					'node' => [
						'databaseId' => (int) $entry['created_by'],
					],
				],
				'dateCreated' => $entry['date_created'],
				'dateUpdated' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'fields'      => [
					'edges' => [
						[
							'fieldValue' => [
								'value' => $entry[ $form['fields'][0]['id'] ],
							],
							'node'       => [
								'id' => $form['fields'][0]['id'],
							],
						],
					],
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'node' => [
						'formId' => $form['id'],
					],
				],
				'formId'      => $form['id'],
				'id'          => $global_id,
				'ip'          => $entry['ip'],
				'isDraft'     => (bool) null,
				'isRead'      => (bool) $entry['is_read'],
				'isStarred'   => (bool) $entry['is_starred'],
				'postId'      => $entry['post_id'],
				'resumeToken' => null,
				'sourceUrl'   => $entry['source_url'],
				'status'      => $this->tester->get_enum_for_value( Enum\EntryStatusEnum::TYPE, $entry['status'] ),
				'userAgent'   => $entry['user_agent'],
			],
		];
		// Test with Database Id.
		$this->assertEquals( $expected, $actual['data'] );

		// Test with Global Id.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $global_id,
					'idType' => 'ID',
				],
			],
		);

		$this->assertEquals( $expected, $actual['data'] );
	}

	public function testGravityFormsEntriesQuery() {
		$query = '
			query {
				gravityFormsEntries {
					nodes {
						entryId
					}
				}
			}
		';

		$actual = graphql( [ 'query' => $query ] );
		$this->assertEquals( 2, count( $actual['data']['gravityFormsEntries']['nodes'] ) );
	}

	public function testEmptyGravityFormsEntryQuery() {
		$entry_id  = $this->factory->entry->create(
			[ 'form_id' => $this->form_id ]
		);
		$global_id = Relay::toGlobalId( 'GravityFormsEntry', $entry_id );
		$entry     = GFAPI::get_entry( $entry_id );
		$form      = GFAPI::get_form( $this->form_id );
		$query     = $this->get_entry_query();

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $entry_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsEntry' => [
				'createdById' => (int) $entry['created_by'],
				'createdBy'   => [
					'node' => [
						'databaseId' => (int) $entry['created_by'],
					],
				],
				'dateCreated' => $entry['date_created'],
				'dateUpdated' => $entry['date_updated'],
				'entryId'     => (int) $entry['id'],
				'fields'      => [
					'edges' => [
						[
							'fieldValue' => [
								'value' => null,
							],
							'node'       => [
								'id' => $form['fields'][0]['id'],
							],
						],
					],
					'nodes' => [
						[
							'id' => $form['fields'][0]['id'],
						],
					],
				],
				'form'        => [
					'node' => [
						'formId' => $form['id'],
					],
				],
				'formId'      => $form['id'],
				'id'          => $global_id,
				'ip'          => $entry['ip'],
				'isDraft'     => (bool) null,
				'isRead'      => (bool) null,
				'isStarred'   => (bool) null,
				'postId'      => null,
				'resumeToken' => null,
				'sourceUrl'   => $entry['source_url'],
				'status'      => $this->tester->get_enum_for_value( Enum\EntryStatusEnum::TYPE, $entry['status'] ),
				'userAgent'   => $entry['user_agent'],
			],
		];

		$this->assertEquals( $expected, $actual['data'] );
	}

	private function get_entry_query() : string {
		return '
			query getEntry($id: ID!, $idType: IdTypeEnum) {
				gravityFormsEntry(id: $id, idType: $idType) {
					createdById
					createdBy {
						node {
							databaseId
						}
					}
					dateCreated
					dateUpdated
					entryId
					fields {
						edges {
							fieldValue {
								... on TextFieldValue {
									value
								}
							}
							node {
								id
							}
						}
						nodes {
							id
						}
					}
					form {
						node {
							formId
						}
					}
					formId
					id
					ip
					isDraft
					isRead
					isStarred
					postId
					resumeToken
					sourceUrl
					status
					userAgent
				}
			}
		';
	}
}
