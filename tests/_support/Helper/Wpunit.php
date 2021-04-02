<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Wpunit extends \Codeception\Module {

	function getTextFieldDefaultArgs() : array {
		return [
			'type'                 => 'text',
			'id'                   => 1,
			'label'                => 'Single Line Text',
			'adminLabel'           => '',
			'isRequired'           => false,
			'size'                 => 'medium',
			'errorMessage'         => '',
			'visibility'           => 'visible',
			'inputs'               => null,
			'formId'               => 2,
			'description'          => 'I am a single line text field.',
			'allowsPrepopulate'    => false,
			'inputMask'            => false,
			'inputMaskValue'       => '',
			'inputMaskIsCustom'    => false,
			'maxLength'            => '',
			'inputType'            => '',
			'labelPlacement'       => '',
			'descriptionPlacement' => '',
			'subLabelPlacement'    => '',
			'placeholder'          => '',
			'cssClass'             => '',
			'inputName'            => '',
			'noDuplicates'         => false,
			'defaultValue'         => '',
			'choices'              => '',
			'productField'         => '',
			'enablePasswordInput'  => '',
			'multipleFiles'        => false,
			'maxFiles'             => '',
			'calculationFormula'   => '',
			'calculationRounding'  => '',
			'enableCalculation'    => '',
			'disableQuantity'      => false,
			'displayAllCategories' => false,
			'useRichTextEditor'    => false,
			'checkboxLabel'        => '',
			'pageNumber'           => 1,
			'fields'               => '',
			'displayOnly'          => '',
		];
	}

	function getTextAreaFieldDefaultArgs() : array {
		return [
			'type'                 => 'textarea',
			'id'                   => 2,
			'label'                => 'Text Area',
			'adminLabel'           => '',
			'isRequired'           => false,
			'size'                 => 'medium',
			'errorMessage'         => '',
			'visibility'           => 'visible',
			'inputs'               => null,
			'formId'               => 2,
			'description'          => 'I am a text area field.',
			'allowsPrepopulate'    => false,
			'inputMask'            => false,
			'inputMaskValue'       => '',
			'inputMaskIsCustom'    => false,
			'maxLength'            => 28,
			'inputType'            => '',
			'labelPlacement'       => '',
			'descriptionPlacement' => '',
			'subLabelPlacement'    => '',
			'placeholder'          => '',
			'cssClass'             => '',
			'inputName'            => '',
			'noDuplicates'         => false,
			'defaultValue'         => '',
			'choices'              => '',
			'conditionalLogic'     => '',
			'productField'         => '',
			'form_id'              => '',
			'useRichTextEditor'    => false,
			'multipleFiles'        => false,
			'maxFiles'             => '',
			'calculationFormula'   => '',
			'calculationRounding'  => '',
			'enableCalculation'    => '',
			'disableQuantity'      => false,
			'displayAllCategories' => false,
			'pageNumber'           => 1,
			'fields'               => '',
			'displayOnly'          => '',
		];
	}

	function getFormDefaultArgs() : array {
		return [
			'button'                     => [
				'conditionalLogic' => [
					'actionType' => 'show',
					'logicType'  => 'any',
					'rules'      => [
						[
							'fieldId'  => 1,
							'operator' => 'is',
							'value'    => 'value1',
						],
						[
							'fieldId'  => 1,
							'operator' => 'is',
							'value'    => 'value2',
						],
					],
				],
				'imageUrl'         => 'https://example.com',
				'text'             => 'Submit',
				'type'             => 'text',
			],
			'confirmations'              => [
				'5cfec9464e7d7' => [
					'id'          => '5cfec9464e7d7',
					'isDefault'   => true,
					'message'     => 'Thanks for contacting us! We will get in touch with you shortly.',
					'name'        => 'Default Confirmation',
					'pageId'      => 1,
					'queryString' => 'text={Single Line Text:1}&textarea={Text Area:2}',
					'type'        => 'message',
					'url'         => 'https://example.com/',
				],
			],
			'cssClass'                   => 'css-class-1 css-class-2',
			'date_created'               => '2019-06-10 21:19:02', // This is disregarded by GFAPI::add_form().
			'descriptionPlacement'       => 'below',
			'enableAnimation'            => false,
			'enableHoneypot'             => false,
			'firstPageCssClass'          => 'first-page-css-class',
			'is_active'                  => true,
			'is_trash'                   => false,
			'labelPlacement'             => 'top_label',
			'lastPageButton'             => [
				'imageUrl' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
				'text'     => 'Previous',
				'type'     => 'text',
			],
			'limitEntries'               => true,
			'limitEntriesCount'          => 100,
			'limitEntriesMessage'        => 'Only 100 entries are permitted.',
			'limitEntriesPeriod'         => 'year',
			'nextFieldId'                => 3,
			'notifications'              => [
				'5cfec9464e529' => [
					'bcc'               => 'bcc-email@example.com',
					'conditionalLogic'  => [
						'actionType' => 'show',
						'logicType'  => 'any',
						'rules'      => [
							[
								'fieldId'  => 1,
								'operator' => 'is',
								'value'    => 'value1',
							],
							[
								'fieldId'  => 1,
								'operator' => 'is',
								'value'    => 'value2',
							],
						],
					],
					'disableAutoformat' => false,
					'enableAttachments' => false,
					'event'             => 'form_submission',
					'from'              => 'from-email@example.com',
					'fromName'          => 'WordPress',
					'id'                => '5cfec9464e529',
					'isActive'          => true,
					'message'           => '{all_fields}',
					'name'              => 'Admin Notification',
					'replyTo'           => 'replyto-email@example.com',
					'routing'           => [
						[
							'fieldId'  => 1,
							'operator' => 'is',
							'value'    => 'value1',
							'email'    => 'email1@example.com',
						],
						[
							'fieldId'  => 1,
							'operator' => 'is',
							'value'    => 'value2',
							'email'    => 'email2@example.com',
						],
					],
					'service'           => 'wordpress',
					'subject'           => 'New submission from {form_title}',
					'to'                => '{admin_email}',
					'toType'            => 'email',
				],
			],
			'pagination'                 => [
				'backgroundColor'                     => '#c6df9c',
				'color'                               => '#197b30',
				'display_progressbar_on_confirmation' => true,
				'pages'                               => [ 'page-1-name', 'page-2-name' ],
				'progressbar_completion_text'         => 'Completed!',
				'style'                               => 'custom',
				'type'                                => 'percentage',
			],
			'postAuthor'                 => 1,
			'postCategory'               => 1,
			'postContentTemplate'        => 'Post content template',
			'postContentTemplateEnabled' => false,
			'postFormat'                 => '0',
			'postStatus'                 => 'publish',
			'postTitleTemplate'          => 'Post title template',
			'postTitleTemplateEnabled'   => false,
			'requireLogin'               => true,
			'requireLoginMessage'        => 'You must be logged in to submit this form.',
			'save'                       => [
				'button'  => [
					'text' => 'Save and Continue Later',
				],
				'enabled' => true,
			],
			'scheduleEnd'                => '01/01/2030',
			'scheduleEndAmpm'            => 'pm',
			'scheduleEndHour'            => 10,
			'scheduleEndMinute'          => 45,
			'scheduleForm'               => true,
			'scheduleMessage'            => 'Schedule message.',
			'schedulePendingMessage'     => 'Schedule pending message.',
			'scheduleStart'              => '01/01/2020',
			'scheduleStartAmpm'          => 'am',
			'scheduleStartHour'          => 9,
			'scheduleStartMinute'        => 30,
			'subLabelPlacement'          => 'below',
			'useCurrentUserAsAuthor'     => true,
		];
	}

	function get_enum_for_value( string $enumName, string $value ) : string {
		$typeRegistry = \WPGraphQL::get_type_registry();

		return $typeRegistry->get_type( $enumName )->serialize( $value );
	}
}
