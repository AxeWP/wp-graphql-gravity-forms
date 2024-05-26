<?php
/**
 * Abstract class - GFHelpers.
 *
 * @package Helper\GFHelpers
 */

namespace Helper\GFHelpers;

use GF_Field;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum\ChainedSelectFieldAlignmentEnum;
use WPGraphQL\GF\Extensions\GFSignature\Type\Enum\SignatureFieldBorderStyleEnum;
use WPGraphQL\GF\Extensions\GFSignature\Type\Enum\SignatureFieldBorderWidthEnum;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\Enum\AmPmEnum;

trait ExpectedFormFields {
	public function add_icon_url_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'addIconUrl', ! empty( $field->addIconUrl ) ? $field->addIconUrl : self::IS_NULL );
	}

	public function address_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'addressType', ! empty( $field->addressType ) ? GFHelpers::get_enum_for_value( Enum\AddressFieldTypeEnum::$type, $field->addressType ) : self::IS_NULL );

		$properties[] = $this->expectedField( 'defaultCountry', ! empty( $field->defaultCountry ) ? GFHelpers::get_enum_for_value( Enum\AddressFieldCountryEnum::$type, $field->defaultCountry ) : self::IS_NULL );
		$properties[] = $this->expectedField( 'defaultProvince', ! empty( $field->defaultProvince ) ? GFHelpers::get_enum_for_value( Enum\AddressFieldProvinceEnum::$type, $field->defaultProvince ) : self::IS_NULL );
		$properties[] = $this->expectedField( 'defaultState', ! empty( $field->defaultState ) ? GFHelpers::get_enum_for_value( Enum\AddressFieldStateEnum::$type, $field->defaultState ) : self::IS_NULL );

		$input_keys = [
			'autocompleteAttribute' => 'autocompleteAttribute',
			'customLabel'           => 'customLabel',
			'defaultValue'          => 'defaultValue',
			'isHidden'              => 'isHidden',
			'label'                 => 'label',
			'name'                  => 'name',
			'placeholder'           => 'placeholder',
			'id'                    => 'id',
		];

		$properties[] = $this->expected_inputs( $input_keys, ! empty( $field->inputs ) ? $field->inputs : [] );
		$properties[] = $this->expectedField( 'inputs.0.key', 'street' );
		$properties[] = $this->expectedField( 'inputs.1.key', 'lineTwo' );
		$properties[] = $this->expectedField( 'inputs.2.key', 'city' );
		$properties[] = $this->expectedField( 'inputs.3.key', 'state' );
		$properties[] = $this->expectedField( 'inputs.4.key', 'zip' );
		$properties[] = $this->expectedField( 'inputs.5.key', 'country' );
	}

	public function admin_label_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'adminLabel', ! empty( $field->adminLabel ) ? $field->adminLabel : self::IS_NULL );
	}

	public function autocomplete_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasAutocomplete', ! empty( $field->enableAutocomplete ) );

		if ( ! in_array( $field->type, [ 'address', 'email', 'name' ], true ) ) {
			$properties[] = $this->expectedField( 'autocompleteAttribute', ! empty( $field->autocompleteAttribute ) ? $field->autocompleteAttribute : self::IS_NULL );
		}
	}

	public function background_color_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'backgroundColor', ! empty( $field->backgroundColor ) ? $field->backgroundColor : self::IS_NULL );
	}

	public function base_price_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'price', ! empty( $field->basePrice ) ? floatval( preg_replace( '/[^\d\.]/', '', $field->basePrice ) ) : self::IS_NULL );
		$properties[] = $this->expectedField( 'formattedPrice', ! empty( $field->basePrice ) ? $field->basePrice : self::IS_NULL );
	}

	public function border_color_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'borderColor', ! empty( $field->borderColor ) ? $field->borderColor : self::IS_NULL );
	}

	public function border_style_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'borderStyle', ! empty( $field->borderStyle ) ? GFHelpers::get_enum_for_value( SignatureFieldBorderStyleEnum::$type, $field->borderStyle ) : self::IS_NULL );
	}

	public function border_width_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'borderWidth', GFHelpers::get_enum_for_value( SignatureFieldBorderWidthEnum::$type, $field->borderWidth ?? 0 ) );
	}

	public function box_width_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'boxWidth', isset( $field->boxWidth ) ? (int) $field->boxWidth : self::IS_NULL );
	}

	public function calculation_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'isCalculation', ! empty( $field->enableCalculation ) );
		$properties[] = $this->expectedField( 'calculationFormula', ! empty( $field->calculationFormula ) ? $field->calculationFormula : self::IS_NULL );
		$properties[] = $this->expectedField( 'calculationRounding', isset( $field->calculationRounding ) ? (int) $field->calculationRounding : self::IS_NULL );
	}

	public function captcha_badge_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'captchaBadgePosition', GFHelpers::get_enum_for_value( Enum\CaptchaFieldBadgePositionEnum::$type, $field->captchaBadge ?? 'bottomright' ) );
	}

	public function captcha_bg_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'simpleCaptchaBackgroundColor', ! empty( $field->simpleCaptchaBackgroundColor ) ? $field->simpleCaptchaBackgroundColor : self::IS_NULL );
	}

	public function captcha_fg_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'simpleCaptchaFontColor', ! empty( $field->simpleCaptchaFontColor ) ? $field->simpleCaptchaFontColor : self::IS_NULL );
	}

	public function captcha_language_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'captchaLanguage', ! empty( $field->captchaLanguage ) ? $field->captchaLanguage : self::IS_NULL );
	}

	public function captcha_size_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'simpleCaptchaSize', ! empty( $field->simpleCaptchaSize ) ? GFHelpers::get_enum_for_value( Enum\FormFieldSizeEnum::$type, $field->simpleCaptchaSize ) : self::IS_NULL );
	}

	public function captcha_theme_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'captchaTheme', ! empty( $field->captchaTheme ) ? GFHelpers::get_enum_for_value( Enum\CaptchaFieldThemeEnum::$type, $field->captchaTheme ) : self::IS_NULL );
	}

	public function captcha_type_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'captchaType', ! empty( $field->captchaType ) ? GFHelpers::get_enum_for_value( Enum\CaptchaFieldTypeEnum::$type, $field->captchaType ) : self::IS_NULL );
	}

	public function chained_selects_alignment_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'chainedSelectsAlignment', ! empty( $field->chainedSelectsAlignment ) ? GFHelpers::get_enum_for_value( ChainedSelectFieldAlignmentEnum::$type, $field->chainedSelectsAlignment ) : self::IS_NULL );
	}

	public function chained_selects_hide_inactive_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'shouldHideInactiveChoices', ! empty( $field->chainedSelectsHideInactive ) );
	}

	public function choices_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasChoiceValue', ! empty( $field->enableChoiceValue ) );

		$keys = [
			'text'       => 'text',
			'value'      => 'value',
			'isSelected' => 'isSelected',
		];

		if ( ! empty( $field->enablePrice ) || in_array( $field->type, [ 'product', 'option', 'quantity', 'shipping' ], true ) ) {
			$keys['price']          = 'price';
			$keys['formattedPrice'] = 'price';
		}

		$properties[] = $this->expected_choices( $keys, ! empty( $field->choices ) ? $field->choices : [] );
	}

	public function chained_choices_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasChoiceValue', ! empty( $field->enableChoiceValue ) );

		$choice_keys  = [
			'text'       => 'text',
			'value'      => 'value',
			'isSelected' => 'isSelected',
			'choices'    => 'choices',
		];
		$properties[] = $this->expected_choices( $choice_keys, ! empty( $field->choices ) ? $field->choices : [] );

		$input_keys   = [
			'label' => 'label',
			'id'    => 'id',
			'name'  => 'name',
		];
		$properties[] = $this->expected_inputs( $input_keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function columns_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasColumns', ! empty( $field->enableColumns ) );

		$choice_keys  = [
			'text'  => 'text',
			'value' => 'value',
		];
		$properties[] = $this->expected_choices( $choice_keys, ! empty( $field->choices ) ? $field->choices : [] );
	}

	public function conditional_logic_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->get_expected_conditional_logic_fields( $field->conditionalLogic );
	}

	public function conditional_logic_page_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->get_expected_conditional_logic_fields( $field->conditionalLogic );
	}

	public function content_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'content', ! empty( $field->content ) ? $field->content : self::IS_NULL );
	}

	public function copy_values_option( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'shouldCopyValuesOption', ! empty( $field->enableCopyValuesOption ) );
		$properties[] = $this->expectedField( 'copyValuesOptionFieldId', isset( $field->copyValuesOptionField ) ? (int) $field->copyValuesOptionField : self::IS_NULL );
		$properties[] = $this->expectedField( 'copyValuesOptionLabel', ! empty( $field->copyValuesOptionLabel ) ? $field->copyValuesOptionLabel : self::IS_NULL );
	}

	public function credit_card_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'supportedCreditCards', ! empty( $field->creditCards ) ? $field->creditCards : self::IS_NULL );

		$keys = [
			'customLabel' => 'customLabel',
			'id'          => 'id',
			'label'       => 'label',
			'placeholder' => 'placeholder',
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function css_class_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'cssClass', ! empty( $field->cssClass ) ? $field->cssClass : self::IS_NULL );
	}

	public function date_format_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'dateFormat', ! empty( $field->dateFormat ) ? GFHelpers::get_enum_for_value( Enum\DateFieldFormatEnum::$type, $field->dateFormat ) : self::IS_NULL );

		$keys = [
			'autocompleteAttribute' => 'autocompleteAttribute',
			'customLabel'           => 'customLabel',
			'defaultValue'          => 'defaultValue',
			'id'                    => 'id',
			'label'                 => 'label',
			'placeholder'           => 'placeholder',
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function date_input_type_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'dateType', ! empty( $field->dateType ) ? GFHelpers::get_enum_for_value( Enum\DateFieldTypeEnum::$type, $field->dateType ) : self::IS_NULL );
		$properties[] = $this->expectedField( 'calendarIconType', ! empty( $field->calendarIconType ) ? GFHelpers::get_enum_for_value( Enum\FormFieldCalendarIconTypeEnum::$type, $field->calendarIconType ) : self::IS_NULL );
		$properties[] = $this->expectedField( 'calendarIconUrl', ! empty( $field->calendarIconUrl ) ? $field->calendarIconUrl : self::IS_NULL );
	}

	public function default_value_setting( GF_Field $field, array &$properties ): void {
		if ( 'email' !== $field->type ) {
			$properties[] = $this->expectedField( 'defaultValue', ! empty( $field->defaultValue ) ? $field->defaultValue : self::IS_NULL );
		}
	}

	public function default_value_textarea_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'defaultValue', ! empty( $field->defaultValue ) ? $field->defaultValue : self::IS_NULL );
	}

	public function delete_icon_url_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'deleteIconUrl', ! empty( $field->deleteIconUrl ) ? $field->deleteIconUrl : self::IS_NULL );
	}

	public function description_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'description', ! empty( $field->description ) ? $field->description : self::IS_NULL );
		$properties[] = $this->expectedField( 'descriptionPlacement', ! empty( $field->descriptionPlacement ) ? GFHelpers::get_enum_for_value( Enum\FormFieldDescriptionPlacementEnum::$type, $field->descriptionPlacement ) : self::IS_NULL );
	}

	public function disable_margins_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasMargins', empty( $field->disableMargins ) );
	}

	public function duplicate_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'shouldAllowDuplicates', empty( $field->noDuplicates ) );
	}

	public function email_confirm_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasEmailConfirmation', ! empty( $field->emailConfirmEnabled ) );

		$keys = [
			'autocompleteAttribute' => 'autocompleteAttribute',
			'defaultValue'          => 'defaultValue',
			'customLabel'           => 'customLabel',
			'id'                    => 'id',
			'name'                  => 'name',
			'label'                 => 'label',
			'placeholder'           => 'placeholder',
		];

		$inputs = $field->emailConfirmEnabled ? $field->inputs : [
			[
				'autocompleteAttribute' => $field->autocompleteAttribute ?? null,
				'defaultValue'          => $field->defaultValue ?? null,
				'customLabel'           => $field->customLabel ?? null,
				'id'                    => $field->id ?? null,
				'label'                 => $field->label ?? null,
				'name'                  => $field->inputName ?? null,
				'placeholder'           => $field->placeholder ?? null,
			]
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $inputs ) ? $inputs : [] );
	}

	public function enable_enhanced_ui_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasEnhancedUI', ! empty( $field->enableEnhancedUI ) );
	}

	public function error_message_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'errorMessage', ! empty( $field->errorMessage ) ? $field->errorMessage : self::IS_NULL );
	}

	public function file_extensions_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'allowedExtensions', explode( ',', $field->allowedExtensions ) );
	}

	public function file_size_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'maxFileSize', ! empty( $field->maxFileSize ) ? $field->maxFileSize : self::IS_NULL );
	}

	public function force_ssl_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'isSSLForced', ! empty( $field->forceSSL ) );
	}

	public function gquiz_setting_choices( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasWeightedScore', ! empty( $field->gquizWeightedScoreEnabled ) );

		$keys = [
			'isCorrect'     => 'gquizIsCorrect',
			'weight'        => 'gquizWeight',
			'isOtherChoice' => 'isOtherChoice',
		];

		$properties[] = $this->expected_choices( $keys, ! empty( $field->choices ) ? $field->choices : [] );
	}

	public function gquiz_setting_show_answer_explanation( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'shouldShowAnswerExplanation', ! empty( $field->gquizShowAnswerExplanation ) );
		$properties[] = $this->expectedField( 'answerExplanation', ! empty( $field->gquizAnswerExplanation ) ? $field->gquizAnswerExplanation : self::IS_NULL );
	}

	public function gquiz_setting_randomize_quiz_choices( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'shouldRandomizeQuizChoices', ! empty( $field->gquizEnableRandomizeQuizChoices ) );
	}

	public function gquiz_setting_question( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'label', ! empty( $field->label ) ? $field->label : self::IS_NULL );
	}

	public function input_mask_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'inputMaskValue', ! empty( $field->inputMaskValue ) ? $field->inputMaskValue : self::IS_NULL );
		$properties[] = $this->expectedField( 'hasInputMask', ! empty( $field->inputMask ) );
	}

	public function label_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'label', ! empty( $field->label ) ? $field->label : self::IS_NULL );
	}

	public function label_placement_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'labelPlacement', ! empty( $field->labelPlacement ) ? GFHelpers::get_enum_for_value( Enum\FormFieldLabelPlacementEnum::$type, $field->labelPlacement ) : self::IS_NULL );
	}

	public function maxlen_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'maxLength', isset( $field->maxLength ) ? (int) $field->maxLength : self::IS_NULL );
	}

	public function maxrows_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'maxRows', isset( $field->maxRows ) ? (int) $field->maxRows : self::IS_NULL );
	}

	public function multiple_files_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'maxFiles', isset( $field->maxFiles ) ? (int) $field->maxFiles : self::IS_NULL );
		$properties[] = $this->expectedField( 'canAcceptMultipleFiles', ! empty( $field->multipleFiles ) );
	}

	public function name_setting( GF_Field $field, array &$properties ): void {
		$input_keys = [
			'autocompleteAttribute' => 'autocompleteAttribute',
			'defaultValue'          => 'defaultValue',
			'hasChoiceValue'        => 'enableChoiceValue',
			'customLabel'           => 'customLabel',
			'id'                    => 'id',
			'isHidden'              => 'isHidden',
			'key'                   => 'key',
			'name'                  => 'name',
			'label'                 => 'label',
			'placeholder'           => 'placeholder',
		];

		$properties[] = $this->expected_inputs( $input_keys, ! empty( $field->inputs ) ? $field->inputs : [] );

		$choice_keys  = [
			'isSelected' => 'isSelected',
			'text'       => 'text',
			'value'      => 'value',
		];
		$properties[] = $this->expected_choices( $choice_keys, ! empty( $field->choices ) ? $field->choices : [] );
	}

	public function next_button_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedObject(
			'nextButton',
			[
				$this->expectedField( 'type', ! empty( $field->nextButton['type'] ) ? GFHelpers::get_enum_for_value( Enum\FormButtonTypeEnum::$type, $field->nextButton['type'] ) : self::IS_NULL ),
				$this->expectedField( 'text', ! empty( $field->nextButton['text'] ) ? $field->nextButton['text'] : self::IS_NULL ),
				$this->expectedField( 'imageUrl', ! empty( $field->nextButton['imageUrl'] ) ? $field->nextButton['imageUrl'] : self::IS_NULL ),
				$this->get_expected_conditional_logic_fields( ! empty( $field->nextButton['conditionalLogic'] ) ? $field->nextButton['conditionalLogic'] : [] ),
			]
		);
	}

	public function number_format_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'numberFormat', ! empty( $field->numberFormat ) ? GFHelpers::get_enum_for_value( Enum\NumberFieldFormatEnum::$type, $field->numberFormat ) : self::IS_NULL );
	}

	public function other_choice_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasOtherChoice', ! empty( $field->enableOtherChoice ) );

		if ( 'quiz' === $field->type ) {
			return;
		}
	}

	public function password_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'isPasswordInput', ! empty( $field->enablePasswordInput ) );
	}

	public function password_setting( GF_Field $field, array &$properties ): void {
		$keys = [
			'customLabel' => 'customLabel',
			'id'          => 'id',
			'isHidden'    => 'isHidden',
			'label'       => 'label',
			'placeholder' => 'placeholder',
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function password_strength_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasPasswordStrengthIndicator', ! empty( $field->passwordStrengthEnabled ) );
		$properties[] = $this->expectedField( 'minPasswordStrength', ! empty( $field->minPasswordStrength ) ? GFHelpers::get_enum_for_value( Enum\PasswordFieldMinStrengthEnum::$type, $field->minPasswordStrength ) : self::IS_NULL );
	}

	public function password_visibility_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasPasswordVisibilityToggle', ! empty( $field->passwordVisibilityEnabled ) );
	}

	public function pen_color_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'penColor', ! empty( $field->penColor ) ? $field->penColor : self::IS_NULL );
	}

	public function pen_size_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'penSize', ! empty( $field->penSize ) ? $field->penSize : self::IS_NULL );
	}

	public function phone_format_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'phoneFormat', ! empty( $field->phoneFormat ) ? GFHelpers::get_enum_for_value( Enum\PhoneFieldFormatEnum::$type, $field->phoneFormat ) : self::IS_NULL );
	}

	public function placeholder_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'placeholder', ! empty( $field->placeholder ) ? $field->placeholder : self::IS_NULL );
	}

	public function placeholder_textarea_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'placeholder', ! empty( $field->placeholder ) ? $field->placeholder : self::IS_NULL );
	}

	public function post_category_checkbox_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasAllCategories', ! empty( $field->displayAllCategories ) );
	}

	public function post_category_initial_item_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'dropdownPlaceholder', ! empty( $field->categoryInitialItem ) ? $field->categoryInitialItem : self::IS_NULL );
	}

	public function post_custom_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'postCustomFieldName', ! empty( $field->postCustomFieldName ) ? $field->postCustomFieldName : self::IS_NULL );
	}

	public function post_image_featured_image( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'isFeaturedImage', ! empty( $field->postFeaturedImage ) );
	}

	public function post_image_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'allowedExtensions', explode( ',', $field->allowedExtensions ) );
		$properties[] = $this->expectedField( 'hasAlt', ! empty( $field->displayAlt ) );
		$properties[] = $this->expectedField( 'hasCaption', ! empty( $field->displayCaption ) );
		$properties[] = $this->expectedField( 'hasDescription', ! empty( $field->displayDescription ) );
		$properties[] = $this->expectedField( 'hasTitle', ! empty( $field->displayTitle ) );
	}

	public function prepopulate_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'canPrepopulate', ! empty( $field->allowsPrepopulate ) );

		if ( ( empty( $field->inputs ) && ! in_array( $field->type, [ 'date', 'email', 'time', 'password' ], true ) ) || 'checkbox' === $field->type ) {
			$properties[] = $this->expectedField( 'inputName', ! empty( $field->inputName ) ? $field->inputName : self::IS_NULL );
		}
	}

	public function previous_button_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedObject(
			'previousButton',
			[
				$this->expectedField( 'type', ! empty( $field->previousButton['type'] ) ? GFHelpers::get_enum_for_value( Enum\FormButtonTypeEnum::$type, $field->previousButton['type'] ) : self::IS_NULL ),
				$this->expectedField( 'text', ! empty( $field->previousButton['text'] ) ? $field->previousButton['text'] : self::IS_NULL ),
				$this->expectedField( 'imageUrl', ! empty( $field->previousButton['imageUrl'] ) ? $field->previousButton['imageUrl'] : self::IS_NULL ),
				$this->get_expected_conditional_logic_fields( ! empty( $field->previousButton['conditionalLogic'] ) ? $field->previousButton['conditionalLogic'] : [] ),
			]
		);
	}

	public function product_field_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'productField', isset( $field->productField ) ? (int) $field->productField : self::IS_NULL );
	}

	public function range_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'rangeMax', isset( $field->rangeMax ) ? (float) $field->rangeMax : self::IS_NULL );
		$properties[] = $this->expectedField( 'rangeMin', isset( $field->rangeMin ) ? (float) $field->rangeMin : self::IS_NULL );
	}

	public function rich_text_editor_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasRichTextEditor', ! empty( $field->useRichTextEditor ) );
	}

	public function rules_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'isRequired', ! empty( $field->isRequired ) );
	}

	public function size_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'size', ! empty( $field->size ) ? GFHelpers::get_enum_for_value( Enum\FormFieldSizeEnum::$type, $field->size ) : self::IS_NULL );
	}

	public function select_all_choices_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'hasSelectAll', ! empty( $field->enableSelectAll ) );

		$keys = [
			'id'    => 'id',
			'name'  => 'name',
			'label' => 'label',
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function sub_label_placement_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'subLabelPlacement', GFHelpers::get_enum_for_value( Enum\FormFieldSubLabelPlacementEnum::$type, ! empty( $field->subLabelPlacement ) ? $field->subLabelPlacement : 'inherit' ) );
	}

	public function time_format_setting( GF_Field $field, array &$properties ): void {
		$properties[] = $this->expectedField( 'timeFormat', ! empty( $field->timeFormat ) ? GFHelpers::get_enum_for_value( Enum\TimeFieldFormatEnum::$type, $field->timeFormat ) : self::IS_NULL );

		$keys = [
			'autocompleteAtribute' => 'autocompleteAtribute',
			'defaultValue'         => 'defaultValue',
			'customLabel'          => 'customLabel',
			'id'                   => 'id',
			'label'                => 'label',
			'placeholder'          => 'placeholder',
		];

		$properties[] = $this->expected_inputs( $keys, ! empty( $field->inputs ) ? $field->inputs : [] );
	}

	public function expected_choices( array $keys, array $choices ): ?array {
		$expected_nodes = [];
		if ( empty( $choices ) ) {
			return $this->expectedField( 'choices', self::IS_NULL );
		}

		foreach ( $choices as $index => $choice ) {
			$expected_fields = [];

			foreach ( $keys as $name => $key ) {
				switch ( $name ) {
					case 'price':
						$expected_fields[] = $this->expectedField( $name, ! empty( $choice['price'] ) ? floatval( preg_replace( '/[^\d\.]/', '', $choice['price'] ) ) : self::IS_NULL );
						break;
					case 'choices':
						$expected_fields[] = $this->expected_choices( $keys, $choice['choices'] ?? [] );
						break;
					case 'value':
						$expected_fields[] = $this->expectedField( $name, (string) $choice[ $key ] );
						break;
					default:
						$expected_fields[] = $this->expectedField( $name, $choice[ $key ] ?? self::IS_NULL );
						break;
				}
			}

			$expected_nodes[] = $this->expectedObject(
				(string) $index,
				$expected_fields
			);
		}

		return $this->expectedObject(
			'choices',
			$expected_nodes
		);
	}

	public function expected_inputs( array $keys, array $inputs ): array {
		$expected_nodes = [];
		foreach ( $inputs as $index => $input ) {
			$expected_fields = [];
			foreach ( $keys as $name => $key ) {
				switch ( $name ) {
					case 'id':
						$expected_fields[] = $this->expectedField( $name, isset( $input[ $key ] ) ? (float) $input[ $key ] : self::IS_NULL );
						break;
					case 'defaultValue':
						$expected_fields[] = $this->expectedField( $name, isset( $input[ $key ] ) ? (string) $input[ $key ] : self::IS_NULL );
						break;
					case 'hasChoiceValue':
					case 'isHidden':
						$expected_fields[] = $this->expectedField( $name, ! empty( $input[ $key ] ) );
						break;
					default:
						$expected_fields[] = $this->expectedField( $name, isset( $input[ $key ] ) ? $input[ $key ] : self::IS_NULL );
				}
			}

			$expected_nodes[] = $this->expectedObject(
				(string) $index,
				$expected_fields
			);
		}

		return $this->expectedObject(
			'inputs',
			$expected_nodes
		);
	}

	public function expected_field_value( string $key, $values ) {
		if ( null === $values ) {
			return $this->expectedField( $key, self::IS_NULL );
		}

		if ( is_array( $values ) ) {
			$expected = [];
			foreach ( $values as $name => $value ) {
				switch ( $value ) {
					case 'codecept_field_value_is_null':
						$value = self::IS_NULL;
						break;
					case 'codecept_field_value_not_null':
						$value = self::NOT_NULL;
						break;
					case 'codecept_field_value_not_falsy':
						$value = self::NOT_FALSY;
						break;
					case 'codecept_field_value_is_falsy':
						$value = self::IS_FALSY;
						break;
				}
				switch ( (string) $name ) {
					case 'amPm':
						$expected[] = $this->expectedField( $name, isset( $value ) ? GFHelpers::get_enum_for_value( AmPmEnum::$type, $value ) : self::IS_NULL );
						break;
					case 'url':
						$expected[] = $this->expectedField( $name, ! empty( $value ) ? self::NOT_FALSY : self::IS_NULL );
						break;
					case 'basePath':
						$expected[] = $this->expectedField( $name, $this->is_draft ? self::IS_NULL : self::NOT_FALSY );
						break;
					default:
						$expected[] = $this->expectedField( $name, $value );
				}
			}

			return $this->expectedObject(
				$key,
				$expected
			);
		}

		return $this->expectedField( $key, $values );
	}
}
