<?php
/**
 * Helpers for WPUnit tests.
 *
 * @package Helper.
 */

namespace Helper;

use Helper\GFHelpers\PropertyHelper;

/**
 * Class - Wpunit
 * All public methods declared in helper class will be available in $I
 */
class Wpunit extends \Codeception\Module {

	/**
	 * Generates the property helper for the field.
	 *
	 * @param string $type .
	 * @param array  $args .
	 * @return PropertyHelper
	 */
	public function getPropertyHelper( string $type, array $args = [] ) : PropertyHelper {
		$interface_defaults = $this->getDefaultArgs();
		$field_defaults     = call_user_func( [ $this, "get{$type}Args" ] );

		$keys = $this->merge_default_args( array_merge( $interface_defaults, $field_defaults ), $args );

		return new PropertyHelper( $keys );
	}

	/**
	 * Gets the default args used by all fields.
	 */
	public function getDefaultArgs() : array {
		return [
			'displayOnly',
			[ 'conditionalLogic' => null ],
			'cssClass',
			[ 'id' => 1 ],
			[ 'inputType' => null ],
			'layoutGridColumnSpan',
			'layoutSpacerGridColumnSpan',
			'pageNumber',
			'visibility',
			'personalDataErase',
			'personalDataExport',
		];
	}

	/**
	 * Get the default args for an address field.
	 */
	public function getAddressFieldArgs() : array {
		return [
			'addressType',
			'adminLabel',
			'allowsPrepopulate',
			'copyValuesOptionDefault',
			'copyValuesOptionField',
			'defaultCountry',
			'defaultProvince',
			'defaultState',
			'description',
			'descriptionPlacement',
			'displayOnly',
			'enableAutocomplete',
			'enableCopyValuesOption',
			'errorMessage',
			'isRequired',
			'label',
			'labelPlacement',
			'subLabelPlacement',
			[
				'inputs' => [
					'fieldId' => 1,
					'count'   => 6,
					'keys'    => [ 'customLabel', 'defaultValue', 'isHidden', 'label', 'name', 'placeholder', 'autocompleteAttribute' ],
				],
			],
			[ 'type' => 'address' ],
		];
	}

	/**
	 * Get the default args for a Captcha field.
	 */
	public function getCaptchaFieldArgs() : array {
		return [
			'captchaBadgePosition',
			'captchaLanguage',
			'captchaTheme',
			'captchaType',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'label',
			'labelPlacement',
			'simpleCaptchaBackgroundColor',
			'simpleCaptchaSize',
			'simpleCaptchaFontColor',
			[ 'type' => 'captcha' ],
		];
	}

