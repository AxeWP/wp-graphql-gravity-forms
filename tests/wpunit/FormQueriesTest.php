<?php
/**
 * Test GraphQL Form Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Types\Enum;

/**
 * Class - FormQueriesTest
 */
class FormQueriesTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_ids;
	private $text_field_helper;
	private $text_area_field_helper;

	/**
	 * Run before each test.
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
		// Text field.
		$this->text_field_helper = $this->tester->getPropertyHelper( 'TextField' );
		$this->fields[]          = $this->factory->field->create( $this->text_field_helper->values );
		// TextAreaField.
		$this->text_area_field_helper = $this->tester->getPropertyHelper( 'TextField', [ 'id' => 2 ] );
		$this->fields[]               = $this->factory->field->create( $this->text_area_field_helper->values );
		// Form.
		$this->form_ids = $this->factory->form->create_many(
			6,
			array_merge( [ 'fields' => $this->fields ], $this->tester->getFormDefaultArgs() )
		);
		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->form->delete( $this->form_ids );

		// Then...
		parent::tearDown();
	}

	/**
	 * Tests `gravityFormsForm`.
	 */
	public function testFormQuery() : void {
		$form_id          = $this->form_ids[0];
		$global_id        = Relay::toGlobalId( 'GravityFormsForm', $form_id );
		$form             = GFAPI::get_form( $form_id );
		$confirmation_key = key( $form['confirmations'] );

		$query = $this->get_form_query();

		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $form_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = [
			'gravityFormsForm' => [
				'button'                     => [
					'conditionalLogic' => [
						'actionType' => $this->tester->get_enum_for_value( Enum\ConditionalLogicActionTypeEnum::$type, $form['button']['conditionalLogic']['actionType'] ),
						'logicType'  => $this->tester->get_enum_for_value( Enum\ConditionalLogicLogicTypeEnum::$type, $form['button']['conditionalLogic']['logicType'] ),
						'rules'      => [
							[
								'fieldId'  => $form['button']['conditionalLogic']['rules'][0]['fieldId'],
								'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['button']['conditionalLogic']['rules'][0]['operator'] ),
								'value'    => $form['button']['conditionalLogic']['rules'][0]['value'],
							],
						],
					],
					'imageUrl'         => $form['button']['imageUrl'],
					'text'             => $form['button']['text'],
					'type'             => $this->tester->get_enum_for_value( Enum\ButtonTypeEnum::$type, $form['button']['type'] ),
				],
				'confirmations'              => [
					[
						'id'               => $form['confirmations'][ $confirmation_key ]['id'],
						'isDefault'        => $form['confirmations'][ $confirmation_key ]['isDefault'],
						'message'          => $form['confirmations'][ $confirmation_key ]['message'],
						'name'             => $form['confirmations'][ $confirmation_key ]['name'],
						'pageId'           => $form['confirmations'][ $confirmation_key ]['pageId'],
						'queryString'      => $form['confirmations'][ $confirmation_key ]['queryString'],
						'type'             => $this->tester->get_enum_for_value( Enum\ConfirmationTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['type'] ),
						'url'              => $form['confirmations'][ $confirmation_key ]['url'],
						'conditionalLogic' => [
							'actionType' => $this->tester->get_enum_for_value( Enum\ConditionalLogicActionTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['conditionalLogic']['actionType'] ),
							'logicType'  => $this->tester->get_enum_for_value( Enum\ConditionalLogicLogicTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['conditionalLogic']['logicType'] ),
							'rules'      => [
								[
									'fieldId'  => $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][0]['fieldId'],
									'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][0]['operator'] ),
									'value'    => $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][0]['value'],
								],
								[
									'fieldId'  => $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][1]['fieldId'],
									'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][1]['operator'] ),
									'value'    => $form['confirmations'][ $confirmation_key ]['conditionalLogic']['rules'][1]['value'],
								],
							],
						],
					],
				],
				'cssClass'                   => $form['cssClass'],
				'customRequiredIndicator'    => $form['customRequiredIndicator'],
				'dateCreated'                => $form['date_created'],
				'description'                => $form['description'],
				'descriptionPlacement'       => $this->tester->get_enum_for_value( Enum\FormDescriptionPlacementEnum::$type, $form['descriptionPlacement'] ),
				'enableAnimation'            => $form['enableAnimation'],
				'enableHoneypot'             => $form['enableHoneypot'],
				'formFields'                 => [
					'nodes' => [
						[ 'type' => $form['fields'][0]['type'] ],
						[ 'type' => $form['fields'][1]['type'] ],
					],
				],
				'firstPageCssClass'          => $form['firstPageCssClass'],
				'formId'                     => $form['id'],
				'id'                         => $global_id,
				'isActive'                   => (bool) $form['is_active'],
				'isTrash'                    => (bool) $form['is_trash'],
				'labelPlacement'             => $this->tester->get_enum_for_value( Enum\FormLabelPlacementEnum::$type, $form['labelPlacement'] ),
				'lastPageButton'             => [
					'imageUrl' => $form['lastPageButton']['imageUrl'],
					'text'     => $form['lastPageButton']['text'],
					'type'     => $this->tester->get_enum_for_value( Enum\ButtonTypeEnum::$type, $form['lastPageButton']['type'] ),
				],
				'limitEntries'               => $form['limitEntries'],
				'limitEntriesCount'          => $form['limitEntriesCount'],
				'limitEntriesMessage'        => $form['limitEntriesMessage'],
				'limitEntriesPeriod'         => $this->tester->get_enum_for_value( Enum\FormLimitEntriesPeriodEnum::$type, $form['limitEntriesPeriod'] ),
				'markupVersion'              => $form['markupVersion'],
				'nextFieldId'                => $form['nextFieldId'],
				'notifications'              => [
					[
						'bcc'               => $form['notifications']['5cfec9464e529']['bcc'],
						'conditionalLogic'  => [
							'actionType' => $this->tester->get_enum_for_value( Enum\ConditionalLogicActionTypeEnum::$type, $form['notifications']['5cfec9464e529']['conditionalLogic']['actionType'] ),
							'logicType'  => $this->tester->get_enum_for_value( Enum\ConditionalLogicLogicTypeEnum::$type, $form['notifications']['5cfec9464e529']['conditionalLogic']['logicType'] ),

							'rules'      => [
								[
									'fieldId'  => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['fieldId'],
									'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['operator'] ),
									'value'    => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['value'],
								],
								[
									'fieldId'  => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['fieldId'],
									'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['operator'] ),
									'value'    => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['value'],
								],
							],
						],
						'disableAutoformat' => $form['notifications']['5cfec9464e529']['disableAutoformat'],
						'enableAttachments' => $form['notifications']['5cfec9464e529']['enableAttachments'],
						'event'             => $form['notifications']['5cfec9464e529']['event'],
						'from'              => $form['notifications']['5cfec9464e529']['from'],
						'fromName'          => $form['notifications']['5cfec9464e529']['fromName'],
						'id'                => $form['notifications']['5cfec9464e529']['id'],
						'isActive'          => $form['notifications']['5cfec9464e529']['isActive'],
						'message'           => $form['notifications']['5cfec9464e529']['message'],
						'name'              => $form['notifications']['5cfec9464e529']['name'],
						'replyTo'           => $form['notifications']['5cfec9464e529']['replyTo'],
						'routing'           => [
							[
								'fieldId'  => $form['notifications']['5cfec9464e529']['routing'][0]['fieldId'],
								'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][0]['operator'] ),
								'value'    => $form['notifications']['5cfec9464e529']['routing'][0]['value'],
								'email'    => $form['notifications']['5cfec9464e529']['routing'][0]['email'],
							],
							[
								'fieldId'  => $form['notifications']['5cfec9464e529']['routing'][1]['fieldId'],
								'operator' => $this->tester->get_enum_for_value( Enum\RuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][1]['operator'] ),
								'value'    => $form['notifications']['5cfec9464e529']['routing'][1]['value'],
								'email'    => $form['notifications']['5cfec9464e529']['routing'][1]['email'],
							],
						],
						'service'           => $form['notifications']['5cfec9464e529']['service'],
						'subject'           => $form['notifications']['5cfec9464e529']['subject'],
						'to'                => $form['notifications']['5cfec9464e529']['to'],
						'toType'            => $this->tester->get_enum_for_value( Enum\NotificationToTypeEnum::$type, $form['notifications']['5cfec9464e529']['toType'] ),
					],
				],
				'pagination'                 => [
					'backgroundColor'                  => $form['pagination']['backgroundColor'],
					'color'                            => $form['pagination']['color'],
					'displayProgressbarOnConfirmation' => $form['pagination']['display_progressbar_on_confirmation'],
					'pages'                            => $form['pagination']['pages'],
					'progressbarCompletionText'        => $form['pagination']['progressbar_completion_text'],
					'style'                            => $this->tester->get_enum_for_value( Enum\PageProgressStyleEnum::$type, $form['pagination']['style'] ),
					'type'                             => $this->tester->get_enum_for_value( Enum\PageProgressTypeEnum::$type, $form['pagination']['type'] ),
				],
				'postAuthor'                 => $form['postAuthor'],
				'postCategory'               => $form['postCategory'],
				'postContentTemplate'        => $form['postContentTemplate'],
				'postContentTemplateEnabled' => $form['postContentTemplateEnabled'],
				'postFormat'                 => $form['postFormat'],
				'postStatus'                 => $form['postStatus'],
				'postTitleTemplate'          => $form['postTitleTemplate'],
				'postTitleTemplateEnabled'   => $form['postTitleTemplateEnabled'],
				'requiredIndicator'          => $this->tester->get_enum_for_value( Enum\RequiredIndicatorEnum::$type, $form['requiredIndicator'] ),
				'requireLogin'               => $form['requireLogin'],
				'requireLoginMessage'        => $form['requireLoginMessage'],
				'save'                       => [
					'buttonText' => $form['save']['button']['text'],
					'enabled'    => $form['save']['enabled'],
				],
				'scheduleEnd'                => $form['scheduleEnd'],
				'scheduleEndAmpm'            => $form['scheduleEndAmpm'],
				'scheduleEndHour'            => $form['scheduleEndHour'],
				'scheduleEndMinute'          => $form['scheduleEndMinute'],
				'scheduleForm'               => $form['scheduleForm'],
				'scheduleMessage'            => $form['scheduleMessage'],
				'schedulePendingMessage'     => $form['schedulePendingMessage'],
				'scheduleStart'              => $form['scheduleStart'],
				'scheduleStartAmpm'          => $form['scheduleStartAmpm'],
				'scheduleStartHour'          => $form['scheduleStartHour'],
				'scheduleStartMinute'        => $form['scheduleStartMinute'],
				'subLabelPlacement'          => $this->tester->get_enum_for_value( Enum\FormSubLabelPlacementEnum::$type, $form['subLabelPlacement'] ),
				'title'                      => $form['title'],
				'useCurrentUserAsAuthor'     => $form['useCurrentUserAsAuthor'],
				'validationSummary'          => $form['validationSummary'],
				'version'                    => $form['version'],
			],
		];

		// Test with Database ID.
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );

		// Test with global ID.
		$actual = graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $global_id,
					'idType' => 'ID',
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertEquals( $expected, $actual['data'] );
	}

	/**
	 * Test `gravityFormsForm` with no setup variables.
	 */
	public function testFormQuery_empty() : void {
		$form_id          = $this->factory->form->create( [ 'fields' => [] ] );
		$global_id        = Relay::toGlobalId( 'GravityFormsForm', $form_id );
		$form             = GFAPI::get_form( $form_id );
		$confirmation_key = key( $form['confirmations'] );

		$query     = $this->get_form_query();
		$variables = [
			'id'     => $form_id,
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected =
			[
				'gravityFormsForm' => [
					'button'                     => null,
					'confirmations'              => [
						[
							'id'               => $form['confirmations'][ $confirmation_key ]['id'],
							'isDefault'        => $form['confirmations'][ $confirmation_key ]['isDefault'],
							'message'          => $form['confirmations'][ $confirmation_key ]['message'],
							'name'             => $form['confirmations'][ $confirmation_key ]['name'],
							'pageId'           => $form['confirmations'][ $confirmation_key ]['pageId'],
							'queryString'      => $form['confirmations'][ $confirmation_key ]['queryString'],
							'type'             => $this->tester->get_enum_for_value( Enum\ConfirmationTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['type'] ),
							'url'              => $form['confirmations'][ $confirmation_key ]['url'],
							'conditionalLogic' => null,
						],
					],
					'cssClass'                   => null,
					'customRequiredIndicator'    => null,
					'dateCreated'                => $form['date_created'],
					'description'                => $form['description'],
					'descriptionPlacement'       => null,
					'enableAnimation'            => null,
					'enableHoneypot'             => null,
					'formFields'                 => [
						'nodes' => null,
					],
					'firstPageCssClass'          => null,
					'formId'                     => $form['id'],
					'id'                         => $global_id,
					'isActive'                   => (bool) $form['is_active'],
					'isTrash'                    => (bool) $form['is_trash'],
					'labelPlacement'             => null,
					'lastPageButton'             => null,
					'limitEntries'               => null,
					'limitEntriesCount'          => 0,
					'limitEntriesMessage'        => null,
					'limitEntriesPeriod'         => null,
					'markupVersion'              => $form['markupVersion'] ?? null,
					'nextFieldId'                => $form['nextFieldId'],
					'notifications'              => [],
					'pagination'                 => null,
					'postAuthor'                 => null,
					'postCategory'               => null,
					'postContentTemplate'        => null,
					'postContentTemplateEnabled' => null,
					'postFormat'                 => null,
					'postStatus'                 => null,
					'postTitleTemplate'          => null,
					'postTitleTemplateEnabled'   => null,
					'requiredIndicator'          => null,
					'requireLogin'               => null,
					'requireLoginMessage'        => null,
					'save'                       => null,
					'scheduleEnd'                => null,
					'scheduleEndAmpm'            => null,
					'scheduleEndHour'            => null,
					'scheduleEndMinute'          => null,
					'scheduleForm'               => null,
					'scheduleMessage'            => null,
					'schedulePendingMessage'     => null,
					'scheduleStart'              => null,
					'scheduleStartAmpm'          => null,
					'scheduleStartHour'          => null,
					'scheduleStartMinute'        => null,
					'subLabelPlacement'          => null,
					'title'                      => $form['title'],
					'useCurrentUserAsAuthor'     => null,
					'validationSummary'          => null,
					'version'                    => null,
				],
			];

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( $expected, $response['data'] );

		$this->factory->form->delete( $form_id );
	}

	/**
	 * Test `gravityFormsForms`.
	 */
	public function testFormsQuery() : void {
		$query = '
			query {
				gravityFormsForms {
					nodes {
						formId
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertCount( 6, $response['data']['gravityFormsForms']['nodes'] );
	}

	/**
	 * Test `gravityFormsForms` with query args.
	 */
	public function testForms_queryArgs() {
		// Get form ids in DESC order.
		$form_ids = array_reverse( $this->form_ids );

		$query = '
			query( $first: Int, $after: String, $last:Int, $before: String ) {
				gravityFormsForms(first: $first, after: $after, last: $last, before: $before) {
					pageInfo{
						hasNextPage
						hasPreviousPage
						startCursor
						endCursor
					}
					edges {
						cursor
						node {
							formId
						}
					}
					nodes {
						formId
					}
				}
			}
		';

		$variables = [
			'first'  => 2,
			'after'  => null,
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		// Check `first` argument.
		$this->assertArrayNotHasKey( 'errors', $response, 'First array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'First does not return correct amount.' );

		$this->assertSame( $form_ids[0], $response['data']['gravityFormsForms']['nodes'][0]['formId'], 'First - node 0 is not same.' );
		$this->assertSame( $form_ids[1], $response['data']['gravityFormsForms']['nodes'][1]['formId'], 'First - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'First does not have next page.' );
		$this->assertFalse( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'First has previous page.' );

		// Check `after` argument.
		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gravityFormsForms']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #1 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'First/after #1 does not return correct amount.' );
		$this->assertSame( $form_ids[2], $response['data']['gravityFormsForms']['nodes'][0]['formId'], 'First/after #1 - node 0 is not same.' );
		$this->assertSame( $form_ids[3], $response['data']['gravityFormsForms']['nodes'][1]['formId'], 'First/after #1- node 1 is not same.' );

		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gravityFormsForms']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'First/after #2 does not return correct amount.' );
		$this->assertSame( $form_ids[4], $response['data']['gravityFormsForms']['nodes'][0]['formId'], 'First/after #2 - node 0 is not same' );
		$this->assertSame( $form_ids[5], $response['data']['gravityFormsForms']['nodes'][1]['formId'], 'First/after #2 - node 1 is not same.' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'First/after #1 does not have next page.' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'First/after #1 does not have previous page.' );

		$variables = [
			'first'  => 2,
			'after'  => $response['data']['gravityFormsForms']['pageInfo']['endCursor'],
			'last'   => null,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'First/after #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'First/after #2 does not return correct amount.' );
		$this->assertSame( $form_ids[4], $response['data']['gravityFormsForms']['nodes'][0]['entryId'], 'First/after #2 - node 0 is not same' );
		$this->assertSame( $form_ids[5], $response['data']['gravityFormsForms']['nodes'][1]['entryId'], 'First/after #2 - node 1 is not same.' );
		$this->assertFalse( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'First/after #2 has next page.' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'First/after #2 does not have previous page.' );

		// Check last argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => null,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'Last does not return correct amount.' );
		$this->assertSame( $form_ids[4], $response['data']['gravityFormsForms']['nodes'][0]['formId'], 'Last - node 0 is not same' );
		$this->assertSame( $form_ids[5], $response['data']['gravityFormsForms']['nodes'][1]['formId'], 'Last - node 1 is not same.' );
		$this->assertFalse( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'Last has next page.' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'Last does not have previous page.' );

		// Check `before` argument.
		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gravityFormsForms']['pageInfo']['startCursor'],
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last array has errors.' );

		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'last/before #1 does not return correct amount.' );
		$this->assertSame( $form_ids[2], $response['data']['gravityFormsForms']['nodes'][0]['formId'], 'last/before #1 - node 0 is not same' );
		$this->assertSame( $form_ids[3], $response['data']['gravityFormsForms']['nodes'][1]['formId'], 'last/before #1 - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'Last/before #1 does not have next page.' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'Last/before #1 does not have previous page.' );

		$variables = [
			'first'  => null,
			'after'  => null,
			'last'   => 2,
			'before' => $response['data']['gravityFormsForms']['pageInfo']['endCursor'],
		];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Last/before #2 array has errors.' );
		$this->assertCount( 2, $response['data']['gravityFormsForms']['nodes'], 'last/before does not return correct amount.' );
		$this->assertSame( $form_ids[0], $response['data']['gravityFormsForms']['nodes'][0]['entryId'], 'last/before #2 - node 0 is not same' );
		$this->assertSame( $form_ids[1], $response['data']['gravityFormsForms']['nodes'][1]['entryId'], 'last/before #2 - node 1 is not same' );
		$this->assertTrue( $response['data']['gravityFormsForms']['pageInfo']['hasNextPage'], 'Last/before #2 does not have next page.' );
		$this->assertFalse( $response['data']['gravityFormsForms']['pageInfo']['hasPreviousPage'], 'Last/before #2 has previous page.' );

		// Check `where.status` argument.

		// Deactivate.
		$this->factory->form->update_object( $form_ids[0], [ 'is_active' => 0 ] );
		$this->factory->form->update_object( $form_ids[1], [ 'is_active' => 0 ] );
		// Trash.
		$this->factory->form->update_object( $form_ids[4], [ 'is_trash' => 1 ] );
		$this->factory->form->update_object( $form_ids[5], [ 'is_trash' => 1 ] );
		// Trash & Deactivate.
		$this->factory->form->update_object(
			$form_ids[2],
			[
				'is_active' => 0,
				'is_trash'  => 1,
			]
		);
		$this->factory->form->update_object(
			$form_ids[3],
			[
				'is_active' => 0,
				'is_trash'  => 1,
			]
		);

		$query = '
			query {
				inactive: gravityFormsForms(where: {status: INACTIVE}) {
					nodes {
						formId
						isActive
						isTrash
					}
				}
				trashed: gravityFormsForms(where: {status: TRASHED}) {
					nodes {
						formId
						isActive
						isTrash
					}
				}
				inactive_trashed: gravityFormsForms(where: {status: INACTIVE_TRASHED}) {
					nodes {
						formId
						isActive
						isTrash
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );

		$this->assertArrayNotHasKey( 'errors', $response, 'Status query has errors.' );
		// Test inactive.
		$this->assertCount( 2, $response['data']['inactive']['nodes'] );
		$this->assertFalse( $response['data']['inactive']['nodes'][0]['isActive'] );
		$this->assertFalse( $response['data']['inactive']['nodes'][0]['isTrash'] );
		// Test trashed.
		$this->assertCount( 2, $response['data']['trashed']['nodes'] );
		$this->assertTrue( $response['data']['trashed']['nodes'][0]['isActive'] );
		$this->assertTrue( $response['data']['trashed']['nodes'][0]['isTrash'] );
		// Test inactive_trashed.
		$this->assertCount( 2, $response['data']['inactive_trashed']['nodes'] );
		$this->assertFalse( $response['data']['inactive_trashed']['nodes'][0]['isActive'] );
		$this->assertTrue( $response['data']['inactive_trashed']['nodes'][0]['isTrash'] );

		// Test where.sort argument.
		$query = '
			query {
				gravityFormsForms( where: { sort: { key: "id", direction: DESC }, status:INACTIVE_TRASHED } ) {
					nodes {
						formId
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertGreaterThan( $response['data']['gravityFormsForms']['nodes'][1]['formId'], $response['data']['gravityFormsForms']['nodes'][0]['formId'] );
	}

	/**
	 * Returns the full form query for reuse.
	 */
	private function get_form_query() : string {
		return '
			query getForm( $id: ID!, $idType: IdTypeEnum ) {
				gravityFormsForm( id: $id, idType: $idType ) {
					button {
						conditionalLogic {
							actionType
							logicType
							rules {
								fieldId
								operator
								value
							}
						}
						imageUrl
						text
						type
					}
					confirmations {
						id
						isDefault
						message
						name
						pageId
						queryString
						type
						url
						conditionalLogic {
							actionType
							logicType
							rules {
								fieldId
								operator
								value
							}
						}
					}
					cssClass
					customRequiredIndicator
					dateCreated
					description
					descriptionPlacement
					enableAnimation
					enableHoneypot
					formFields {
						nodes {
							type
						}
					}
					firstPageCssClass
					formId
					id
					isActive
					isTrash
					labelPlacement
					lastPageButton {
						imageUrl
						text
						type
					}
					limitEntries
					limitEntriesCount
					limitEntriesMessage
					limitEntriesPeriod
					markupVersion
					nextFieldId
					notifications {
						bcc
						conditionalLogic {
							actionType
							logicType
							rules {
								fieldId
								operator
								value
							}
						}
						disableAutoformat
						enableAttachments
						event
						from
						fromName
						id
						isActive
						message
						name
						replyTo
						routing {
							email
							fieldId
							operator
							value
						}
						service
						subject
						to
						toType
					}
					pagination {
						backgroundColor
						color
						displayProgressbarOnConfirmation
						pages
						progressbarCompletionText
						style
						type
					}
					postAuthor
					postCategory
					postContentTemplate
					postContentTemplateEnabled
					postFormat
					postStatus
					postTitleTemplate
					postTitleTemplateEnabled
					requiredIndicator
					requireLogin
					requireLoginMessage
					save {
						buttonText
						enabled
					}
					scheduleEnd
					scheduleEndAmpm
					scheduleEndHour
					scheduleEndMinute
					scheduleForm
					scheduleMessage
					schedulePendingMessage
					scheduleStart
					scheduleStartAmpm
					scheduleStartHour
					scheduleStartMinute
					subLabelPlacement
					title
					useCurrentUserAsAuthor
					validationSummary
					version
				}
			}
		';
	}
}
