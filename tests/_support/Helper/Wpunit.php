<?php
/**
 * Helpers for WPUnit tests.
 *
 * @package .
 */

namespace Helper;

/**
 * Class - Wpunit
 * All public methods declared in helper class will be available in $I
 */
class Wpunit extends \Codeception\Module {

	/**
	 * Get the default args for a text field.
	 *
	 * @param array $args .
	 * @return array
	 */
	public function getTextFieldDefaultArgs( array $args = [] ) : array {
		return array_merge(
			[
				'adminLabel'                 => '',
				'allowsPrepopulate'          => true,
				'calculationFormula'         => '',
				'calculationRounding'        => '',
				'choices'                    => '',
				'cssClass'                   => 'first-class second-class',
				'defaultValue'               => 'Default',
				'description'                => 'I am a single line text field.',
				'descriptionPlacement'       => '',
				'disableQuantity'            => false,
				'displayAllCategories'       => false,
				'displayOnly'                => '',
				'enableCalculation'          => '',
				'enablePasswordInput'        => '',
				'errorMessage'               => 'Some error message',
				'fields'                     => '',
				'id'                         => 1,
				'inputMask'                  => false,
				'inputMaskIsCustom'          => false,
				'inputMaskValue'             => '',
				'inputName'                  => 'textinputname',
				'inputs'                     => null,
				'inputType'                  => '',
				'isRequired'                 => true,
				'label'                      => 'Single Line Text',
				'labelPlacement'             => '',
				'layoutGridColumnSpan'       => 6,
				'layoutSpacerGridColumnSpan' => 0,
				'maxFiles'                   => '',
				'maxLength'                  => 100,
				'multipleFiles'              => false,
				'noDuplicates'               => true,
				'pageNumber'                 => 1,
				'placeholder'                => 'some placeholder',
				'productField'               => '',
				'size'                       => 'medium',
				'subLabelPlacement'          => '',
				'type'                       => 'text',
				'useRichTextEditor'          => false,
				'visibility'                 => 'visible',
			],
			$args
		);
	}

	/**
	 * Get the default args for a textarea field.
	 *
	 * @param array $args .
	 * @return array
	 */
	public function getTextAreaFieldDefaultArgs( array $args = [] ) : array {
		return array_merge(
			[
				'adminLabel'           => '',
				'allowsPrepopulate'    => false,
				'calculationFormula'   => '',
				'calculationRounding'  => '',
				'choices'              => '',
				'cssClass'             => 'first-class second-class',
				'defaultValue'         => 'Default',
				'description'          => 'I am a text area field.',
				'descriptionPlacement' => '',
				'disableQuantity'      => false,
				'displayAllCategories' => false,
				'displayOnly'          => '',
				'enableCalculation'    => '',
				'errorMessage'         => 'Some error message',
				'fields'               => '',
				'formId'               => 2,
				'id'                   => 1,
				'inputMask'            => false,
				'inputMaskIsCustom'    => false,
				'inputMaskValue'       => '',
				'inputName'            => 'textareainputname',
				'inputs'               => null,
				'inputType'            => '',
				'isRequired'           => true,
				'label'                => 'Text Area',
				'labelPlacement'       => '',
				'maxFiles'             => '',
				'maxLength'            => 250,
				'multipleFiles'        => false,
				'noDuplicates'         => false,
				'pageNumber'           => 1,
				'placeholder'          => 'Some placeholder',
				'productField'         => '',
				'size'                 => 'medium',
				'subLabelPlacement'    => '',
				'type'                 => 'textarea',
				'useRichTextEditor'    => true,
				'visibility'           => 'visible',
			],
			$args
		);
	}