	/**
	 * Get the default args for a ChainedSelect field.
	 */
	public function getChainedSelectFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'chainedSelectsAlignment',
			'chainedSelectsHideInactive',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'subLabelPlacement',
			[ 'type' => 'chainedselect' ],
			[
				'inputs' => [
					'fieldId' => 1,
					'count'   => 3,
					'keys'    => [ 'label', 'name' ],
				],
			],
			[
				'choices' => [
					[
						'text'       => '2015',
						'value'      => '2015',
						'isSelected' => false,
						'choices'    => [
							[
								'text'       => 'Acura',
								'value'      => 'Acura',
								'isSelected' => true,
								'choices'    => [
									[
										'text'       => 'ILX',
										'value'      => 'ILX',
										'isSelected' => true,
									],
									[
										'text'       => 'MDX',
										'value'      => 'MDX',
										'isSelected' => false,
									],
								],
							],
							[
								'text'       => 'Alfa Romeo',
								'value'      => 'Alfa Romeo',
								'isSelected' => false,
								'choices'    => [
									[
										'text'       => '4C',
										'value'      => '4c',
										'isSelected' => false,
									],
									[
										'text'       => '4C Spider',
										'value'      => '$C Spider',
										'isSelected' => false,
									],
								],
							],
						],
					],
					[
						'text'       => '2016',
						'value'      => '2016',
						'isSelected' => false,
						'choices'    => [
							[
								'text'       => 'Acura',
								'value'      => 'Acura',
								'isSelected' => false,
								'choices'    => [
									[
										'text'       => 'ILX',
										'value'      => 'ILX',
										'isSelected' => false,
									],
									[
										'text'       => 'MDX 2016',
										'value'      => 'MDX 2016',
										'isSelected' => false,
									],
								],
							],
							[
								'text'       => 'Alfa Romeo',
								'value'      => 'Alfa Romeo',
								'isSelected' => false,
								'choices'    => [
									[
										'text'       => '4C',
										'value'      => '4c',
										'isSelected' => false,
									],
									[
										'text'       => '4C Spider',
										'value'      => '$C Spider',
										'isSelected' => false,
									],
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Get the default args for a Checkbox field.
	 */
	public function getCheckboxFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'description',
			'descriptionPlacement',
			'enableChoiceValue',
			'enableSelectAll',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			[ 'type' => 'checkbox' ],
			[
				'inputs' => [
					'fieldId' => 1,
					'count'   => 3,
					'keys'    => [ 'label', 'name' ],
				],
			],
			[
				'choices' => [
					[
						'text'       => 'First Choice',
						'value'      => 'first',
						'isSelected' => true,
					],
					[
						'text'       => 'Second Choice',
						'value'      => 'second',
						'isSelected' => false,
					],
					[
						'text'       => 'Third Choice',
						'value'      => 'third',
						'isSelected' => false,
					],
				],
			],
		];
	}

	/**
	 * Get the default args for a consent field.
	 */
	public function getConsentFieldArgs() : array {
		return [
			'adminLabel',
			'checkboxLabel',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			[ 'type' => 'consent' ],
			[
				'inputs' => [
					[
						'id'    => '1.1',
						'label' => 'Conset',
					],
					[
						'id'       => '1.2',
						'label'    => 'Text',
						'isHidden' => true,
					],
					[
						'id'       => '1.3',
						'label'    => 'Description',
						'isHidden' => 1,
					],
				],
			],
			[
				'choices' => [
					[
						'text'       => 'Checked',
						'value'      => 1,
						'isSelected' => false,
					],
				],
			],
		];
	}

	/**
	 * Get the default args for a consent field.
	 */
	public function getDateFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'calendarIconType',
			'calendarIconUrl',
			'dateFormat',
			'dateType',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'subLabelPlacement',
			[ 'type' => 'date' ],
			[
				'inputs' => [
					'fieldId' => 1,
					'count'   => 3,
					'keys'    => [ 'customLabel', 'defaultValue', 'label', 'placeholder' ],
				],
			],
		];
	}

	/**
	 * Get the default args for an email field.
	 */
	public function getEmailFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'emailConfirmEnabled',
			'enableAutocomplete',
			'enableEnhancedUI',
			'errorMessage',
			[
				'inputs' => [
					[
						'autocompleteAttribute' => 'email',
						'customLabel'           => 'enter email',
						'defaultValue'          => 'user@someemail.com',
						'id'                    => 1,
						'label'                 => 'Enter Email',
						'name'                  => null,
						'placeholder'           => 'place',
					],
					[
						'autocompleteAttribute' => 'email',
						'customLabel'           => 'confirm email',
						'defaultValue'          => 'user@someemail.com',
						'id'                    => '1.2',
						'label'                 => 'Confirm Email',
						'name'                  => null,
						'placeholder'           => 'holder',
					],
				],
			],
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'size',
			[ 'type' => 'email' ],
		];
	}

	/**
	 * Get the default args for an email field.
	 */
	public function getFileUploadFieldArgs() : array {
		return [
			'adminLabel',
			'allowedExtensions',
			'cssClass',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'isRequired',
			'label',
			'labelPlacement',
			'maxFiles',
			'maxFileSize',
			'multipleFiles',
			[ 'type' => 'fileupload' ],
		];
	}

