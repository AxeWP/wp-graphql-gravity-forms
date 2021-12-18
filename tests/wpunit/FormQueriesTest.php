<?php
/**
 * Test GraphQL Form Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum;

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
		$this->text_area_field_helper = $this->tester->getPropertyHelper( 'TextAreaField', [ 'id' => 2 ] );
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

		$actual = $this->graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $form_id,
					'idType' => 'DATABASE_ID',
				],
			]
		);

		$expected = $this->expected_field_response( $form, $confirmation_key );

		// Test with Database ID.
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );

		// Test with global ID.
		$actual = $this->graphql(
			[
				'query'     => $query,
				'variables' => [
					'id'     => $global_id,
					'idType' => 'ID',
				],
			]
		);
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );
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
			query getForm( $id: ID!, $idType: FormIdTypeEnum ) {
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
					databaseId
					dateCreated
					dateCreatedGmt
					description
					descriptionPlacement
					entryLimits {
						hasLimits
						limitReachedMessage
						limitationPeriod
						maxEntries
					}
					firstPageCssClass
					formFields {
						nodes {
							type
						}
					}
					hasConditionalLogicAnimation
					hasHoneypot
					hasValidationSummary
					id
					isActive
					isTrash
					labelPlacement
					lastPageButton {
						imageUrl
						text
						type
					}
					login {
						isLoginRequired
						loginRequiredMessage
					}
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
						event
						from
						fromName
						id
						isActive
						isAutoformatted
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
						shouldSendAttachments
						subject
						to
						toType
					}
					pagination {
						backgroundColor
						color
						hasProgressbarOnConfirmation
						pageNames
						progressbarCompletionText
						style
						type
					}
					postCreation {
						author {
							databaseId
						}
						authorDatabaseId
						authorId
						categoryDatabaseId
						contentTemplate
						format
						hasContentTemplate
						hasTitleTemplate
						titleTemplate
						status
						shouldUseCurrentUserAsAuthor
					}'
					/*
					quiz {
						failConfirmation {
							isAutoformatted
							message
						}
						grades {
							text
							value
						}
						gradingType
						hasInstantFeedback
						hasLetterConfirmationMessage
						hasPassFailConfirmationMessage
						isShuffleFieldsEnabled
						letterConfirmation {
							isAutoformatted
							message
						}
						maxScore
						passConfirmation {
							isAutoformatted
							message
						}
						passPercent
					}
					*/
					. '
					requiredIndicator
					saveAndContinue {
						buttonText
						isSaveAndContinueEnabled
					}
					scheduling {
						closedMessage
						endDetails {
							amPm
							date
							dateGmt
							hour
							minute
						}
						hasSchedule
						pendingMessage
						startDetails {
							amPm
							date
							dateGmt
							hour
							minute
						}
					}
					subLabelPlacement
					title
					version
				}
			}
		';
	}

	/**
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 * @return array
	 */
	public function expected_field_response( array $form, string $confirmation_key ) : array {
		return [
			$this->expectedObject(
				'gravityFormsForm',
				[
					$this->expectedObject(
						'button',
						[
							$this->get_expected_conditional_logic_fields( $form['button']['conditionalLogic'] ),
							$this->expectedField( 'imageUrl', $form['button']['imageUrl'] ),
							$this->expectedField( 'text', $form['button']['text'] ),
							$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormButtonTypeEnum::$type, $form['button']['type'] ) ),
						]
					),
					$this->expectedObject(
						'confirmations',
						[
							$this->expectedNode(
								'0',
								[
									$this->expectedField( 'id', $form['confirmations'][ $confirmation_key ]['id'] ),
									$this->expectedField( 'isDefault', $form['confirmations'][ $confirmation_key ]['isDefault'] ),
									$this->expectedField( 'message', $form['confirmations'][ $confirmation_key ]['message'] ),
									$this->expectedField( 'name', $form['confirmations'][ $confirmation_key ]['name'] ),
									$this->expectedField( 'pageId', $form['confirmations'][ $confirmation_key ]['pageId'] ),
									$this->expectedField( 'queryString', $form['confirmations'][ $confirmation_key ]['queryString'] ),
									$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormConfirmationTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['type'] ) ),
									$this->expectedField( 'url', $form['confirmations'][ $confirmation_key ]['url'] ),
									$this->get_expected_conditional_logic_fields( $form['confirmations'][ $confirmation_key ]['conditionalLogic'] ),
								],
							),
						]
					),
					$this->expectedField( 'cssClass', $form['cssClass'] ),
					$this->expectedField( 'customRequiredIndicator', $form['customRequiredIndicator'] ),
					$this->expectedField( 'databaseId', $form['id'] ),
					$this->expectedField( 'dateCreated', get_date_from_gmt( $form['date_created'] ) ),
					$this->expectedField( 'dateCreatedGmt', $form['date_created'] ),
					$this->expectedField( 'description', $form['description'] ),
					$this->expectedField( 'descriptionPlacement', $this->tester->get_enum_for_value( Enum\FormDescriptionPlacementEnum::$type, $form['descriptionPlacement'] ) ),
					$this->expectedObject(
						'entryLimits',
						[
							$this->expectedField( 'hasLimits', $form['limitEntries'] ),
							$this->expectedField( 'limitReachedMessage', $form['limitEntriesMessage'] ),
							$this->expectedField( 'limitationPeriod', $this->tester->get_enum_for_value( Enum\FormLimitEntriesPeriodEnum::$type, $form['limitEntriesPeriod'] ) ),
							$this->expectedField( 'maxEntries', $form['limitEntriesCount'] ),
						]
					),
					$this->expectedField( 'firstPageCssClass', $form['firstPageCssClass'] ),
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'0',
								[
									$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormFieldTypeEnum::$type, $form['fields'][0]['type'] ) ),
								]
							),
							$this->expectedNode(
								'1',
								[
									$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormFieldTypeEnum::$type, $form['fields'][1]['type'] ) ),
								]
							),
						]
					),
					$this->expectedField( 'hasConditionalLogicAnimation', $form['enableAnimation'] ),
					$this->expectedField( 'hasHoneypot', $form['enableHoneypot'] ),
					$this->expectedField( 'hasValidationSummary', $form['validationSummary'] ),
					$this->expectedField( 'id', Relay::toGlobalId( 'GravityFormsForm', $form['id'] ) ),
					$this->expectedField( 'isActive', (bool) $form['is_active'] ),
					$this->expectedField( 'isTrash', (bool) $form['is_trash'] ),
					$this->expectedField( 'labelPlacement', $this->tester->get_enum_for_value( Enum\FormLabelPlacementEnum::$type, $form['labelPlacement'] ) ),
					$this->expectedObject(
						'lastPageButton',
						[
							$this->expectedField( 'imageUrl', $form['lastPageButton']['imageUrl'] ),
							$this->expectedField( 'text', $form['lastPageButton']['text'] ),
							$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormButtonTypeEnum::$type, $form['lastPageButton']['type'] ) ),
						]
					),
					$this->expectedObject(
						'login',
						[
							$this->expectedField( 'isLoginRequired', $form['requireLogin'] ),
							$this->expectedField( 'loginRequiredMessage', $form['requireLoginMessage'] ),
						]
					),
					$this->expectedField( 'markupVersion', $form['markupVersion'] ),
					$this->expectedField( 'nextFieldId', $form['nextFieldId'] ),
					$this->expectedObject(
						'notifications',
						[
							$this->expectedNode(
								'0',
								[
									$this->expectedField( 'bcc', $form['notifications']['5cfec9464e529']['bcc'] ),
									$this->get_expected_conditional_logic_fields( $form['notifications']['5cfec9464e529']['conditionalLogic'] ),
									$this->expectedField( 'event', $form['notifications']['5cfec9464e529']['event'] ),
									$this->expectedField( 'from', $form['notifications']['5cfec9464e529']['from'] ),
									$this->expectedField( 'fromName', $form['notifications']['5cfec9464e529']['fromName'] ),
									$this->expectedField( 'id', $form['notifications']['5cfec9464e529']['id'] ),
									$this->expectedField( 'isActive', $form['notifications']['5cfec9464e529']['isActive'] ),
									$this->expectedField( 'isAutoformatted', empty( $form['notifications']['5cfec9464e529']['disableAutoformat'] ) ),
									$this->expectedField( 'message', $form['notifications']['5cfec9464e529']['message'] ),
									$this->expectedField( 'name', $form['notifications']['5cfec9464e529']['name'] ),
									$this->expectedField( 'replyTo', $form['notifications']['5cfec9464e529']['replyTo'] ),
									$this->expectedObject(
										'routing',
										[
											$this->expectedNode(
												'0',
												[
													$this->expectedField( 'email', $form['notifications']['5cfec9464e529']['routing'][0]['email'] ),
													$this->expectedField( 'fieldId', $form['notifications']['5cfec9464e529']['routing'][0]['fieldId'] ),
													$this->expectedField( 'operator', $this->tester->get_enum_for_value( Enum\FormRuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][0]['operator'] ) ),
													$this->expectedField( 'value', $form['notifications']['5cfec9464e529']['routing'][0]['value'] ),
												],
											),
											$this->expectedNode(
												'1',
												[
													$this->expectedField( 'email', $form['notifications']['5cfec9464e529']['routing'][1]['email'] ),
													$this->expectedField( 'fieldId', $form['notifications']['5cfec9464e529']['routing'][1]['fieldId'] ),
													$this->expectedField( 'operator', $this->tester->get_enum_for_value( Enum\FormRuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][1]['operator'] ) ),
													$this->expectedField( 'value', $form['notifications']['5cfec9464e529']['routing'][1]['value'] ),
												],
											),
										]
									),
									$this->expectedField( 'service', $form['notifications']['5cfec9464e529']['service'] ),
									$this->expectedField( 'shouldSendAttachments', ! empty( $form['notifications']['5cfec9464e529']['enableAttachments'] ) ),
									$this->expectedField( 'subject', $form['notifications']['5cfec9464e529']['subject'] ),
									$this->expectedField( 'to', $form['notifications']['5cfec9464e529']['to'] ),
									$this->expectedField( 'toType', $this->tester->get_enum_for_value( Enum\FormNotificationToTypeEnum::$type, $form['notifications']['5cfec9464e529']['toType'] ) ),
								]
							),
						]
					),
					$this->expectedObject(
						'pagination',
						[
							$this->expectedField( 'backgroundColor', $form['pagination']['backgroundColor'] ),
							$this->expectedField( 'color', $form['pagination']['color'] ),
							$this->expectedField( 'hasProgressbarOnConfirmation', ! empty( $form['pagination']['display_progressbar_on_confirmation'] ) ),
							$this->expectedField( 'pageNames', $form['pagination']['pages'] ),
							$this->expectedField( 'progressbarCompletionText', $form['pagination']['progressbar_completion_text'] ),
							$this->expectedField( 'status', $form['postStatus'] ),
							$this->expectedField( 'style', $this->tester->get_enum_for_value( Enum\FormPageProgressStyleEnum::$type, $form['pagination']['style'] ) ),
							$this->expectedField( 'type', $this->tester->get_enum_for_value( Enum\FormPageProgressTypeEnum::$type, $form['pagination']['type'] ) ),
							$this->expectedObject(
								'postCreation',
								[
									$this->expectedObject(
										'author',
										[
											$this->expectedField( 'databaseId', $form['postAuthor'] ),
										]
									),
									$this->expectedField( 'authorDatabaseId', $form['postAuthor'] ),
									$this->expectedField( 'authorId', Relay::toGlobalId( 'user', $form['postAuthor'] ) ),
									$this->expectedField( 'categoryDatabaseId', $form['postCategory'] ),
									$this->expectedField( 'contentTemplate', $form['postContentTemplate'] ),
									$this->expectedField( 'format', $this->tester->get_enum_for_value( Enum\PostFormatTypeEnum::$type, $form['postFormat'] ) ),
									$this->expectedField( 'hasContentTemplate', ! empty( $form['postContentTemplateEnabled'] ) ),
									$this->expectedField( 'hasTitleTemplate', ! empty( $form['postTitleTemplateEnabled'] ) ),
									$this->expectedField( 'titleTemplate', $form['postTitleTemplate'] ),
									$this->expectedField( 'status', $form['postStatus'] ),
									$this->expectedField( 'shouldUseCurrentUserAsAuthor', ! empty( $form['useCurrentUserAsAuthor'] ) ),
									// @todo Quiz fields

								]
							),
							$this->expectedField( 'requiredIndicator', $this->tester->get_enum_for_value( Enum\FormFieldRequiredIndicatorEnum::$type, $form['requiredIndicator'] ) ),
							$this->expectedObject(
								'saveAndContinue',
								[
									$this->expectedField( 'buttonText', $form['save']['button']['text'] ),
									$this->expectedField( 'isSaveAndContinueEnabled', ! empty( $form['save']['button']['enabled'] ) ),
								]
							),
							$this->expectedObject(
								'scheduling',
								[
									$this->expectedField( 'closedMessage', $form['scheduleMessage'] ),
									$this->expectedObject(
										'endDetails',
										[
											$this->expectedField( 'amPm', $form['scheduleEndAmpm'] ),
											$this->expectedField( 'date', get_date_from_gmt( $form['scheduleEnd'] ) ),
											$this->expectedField( 'dateGmt', $form['scheduleEnd'] ),
											$this->expectedField( 'hour', $form['scheduleEndHour'] ),
											$this->expectedField( 'minute', $form['scheduleEndMinute'] ),

										]
									),
									$this->expectedField( 'hasSchedule', ! empty( $form['scheduleForm'] ) ),
									$this->expectedField( 'pendingMessage', $form['schedulePendingMessage'] ),
									$this->expectedObject(
										'startDetails',
										[
											$this->expectedField( 'amPm', $form['scheduleStartAmpm'] ),
											$this->expectedField( 'date', get_date_from_gmt( $form['scheduleStart'] ) ),
											$this->expectedField( 'dateGmt', $form['scheduleStart'] ),
											$this->expectedField( 'hour', $form['scheduleStartHour'] ),
											$this->expectedField( 'minute', $form['scheduleStartMinute'] ),

										]
									),
								]
							),
							$this->expectedField( 'subLabelPlacement', $this->tester->get_enum_for_value( Enum\FormSubLabelPlacementEnum::$type, $form['subLabelPlacement'] ) ),
							$this->expectedField( 'title', $form['title'] ),
							$this->expectedField( 'version', $form['version'] ),
						]
					),
				]
			),
		];
	}
}