	/**
	 * Get the default args for an address field.
	 *
	 * @param array $args .
	 * @return array
	 */
	public function getAddressFieldDefaultArgs( array $args = [] ) : array {
		return array_merge(
			[
				'addressType'             => 'international',
				'adminLabel'              => '',
				'adminOnly'               => false,
				'copyValuesOptionDefault' => '',
				'copyValuesOptionField'   => null,
				'cssClass'                => 'first-class second-class',
				'defaultCountry'          => 'United States',
				'defaultProvince'         => '',
				'defaultState'            => '',
				'description'             => '',
				'descriptionPlacement'    => '',
				'enableCopyValuesOption'  => '',
				'enableAutocomplete'      => 1,
				'errorMessage'            => 'Some Error Message',
				'id'                      => 1,
				'isRequired'              => false,
				'label'                   => 'Address',
				'labelPlacement'          => '',
				'size'                    => 'medium',
				'subLabelPlacement'       => '',
				'type'                    => 'address',
				'visibility'              => 'visible',
				'inputs'                  => [
					[
						'customLabel'           => 'Street',
						'defaultValue'          => 'Default Street Address',
						'id'                    => '3.1',
						'isHidden'              => true,
						'label'                 => 'Street Address',
						'name'                  => 'ab',
						'placeholder'           => 'some Placeholder',
						'autocompleteAttribute' => 'street-autocomplete',
					],
					[
						'customLabel'           => 'line 2',
						'defaultValue'          => '',
						'id'                    => '3.2',
						'label'                 => 'Address Line 2',
						'name'                  => '',
						'placeholder'           => '',
						'autocompleteAttribute' => null,
					],
					[
						'customLabel'           => null,
						'defaultValue'          => null,
						'id'                    => '3.3',
						'isHidden'              => false,
						'label'                 => 'City',
						'name'                  => '',
						'placeholder'           => null,
						'autocompleteAttribute' => null,
					],
					[
						'customLabel'           => null,
						'defaultValue'          => null,
						'id'                    => '3.4',
						'isHidden'              => false,
						'label'                 => 'State / Province',
						'name'                  => '',
						'placeholder'           => 'State',
						'autocompleteAttribute' => null,
					],
					[
						'customLabel'           => null,
						'defaultValue'          => null,
						'id'                    => '3.5',
						'isHidden'              => true,
						'label'                 => 'ZIP / Postal Code',
						'name'                  => '',
						'placeholder'           => null,
						'autocompleteAttribute' => null,
					],
					[
						'customLabel'           => null,
						'defaultValue'          => null,
						'id'                    => '3.6',
						'isHidden'              => false,
						'label'                 => 'Country',
						'name'                  => '',
						'placeholder'           => null,
						'autocompleteAttribute' => null,
					],
				],
			],
			$args
		);
	}

	/**
	 * Get the default args for an address field.
	 *
	 * @param array $args .
	 * @return array
	 */
	public function getCaptchaFieldDefaultArgs( array $args = [] ) : array {
		return array_merge(
			[
				'captchaLanguage'              => 'iw',
				'captchaTheme'                 => 'dark',
				'captchaType'                  => '',
				'cssClass'                     => 'first-class second-class',
				'description'                  => '',
				'descriptionPlacement'         => '',
				'displayOnly'                  => 1,
				'errorMessage'                 => 'Some Error Message',
				'id'                           => 1,
				'label'                        => 'CAPTCHA',
				'layoutGridColumnSpan'         => null,
				'layoutSpacerGridColumnSpan'   => null,
				'simpleCaptchaBackgroundColor' => null,
				'simpleCaptchaSize'            => null,
				'simpleCaptchaFontColor'       => null,
				'size'                         => 'large',
				'type'                         => 'captcha',
			],
			$args
		);
	}

	/**
	 * Get the default args for a form.
	 *
	 * @return array
	 */
	public function getFormDefaultArgs() : array {
		return [
			'button'                     => [
				'conditionalLogic' => [
					'actionType' => 'hide',
					'logicType'  => 'any',
					'rules'      => [
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
			'customRequiredIndicator'    => '(Required)',
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
			'markupVersion'              => 2,
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
			'requireLogin'               => false,
			'requireLoginMessage'        => 'You must be logged in to submit this form.',
			'requiredIndicator'          => 'asterisk',
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
			'validationSummary'          => true,
			'version'                    => '2.5.0.1',
		];
	}

	/**
	 * Converts a string value to its Enum equivalent
	 *
	 * @param string $enumName Name of the Enum registered in GraphQL.
	 * @param string $value .
	 * @return string
	 */
	public function get_enum_for_value( string $enumName, string $value ) : string {
		$typeRegistry = \WPGraphQL::get_type_registry();
		return $typeRegistry->get_type( $enumName )->serialize( $value );
	}
}