	/**
	 * Get the default args for a hidden field.
	 */
	public function getHiddenFieldArgs() : array {
		return [
			[ 'cssClass' => null ],
			[ 'adminLabel' => null ],
			[ 'isRequired' => null ],
			[ 'noDuplicates' => null ],
			'allowsPrepopulate',
			'defaultValue',
			'label',
			[ 'type' => 'hidden' ],
			[ 'visibility' => 'hidden' ],
		];
	}

	/**
	 * Get args for HTML field.
	 */
	public function getHtmlFieldArgs() : array {
		return [
			'adminLabel',
			'content',
			'disableMargins',
			[ 'displayOnly' => true ],
			'inputName',
			'label',
			[ 'type' => 'html' ],
		];
	}
	/**
	 * Get the default args for a list field.
	 */
	public function getListFieldArgs() : array {
		return [
			'addIconUrl',
			'adminLabel',
			'allowsPrepopulate',
			[
				'choices' => [
					[
						'text'  => 'First Choice',
						'value' => 'first',
					],
					[
						'text'  => 'Second Choice',
						'value' => 'second',
					],
					[
						'text'  => 'Third Choice',
						'value' => 'third',
					],
				],
			],
			'deleteIconUrl',
			'description',
			'descriptionPlacement',
			'enableColumns',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'listValues',
			'maxRows',
			[ 'type' => 'list' ],
		];
	}

