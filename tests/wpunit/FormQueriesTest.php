<?php
/**
 * Test GraphQL Form Queries.
 *
 * @package .
 */

use GraphQLRelay\Relay;
use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Extensions\GFQuiz\Type\Enum as QuizEnum;
use Helper\GFHelpers\GFHelpers;
use WPGraphQL\GF\Data\Loader\FormsLoader;

/**
 * Class - FormQueriesTest
 */
class FormQueriesTest extends GFGraphQLTestCase {
	private $fields = [];
	private $form_id;
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
		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);
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
	 * Tests `gfForm`.
	 */
	public function testFormQuery() : void {
		$global_id        = Relay::toGlobalId( FormsLoader::$name, $this->form_id );
		$form             = GFAPI::get_form( $this->form_id );
		$confirmation_key = key( $form['confirmations'] );

		$query = $this->get_form_query();

		// Test with bad ID.
		$variables = [
			'id'     => 99999999,
			'idType' => 'DATABASE_ID',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertNull( $actual['data']['gfForm'] );

		// Test with Database ID.
		$variables['id'] = $this->form_id;
		$expected        = $this->expected_field_response( $form, $confirmation_key );

		$actual = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );

				// Test with bad global ID.
		$variables = [
			'id'     => 'not-a-real-id',
			'idType' => 'ID',
		];

		$actual = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayHasKey( 'errors', $actual );

		// Test with Global Id.
		$variables['id'] = $global_id;
		$actual          = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $actual );
		$this->assertQuerySuccessful( $actual, $expected );
	}

	/**
	 * Returns the full form query for reuse.
	 */
	private function get_form_query() : string {
		return '
			query getForm( $id: ID!, $idType: FormIdTypeEnum ) {
				gfForm( id: $id, idType: $idType ) {
					confirmations {
						id
						isActive
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
						hasLimit
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
						lastPageButton {
							imageUrl
							text
							type
						}
						pageNames
						progressbarCompletionText
						style
						type
					}
					personalData {
						daysToRetain
						retentionPolicy
						shouldSaveIP
						dataPolicies {
							canExportAndErase
							identificationFieldDatabaseId
							entryData {
								key
								shouldErase
								shouldExport
							}
						}
					}
					postCreation {
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
					}
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
					requiredIndicator
					saveAndContinue {
						buttonText
						hasSaveAndContinue
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
					submitButton {
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
						layoutGridColumnSpan
						location
						text
						type
						width
					}
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
				'gfForm',
				[
					$this->expectedObject(
						'submitButton',
						[
							$this->get_expected_conditional_logic_fields( $form['button']['conditionalLogic'] ?? [] ),
							$this->expectedField( 'imageUrl', $form['button']['imageUrl'] ),
							$this->expectedField( 'layoutGridColumnSpan', (int) $form['button']['layoutGridColumnSpan'] ),
							$this->expectedField( 'location', GFHelpers::get_enum_for_value( Enum\FormSubmitButtonLocationEnum::$type, $form['button']['location'] ) ),
							$this->expectedField( 'text', $form['button']['text'] ),
							$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormButtonTypeEnum::$type, $form['button']['type'] ) ),
							$this->expectedField( 'width', GFHelpers::get_enum_for_value( Enum\FormSubmitButtonWidthEnum::$type, $form['button']['width'] ) ),
						]
					),
					$this->expectedNode(
						'confirmations',
						[
							$this->expectedField( 'id', $form['confirmations'][ $confirmation_key ]['id'] ),
							$this->expectedField( 'isActive', true ),
							$this->expectedField( 'isDefault', $form['confirmations'][ $confirmation_key ]['isDefault'] ),
							$this->expectedField( 'message', $form['confirmations'][ $confirmation_key ]['message'] ),
							$this->expectedField( 'name', $form['confirmations'][ $confirmation_key ]['name'] ),
							$this->expectedField( 'pageId', $form['confirmations'][ $confirmation_key ]['pageId'] ?? static::IS_NULL ),
							$this->expectedField( 'queryString', $form['confirmations'][ $confirmation_key ]['queryString'] ),
							$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormConfirmationTypeEnum::$type, $form['confirmations'][ $confirmation_key ]['type'] ) ),
							$this->expectedField( 'url', $form['confirmations'][ $confirmation_key ]['url'] ),
							$this->get_expected_conditional_logic_fields( $form['confirmations'][ $confirmation_key ]['conditionalLogic'] ?? [] ),
						],
						0
					),
					$this->expectedField( 'cssClass', $form['cssClass'] ),
					$this->expectedField( 'customRequiredIndicator', $form['customRequiredIndicator'] ),
					$this->expectedField( 'databaseId', $form['id'] ),
					$this->expectedField( 'dateCreated', get_date_from_gmt( $form['date_created'] ) ),
					$this->expectedField( 'dateCreatedGmt', $form['date_created'] ),
					$this->expectedField( 'description', $form['description'] ),
					$this->expectedField( 'descriptionPlacement', GFHelpers::get_enum_for_value( Enum\FormDescriptionPlacementEnum::$type, $form['descriptionPlacement'] ) ),
					$this->expectedObject(
						'entryLimits',
						[
							$this->expectedField( 'hasLimit', ! empty( $form['limitEntries'] ) ),
							$this->expectedField( 'limitReachedMessage', $form['limitEntriesMessage'] ),
							$this->expectedField( 'limitationPeriod', GFHelpers::get_enum_for_value( Enum\FormLimitEntriesPeriodEnum::$type, $form['limitEntriesPeriod'] ) ),
							$this->expectedField( 'maxEntries', $form['limitEntriesCount'] ),
						]
					),
					$this->expectedField( 'firstPageCssClass', $form['firstPageCssClass'] ),
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								[
									$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormFieldTypeEnum::$type, $form['fields'][0]['type'] ) ),
								],
								0
							),
							$this->expectedNode(
								'nodes',
								[
									$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormFieldTypeEnum::$type, $form['fields'][1]['type'] ) ),
								],
								1
							),
						]
					),
					$this->expectedField( 'hasConditionalLogicAnimation', $form['enableAnimation'] ),
					$this->expectedField( 'hasHoneypot', $form['enableHoneypot'] ),
					$this->expectedField( 'hasValidationSummary', $form['validationSummary'] ),
					$this->expectedField( 'id', Relay::toGlobalId( FormsLoader::$name, $form['id'] ) ),
					$this->expectedField( 'isActive', (bool) $form['is_active'] ),
					$this->expectedField( 'isTrash', (bool) $form['is_trash'] ),
					$this->expectedField( 'labelPlacement', GFHelpers::get_enum_for_value( Enum\FormLabelPlacementEnum::$type, $form['labelPlacement'] ) ),
					$this->expectedObject(
						'login',
						[
							$this->expectedField( 'isLoginRequired', $form['requireLogin'] ),
							$this->expectedField( 'loginRequiredMessage', $form['requireLoginMessage'] ),
						]
					),
					$this->expectedField( 'markupVersion', $form['markupVersion'] ),
					$this->expectedField( 'nextFieldId', $form['nextFieldId'] ),
					$this->expectedNode(
						'notifications',
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
							$this->expectedNode(
								'routing',
								[
									$this->expectedField( 'email', $form['notifications']['5cfec9464e529']['routing'][0]['email'] ),
									$this->expectedField( 'fieldId', (int) $form['notifications']['5cfec9464e529']['routing'][0]['fieldId'] ),
									$this->expectedField( 'operator', GFHelpers::get_enum_for_value( Enum\FormRuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][0]['operator'] ) ),
									$this->expectedField( 'value', $form['notifications']['5cfec9464e529']['routing'][0]['value'] ),
								],
								0
							),
							$this->expectedNode(
								'routing',
								[
									$this->expectedField( 'email', $form['notifications']['5cfec9464e529']['routing'][1]['email'] ),
									$this->expectedField( 'fieldId', (int) $form['notifications']['5cfec9464e529']['routing'][1]['fieldId'] ),
									$this->expectedField( 'operator', GFHelpers::get_enum_for_value( Enum\FormRuleOperatorEnum::$type, $form['notifications']['5cfec9464e529']['routing'][1]['operator'] ) ),
									$this->expectedField( 'value', $form['notifications']['5cfec9464e529']['routing'][1]['value'] ),
								],
								1
							),
							$this->expectedField( 'service', $form['notifications']['5cfec9464e529']['service'] ),
							$this->expectedField( 'shouldSendAttachments', ! empty( $form['notifications']['5cfec9464e529']['enableAttachments'] ) ),
							$this->expectedField( 'subject', $form['notifications']['5cfec9464e529']['subject'] ),
							$this->expectedField( 'to', $form['notifications']['5cfec9464e529']['to'] ),
							$this->expectedField( 'toType', GFHelpers::get_enum_for_value( Enum\FormNotificationToTypeEnum::$type, $form['notifications']['5cfec9464e529']['toType'] ) ),
						]
					),
					$this->expectedObject(
						'pagination',
						[
							$this->expectedField( 'backgroundColor', $form['pagination']['backgroundColor'] ),
							$this->expectedField( 'color', $form['pagination']['color'] ),
							$this->expectedField( 'hasProgressbarOnConfirmation', ! empty( $form['pagination']['display_progressbar_on_confirmation'] ) ),
							$this->expectedObject(
								'lastPageButton',
								[
									$this->expectedField( 'imageUrl', $form['lastPageButton']['imageUrl'] ),
									$this->expectedField( 'text', $form['lastPageButton']['text'] ),
									$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormButtonTypeEnum::$type, $form['lastPageButton']['type'] ) ),
								]
							),
							$this->expectedField( 'pageNames', $form['pagination']['pages'] ),
							$this->expectedField( 'progressbarCompletionText', $form['pagination']['progressbar_completion_text'] ),
							$this->expectedField( 'style', GFHelpers::get_enum_for_value( Enum\FormPageProgressStyleEnum::$type, $form['pagination']['style'] ) ),
							$this->expectedField( 'type', GFHelpers::get_enum_for_value( Enum\FormPageProgressTypeEnum::$type, $form['pagination']['type'] ) ),
						]
					),
					$this->expectedObject(
						'personalData',
						[
							$this->expectedField( 'daysToRetain', $form['personalData']['retention']['retain_entries_days'] ),
							$this->expectedField( 'retentionPolicy', GFHelpers::get_enum_for_value( Enum\FormRetentionPolicyEnum::$type, $form['personalData']['retention']['policy'] ) ),
							$this->expectedField( 'shouldSaveIP', empty( $form['personalData']['preventIP'] ) ),
							$this->expectedObject(
								'dataPolicies',
								[
									$this->expectedField( 'canExportAndErase', $form['personalData']['exportingAndErasing']['enabled'] ),
									$this->expectedField( 'identificationFieldDatabaseId', $form['personalData']['exportingAndErasing']['identificationField'] ),
									$this->expectedNode(
										'entryData',
										[
											$this->expectedField( 'key', array_keys( $form['personalData']['exportingAndErasing']['columns'] )[0] ),
											$this->expectedField( 'shouldErase', ! empty( $form['personalData']['exportingAndErasing']['columns'][ array_keys( $form['personalData']['exportingAndErasing']['columns'] )[0] ]['erase'] ) ),
											$this->expectedField( 'shouldExport', ! empty( $form['personalData']['exportingAndErasing']['columns'][ array_keys( $form['personalData']['exportingAndErasing']['columns'] )[0] ]['export'] ) ),
										],
										0
									),
									$this->expectedNode(
										'entryData',
										[
											$this->expectedField( 'key', array_keys( $form['personalData']['exportingAndErasing']['columns'] )[1] ),
											$this->expectedField( 'shouldErase', ! empty( $form['personalData']['exportingAndErasing']['columns'][ array_keys( $form['personalData']['exportingAndErasing']['columns'] )[1] ]['erase'] ) ),
											$this->expectedField( 'shouldExport', ! empty( $form['personalData']['exportingAndErasing']['columns'][ array_keys( $form['personalData']['exportingAndErasing']['columns'] )[1] ]['export'] ) ),
										],
										1
									),
								]
							),
						]
					),
					$this->expectedObject(
						'postCreation',
						[
							$this->expectedField( 'authorDatabaseId', $form['postAuthor'] ),
							$this->expectedField( 'authorId', Relay::toGlobalId( 'user', $form['postAuthor'] ) ),
							$this->expectedField( 'categoryDatabaseId', $form['postCategory'] ),
							$this->expectedField( 'contentTemplate', $form['postContentTemplate'] ),
							$this->expectedField( 'format', GFHelpers::get_enum_for_value( Enum\PostFormatTypeEnum::$type, $form['postFormat'] ) ),
							$this->expectedField( 'hasContentTemplate', ! empty( $form['postContentTemplateEnabled'] ) ),
							$this->expectedField( 'hasTitleTemplate', ! empty( $form['postTitleTemplateEnabled'] ) ),
							$this->expectedField( 'titleTemplate', $form['postTitleTemplate'] ),
							$this->expectedField( 'status', $form['postStatus'] ),
							$this->expectedField( 'shouldUseCurrentUserAsAuthor', ! empty( $form['useCurrentUserAsAuthor'] ) ),
						]
					),
					// @todo Quiz fields
					$this->expectedObject(
						'quiz',
						[
							$this->expectedObject(
								'failConfirmation',
								[
									$this->expectedField( 'isAutoformatted', empty( $form['gravityformsquiz']['failConfirmationDisableAutoformat'] ) ),
									$this->expectedField( 'message', $form['gravityformsquiz']['failConfirmationMessage'] ),
								]
							),
							// This is null, since grading type is PASSFAIL.
							$this->expectedField( 'grades', static::IS_NULL ),
							// $this->expectedNode(
							// 'grades',
							// [
							// $this->expectedField( 'text', $form['gravityformsquiz']['grades'][0]['text'] ),
							// $this->expectedField( 'value', $form['gravityformsquiz']['grades'][0]['value'] ),
							// ],
							// 0
							// ),
							$this->expectedField( 'gradingType', GFHelpers::get_enum_for_value( QuizEnum\QuizFieldGradingTypeEnum::$type, $form['gravityformsquiz']['grading'] ) ),
							$this->expectedField( 'hasInstantFeedback', ! empty( $form['gravityformsquiz']['instantFeedback'] ) ),
							// This is null because grading type is PASSFAIL.
							$this->expectedField( 'hasLetterConfirmationMessage', static::IS_NULL ),
							$this->expectedField( 'hasPassFailConfirmationMessage', ! empty( $form['gravityformsquiz']['passfailDisplayConfirmation'] ) ),
							$this->expectedField( 'isShuffleFieldsEnabled', ! empty( $form['gravityformsquiz']['shuffleFields'] ) ),
							$this->expectedField( 'letterConfirmation', static::IS_NULL ),
							$this->expectedField( 'maxScore', static::IS_NULL ),
							$this->expectedObject(
								'passConfirmation',
								[
									$this->expectedField( 'isAutoformatted', empty( $form['gravityformsquiz']['passConfirmationDisableAutoformat'] ) ),
									$this->expectedField( 'message', $form['gravityformsquiz']['passConfirmationMessage'] ),
								]
							),
							$this->expectedField( 'passPercent', $form['gravityformsquiz']['passPercent'] ),
						]
					),
					$this->expectedField( 'requiredIndicator', GFHelpers::get_enum_for_value( Enum\FormFieldRequiredIndicatorEnum::$type, $form['requiredIndicator'] ) ),
					$this->expectedObject(
						'saveAndContinue',
						[
							$this->expectedField( 'buttonText', $form['save']['button']['text'] ),
							$this->expectedField( 'hasSaveAndContinue', ! empty( $form['save']['enabled'] ) ),
						]
					),
					$this->expectedObject(
						'scheduling',
						[
							$this->expectedField( 'closedMessage', $form['scheduleMessage'] ),
							$this->expectedObject(
								'endDetails',
								[
									$this->expectedField( 'amPm', GFHelpers::get_enum_for_value( Enum\AmPmEnum::$type, $form['scheduleEndAmpm'] ) ),
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
									$this->expectedField( 'amPm', GFHelpers::get_enum_for_value( Enum\AmPmEnum::$type, $form['scheduleStartAmpm'] ) ),
									$this->expectedField( 'date', get_date_from_gmt( $form['scheduleStart'] ) ),
									$this->expectedField( 'dateGmt', $form['scheduleStart'] ),
									$this->expectedField( 'hour', $form['scheduleStartHour'] ),
									$this->expectedField( 'minute', $form['scheduleStartMinute'] ),

								]
							),
						]
					),
					$this->expectedField( 'subLabelPlacement', GFHelpers::get_enum_for_value( Enum\FormSubLabelPlacementEnum::$type, $form['subLabelPlacement'] ) ),
					$this->expectedField( 'title', $form['title'] ),
					$this->expectedField( 'version', $form['version'] ),
				]
			),
		];
	}
}