	/**
	 * Get the default args for a MultiSelect field.
	 */
	public function getMultiSelectFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'description',
			'descriptionPlacement',
			'enableChoiceValue',
			'enableEnhancedUI',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'size',
			'storageType',
			[ 'type' => 'multiselect' ],
			[
				'choices' => [
					[
						'text'       => 'first',
						'value'      => 'first',
						'isSelected' => true,
					],
					[
						'text'       => 'second',
						'value'      => 'second',
						'isSelected' => false,
					],
					[
						'text'       => 'third',
						'value'      => 'third',
						'isSelected' => false,
					],
				],
			],
		];
	}

	/**
	 * Get the default args for a name field.
	 */
	public function getNameFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'description',
			'descriptionPlacement',
			'enableAutocomplete',
			'errorMessage',
			'isRequired',
			'label',
			'labelPlacement',
			'subLabelPlacement',
			[
				'inputs' => [
					[
						'autocompleteAttribute' => 'honorific-prefix',
						'customLabel'           => null,
						'defaultValue'          => 'some_default',
						'id'                    => '1.2',
						'isHidden'              => false,
						'key'                   => 'prefix',
						'label'                 => 'Prefix',
						'choices'               => [
							[
								'isSelected' => true,
								'text'       => 'Dr.',
								'value'      => 'Dr.',
							],
							[
								'isSelected' => false,
								'text'       => 'Prof.',
								'value'      => 'Prof.',
							],
						],
						'placeholder'           => 'pre',
					],
					[
						'autocompleteAttribute' => 'given-name',
						'choices'               => null,
						'customLabel'           => null,
						'defaultValue'          => 'some_default',
						'id'                    => '1.3',
						'isHidden'              => false,
						'key'                   => 'first',
						'label'                 => 'First',
						'placeholder'           => 'f',
					],
					[
						'autocompleteAttribute' => 'additional-name',
						'choices'               => null,
						'customLabel'           => null,
						'defaultValue'          => 'some_default',
						'id'                    => '1.4',
						'isHidden'              => false,
						'key'                   => 'middle',
						'label'                 => 'Middle',
						'placeholder'           => 'f',
					],
					[
						'autocompleteAttribute' => 'family-name',
						'choices'               => null,
						'customLabel'           => null,
						'defaultValue'          => 'some_default',
						'id'                    => '1.6',
						'isHidden'              => false,
						'key'                   => 'last',
						'label'                 => 'Last',
						'placeholder'           => 'm',
					],
					[
						'autocompleteAttribute' => 'honorific-suffix',
						'choices'               => null,
						'customLabel'           => null,
						'defaultValue'          => 'some_default',
						'id'                    => '1.8',
						'isHidden'              => false,
						'key'                   => 'suffix',
						'label'                 => 'Suffix',
						'isHidden'              => false,
						'placeholder'           => 's',
					],
				],
			],
			[ 'type' => 'name' ],
		];
	}

	/**
	 * Get the default args for a number field.
	 */
	public function getNumberFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'autocompleteAttribute',
			'calculationFormula',
			'calculationRounding',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'enableAutocomplete',
			'enableCalculation',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'numberFormat',
			'placeholder',
			'rangeMax',
			'rangeMin',
			'size',
			[ 'type' => 'number' ],
		];
	}

	/**
	 * Get args for page field.
	 */
	public function getPageFieldArgs() : array {
		return [
			'adminLabel',
			[
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
			],
			[ 'displayOnly' => true ],
			'inputName',
			'label',
			'nextButton',
			'previousButton',
			[ 'type' => 'page' ],
		];
	}

	/**
	 * Get the default args for a Phone field.
	 */
	public function getPhoneFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'autocompleteAttribute',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'enableAutocomplete',
			'enableCalculation',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'phoneFormat',
			'size',
			[ 'type' => 'phone' ],
		];
	}

	/**
	 * Get the default args for a PostContent field.
	 */
	public function getPostContentFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'maxLength',
			'placeholder',
			'size',
			[ 'type' => 'post_content' ],
		];
	}

	/**
	 * Get the default args for a PostContent field.
	 */
	public function getPostCategoryFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'description',
			'descriptionPlacement',
			'displayAllCategories',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'maxLength',
			'placeholder',
			'size',
			[ 'type' => 'post_category' ],
		];
	}

	/**
	 * Get the default args for a PostExcerpt field.
	 */
	public function getPostExcerptFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'maxLength',
			'placeholder',
			'size',
			[ 'type' => 'post_excerpt' ],
		];
	}


	/**
	 * Get the default args for an email field.
	 */
	public function getPostImageFieldArgs() : array {
		return [
			'adminLabel',
			'allowedExtensions',
			'cssClass',
			'description',
			'descriptionPlacement',
			'displayAlt',
			'displayCaption',
			'displayDescription',
			'displayTitle',
			'errorMessage',
			'isRequired',
			'label',
			'labelPlacement',
			'postFeaturedImage',
			[ 'type' => 'post_image' ],
		];
	}

	/**
	 * Get the default args for a PostContent field.
	 */
	public function getPostTagsFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'placeholder',
			'size',
			[ 'type' => 'post_tags' ],
		];
	}

	/**
	 * Get the default args for a PostTitle field.
	 */
	public function getPostTitleFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'size',
			[ 'type' => 'post_title' ],
		];
	}

	/**
	 * Get the default args for a Quiz field
	 */
	public function getQuizFieldArgs() : array {
		return [
			'autocompleteAttribute',
			'adminLabel',
			'allowsPrepopulate',
			'gquizAnswerExplanation',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'enableAutocomplete',
			'enableChoiceValue',
			'enableEnhancedUI',
			'enableSelectAll',
			'enableOtherChoice',
			'errorMessage',
			'gquizWeightedScoreEnabled',
			'gquizEnableRandomizeQuizChoices',
			[ 'id' => 1 ],
			'inputName',
			[ 'inputType' => 'checkbox' ],
			[ 'inputs' => null ],
			[ 'gquizFieldType' => 'checkbox' ],
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'size',
			'gquizShowAnswerExplanation',
			[ 'type' => 'quiz' ],
			[
				'choices' => [
					[
						'text'           => 'First Choice',
						'value'          => 'gquiz4dd0fdac5',
						'gquizIsCorrect' => true,
						'gquizWeight'    => null,
						'isOtherChoice'  => false,
					],
					[
						'text'           => 'Second Choice',
						'value'          => 'gquiz4dd0fdac4',
						'gquizIsCorrect' => false,
						'gquizWeight'    => 1.4,
						'isOtherChoice'  => false,
					],
					[
						'text'           => 'Third Choice',
						'value'          => 'gquiz4dd0fdac3',
						'gquizIsCorrect' => false,
						'gquizWeight'    => 3,
						'isOtherChoice'  => false,
					],
				],
			],
		];
	}

	/**
	 * Get the default args for a radio field.
	 */
	public function getRadioFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			[
				'choices' => [
					[
						'text'       => 'First Choice',
						'value'      => 'first',
						'isSelected' => true,
					],
					[
						'text'       => 'Second Choice',
						'value'      => 'second',
						'isSelected' => true,
					],
					[
						'text'       => 'Third Choice',
						'value'      => 'third',
						'isSelected' => false,
					],
				],
			],
			'description',
			'descriptionPlacement',
			'enableChoiceValue',
			'enableOtherChoice',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			[ 'type' => 'radio' ],
		];
	}

	/**
	 * Get args for section field.
	 */
	public function getSectionFieldArgs() : array {
		return [
			'adminLabel',
			[ 'displayOnly' => true ],
			'inputName',
			'label',
			[ 'type' => 'section' ],
		];
	}

	/**
	 * Get the default args for a select field.
	 */
	public function getSelectFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'autocompleteAttribute',
			[
				'choices' => [
					[
						'text'       => 'First Choice',
						'value'      => 'first',
						'isSelected' => true,
					],
					[
						'text'       => 'Second Choice',
						'value'      => 'second',
						'isSelected' => false,
					],
					[
						'text'       => 'Third Choice',
						'value'      => 'third',
						'isSelected' => false,
					],
				],
			],
			'defaultValue',
			'description',
			'descriptionPlacement',
			'enableAutocomplete',
			'enableChoiceValue',
			'enableEnhancedUI',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'size',
			[ 'type' => 'select' ],
		];
	}

	/**
	 * Get the default args for a signature field.
	 */
	public function getSignatureFieldArgs() : array {
		return [
			'adminLabel',
			'backgroundColor',
			'borderColor',
			'borderStyle',
			'borderWidth',
			'boxWidth',
			'cssClass',
			'description',
			'descriptionPlacement',
			'errorMessage',
			[ 'id' => 1 ],
			'isRequired',
			'label',
			'labelPlacement',
			'penColor',
			'penSize',
			'size',
			[ 'type' => 'signature' ],
		];
	}

	/**
	 * Get the default args for a text field.
	 */
	public function getTextFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'autocompleteAttribute',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'enablePasswordInput',
			'errorMessage',
			'hasInputMask',
			'inputName',
			'inputMaskValue',
			'isRequired',
			'label',
			'labelPlacement',
			'maxLength',
			'noDuplicates',
			'placeholder',
			'size',
			[ 'type' => 'text' ],
		];
	}

	/**
	 * Get the default args for a textarea field.
	 */
	public function getTextAreaFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'maxLength',
			'noDuplicates',
			'placeholder',
			'size',
			[ 'type' => 'textarea' ],
			'useRichTextEditor',
		];
	}

	/**
	 * Get the default args for a time field.
	 */
	public function getTimeFieldArgs() : array {
		return [
			'adminLabel',
			'allowsPrepopulate',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			[
				'inputs' => [
					[
						'id'           => '2.1',
						'label'        => 'Hour',
						'customLabel'  => 'hr',
						'placeholder'  => 'hh',
						'defaultValue' => 12,
					],
					[
						'id'           => '2.2',
						'label'        => 'Minute',
						'customLabel'  => 'min',
						'placeholder'  => 'mm',
						'defaultValue' => 20,
					],
					[
						'id'           => '2.3',
						'label'        => 'AM/PM',
						'defaultValue' => 20,
					],
				],
			],
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'subLabelPlacement',
			[ 'type' => 'time' ],
		];
	}


	/**
	 * Get default args for website field.
	 */
	public function getWebsiteFieldArgs() : array {
		return [
			'adminLabel',
			'defaultValue',
			'description',
			'descriptionPlacement',
			'errorMessage',
			'inputName',
			'isRequired',
			'label',
			'labelPlacement',
			'noDuplicates',
			'placeholder',
			'size',
			[ 'type' => 'website' ],
		];
	}

	/**
	 * Get the default args for a form.
	 *
	 * @param array $args .
	 */
	public function getFormDefaultArgs( $args = [] ) : array {
		return array_merge(
			[
				'button'                     => [
					'conditionalLogic'     => [
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
					'imageUrl'             => 'https://example.com',
					'layoutGridColumnSpan' => '12',
					'location'             => 'bottom',
					'text'                 => 'Submit',
					'type'                 => 'text',
					'width'                => 'full',
				],
				'confirmations'              => [
					'5cfec9464e7d7' => [
						'id'               => '5cfec9464e7d7',
						'isActive'         => true,
						'isDefault'        => true,
						'message'          => 'Thanks for contacting us! We will get in touch with you shortly.',
						'name'             => 'Default Confirmation',
						'pageId'           => null,
						'queryString'      => 'text={Single Line Text:1}&textarea={Text Area:2}',
						'type'             => 'message',
						'url'              => 'https://example.com/',
						'conditionalLogic' => null,
					],
				],
				'cssClass'                   => 'css-class-1 css-class-2',
				'customRequiredIndicator'    => '(Required)',
				'date_created'               => '2019-06-10 21:19:02', // This is disregarded by GFAPI::add_form().
				'descriptionPlacement'       => 'below',
				'enableAnimation'            => false,
				'enableHoneypot'             => false,
				'firstPageCssClass'          => 'first-page-css-class',
				'gravityformsquiz'           => [
					'shuffleFields' => false,
					'instantFeedback' => true,
					'grading' => 'passfail',
					'grades'  => [
						[
							'text' => 'A',
							'value' => 90,
						],
						[
							'text' => 'B',
							'value' => 80,
						],
						[
							'text' => 'C',
							'value' => 70,
						],
						[
							'text' => 'D',
							'value' => 60,
						],
						[
							'text' => 'F',
							'value' => 0,
						],
					],
					'passPercent' => 50,
					'passfailDisplayConfirmation' => true,
					'passConfirmationMessage' => 'You passed!',
					'failConfirmationMessage' => 'You failed.',
					'failConfirmationDisableAutoformat' => false,
					'letterDisplayConfirmation' => true,
					'letterConfirmationMessage' => 'Your grade is {quiz_grade}.',
					'letterConfirmationDisableAutoformat' => false,
					'general' => null
				],
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
				'personalData'               => [
					'preventIP'           => false,
					'retention'           => [
						'policy'              => 'delete',
						'retain_entries_days' => 30,
					],
					'exportingAndErasing' => [
						'enabled'             => true,
						'identificationField' => 1,
						'columns'             => [
							'ip'        => [
								'export' => true,
								'erase'  => false,
							],
							'userAgent' => [
								'export' => false,
								'erase'  => true,
							],
						],
					],
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
			],
			$args
		);
	}


	/**
	 * Converts a string value to its Enum equivalent
	 *
	 * @param string      $enumName Name of the Enum registered in GraphQL.
	 * @param string|null $value .
	 * @return string|null
	 */
	public function get_enum_for_value( string $enumName, $value ) {
		if ( null === $value ) {
			return null;
		}

		$typeRegistry = \WPGraphQL::get_type_registry();
		return $typeRegistry->get_type( $enumName )->serialize( $value );
	}

	/**
	 * Merges default args with custom ones.
	 *
	 * @param array $default .
	 * @param array $custom .
	 */
	public function merge_default_args( array $default, array $custom = [] ) : array {
		array_walk(
			$default,
			function( &$value ) use ( $custom ) {
				if ( empty( $custom ) ) {
					return;
				}

				if ( is_string( $value ) && ! empty( $custom[ $value ] ) ) {
					$value = [ $value => $custom[ $value ] ];
					return;
				}

				if ( is_array( $value ) && ! empty( $custom[ array_key_first( $value ) ] ) ) {
					$value[ array_key_first( $value ) ] = $custom[ array_key_first( $value ) ];
					return;
				}
			}
		);
		return $default;
	}
}
