<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\Enum\AddressTypeEnum;
use WPGraphQL\GF\Type\Enum\CalendarIconTypeEnum;
use WPGraphQL\GF\Type\Enum\CaptchaThemeEnum;
use WPGraphQL\GF\Type\Enum\CaptchaTypeEnum;
use WPGraphQL\GF\Type\Enum\ChainedSelectsAlignmentEnum;
use WPGraphQL\GF\Type\Enum\DateFieldFormatEnum;
use WPGraphQL\GF\Type\Enum\DateTypeEnum;
use WPGraphQL\GF\Type\Enum\DescriptionPlacementPropertyEnum;
use WPGraphQL\GF\Type\Enum\LabelPlacementPropertyEnum;
use WPGraphQL\GF\Type\Enum\MinPasswordStrengthEnum;
use WPGraphQL\GF\Type\Enum\NumberFieldFormatEnum;
use WPGraphQL\GF\Type\Enum\PhoneFieldFormatEnum;
use WPGraphQL\GF\Type\Enum\SignatureBorderStyleEnum;
use WPGraphQL\GF\Type\Enum\SignatureBorderWidthEnum;
use WPGraphQL\GF\Type\Enum\SizePropertyEnum;
use WPGraphQL\GF\Type\Enum\TimeFieldFormatEnum;
use WPGraphQL\GF\Type\WPObject\Button\Button;
use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;

/**
 * Class - FieldProperties
 */
class FieldProperties {
	/**
	 * Get 'addIconUrl' property.
	 */
	public static function add_icon_url() : array {
		return [
			'addIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the add row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'addressType' property.
	 */
	public static function address_type() : array {
		return [
			'addressType' => [
				'type'        => AddressTypeEnum::$type,
				'description' => __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'adminLabel' property.
	 */
	public static function admin_label() : array {
		return [
			'adminLabel' => [
				'type'        => 'String',
				'description' => __( 'When specified, the value of this property will be used on the admin pages instead of the label. It is useful for fields with long labels.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'allowedExtensions' property.
	 */
	public static function allowed_extensions() : array {
		return [
			'allowedExtensions' => [
				'type'        => 'String',
				'description' => __( 'A comma-delimited list of the file extensions which may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'allowsPrepopulate' property.
	 */
	public static function allows_prepopulate() : array {
		return [
			'allowsPrepopulate' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field’s value can be pre-populated dynamically.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'autocompleteAttribute' property.
	 */
	public static function autocomplete_attribute() : array {
		return [
			'autocompleteAttribute' => [
				'type'        => 'String',
				'description' => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'answerExplanation' property.
	 */
	public static function answer_explanation() : array {
		return [
			'answerExplanation' => [
				'type'        => 'String',
				'description' => __( 'The explanation for the correct answer and/or incorrect answers.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn ( $source ) : ?string => $source['gquizAnswerExplanation'] ?? null,
			],
		];
	}

	/**
	 * Get 'backgroundColor' property.
	 */
	public static function background_color() : array {
		return [
			'backgroundColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the background of the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'borderColor' property.
	 */
	public static function border_color() : array {
		return [
			'borderColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'borderStyle' property.
	 */
	public static function border_style() : array {
		return [
			'borderStyle' => [
				'type'        => SignatureBorderStyleEnum::$type,
				'description' => __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'boxWidth' property.
	 */
	public static function box_width() : array {
		return [
			'boxWidth' => [
				'type'        => 'Int',
				'description' => __( 'Width of the signature field in pixels.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'borderWidth' property.
	 */
	public static function border_width() : array {
		return [
			'borderWidth' => [
				'type'        => SignatureBorderWidthEnum::$type,
				'description' => __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calculationFormula' property.
	 */
	public static function calculation_formula() : array {
		return [
			'calculationFormula' => [
				'type'        => 'String',
				'description' => __( 'The formula used for the number field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calculationRounding' property.
	 *
	 * @todo make Int
	 */
	public static function calculation_rounding() : array {
		return [
			'calculationRounding' => [
				'type'        => 'String',
				'description' => __( 'Specifies to how many decimal places the number should be rounded. This is available when enableCalculation is true, but is not available when the chosen format is “Currency”.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calendarIconType' property.
	 */
	public static function calendar_icon_type() : array {
		return [
			'calendarIconType' => [
				'type'        => CalendarIconTypeEnum::$type,
				'description' => __( 'Determines how the date field displays it’s calendar icon.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calendarIconUrl' property.
	 *
	 * @todo convert to enum
	 */
	public static function calendar_icon_url() : array {
		return [
			'calendarIconUrl' => [
				'type'        => 'String',
				'description' => __( 'Contains the URL to the custom calendar icon. Only applicable when calendarIconType is set to custom.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'captchaLanguage' property.
	 *
	 * @todo convert to enum
	 */
	public static function captcha_language() : array {
		return [
			'captchaLanguage' => [
				'type'        => 'String',
				'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'captchaTheme' property.
	 */
	public static function captcha_theme() : array {
		return [
			'captchaTheme' => [
				'type'        => CaptchaThemeEnum::$type,
				'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn ( $root ) => $root['captchaTheme'] ?: null,
			],
		];
	}

	/**
	 * Get 'captchaType' property.
	 */
	public static function captcha_type() : array {
		return [
			'captchaType' => [
				'type'        => CaptchaTypeEnum::$type,
				'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $root ) => $root['captchaType'] ?: 'recaptcha',
			],
		];
	}

	/**
	 * Get 'chainedSelectsAlignment' property.
	 */
	public static function chained_selects_alignment() : array {
		return [
			'chainedSelectsAlignment' => [
				'type'        => ChainedSelectsAlignmentEnum::$type,
				'description' => __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'chainedSelectsHideInactive' property.
	 */
	public static function chained_selects_hide_inactive() : array {
		return [
			'chainedSelectsHideInactive' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether inactive dropdowns should be hidden.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'checkboxLabel' property.
	 */
	public static function checkbox_label() : array {
		return [
			'checkboxLabel' => [
				'type'        => 'String',
				'description' => __( 'Text of the consent checkbox', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isCorrect' choice property.
	 */
	public static function choice_is_correct() : array {
		return [
			'isCorrect' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the choice item is the correct answer.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source['gquizIsCorrect'] ),
			],
		];
	}

	/**
	 * Get 'isOtherChoice' choice property.
	 */
	public static function choice_is_other() : array {
		return [
			'isOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the radio button item is the “Other” choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isSelected' choice property.
	 */
	public static function choice_is_selected() : array {
		return [
			'isSelected' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the 'price' choice property.
	 *
	 * @todo check type.
	 */
	public static function choice_price() : array {
		return [
			'price' => [
				'type'        => 'String',
				'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Get 'text' choice property.
	 */
	public static function choice_text() : array {
		return [
			'text' => [
				'type'        => 'String',
				'description' => __( 'The text to be displayed to the user when displaying this choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'value' choice property.
	 */
	public static function choice_value() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value to be stored in the database when this choice is selected. Note: This property is only supported by the Drop Down and Post Category fields. Checkboxes and Radio fields will store the text property in the database regardless of the value property.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'weight' choice property.
	 */
	public static function choice_weight() : array {
		return [
			'weight' => [
				'type'        => 'Float',
				'description' => __( 'The weighted score awarded for the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source, array $args, AppContext $context ) {
					if ( isset( $context->gfField->gquizWeightedScoreEnabled ) && false === $context->gfField->gquizWeightedScoreEnabled ) {
						return (float) $source['gquizIsCorrect'];
					}

					return is_numeric( $source['gquizWeight'] ) ? (float) $source['gquizWeight'] : null;
				},
			],
		];
	}

	/**
	 * Get 'conditionalLogic' property.
	 */
	public static function conditional_logic() : array {
		return [
			'conditionalLogic' => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls the visibility of the field based on values selected by the user.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'content' property.
	 */
	public static function content() : array {
		return [
			'content' => [
				'type'        => 'String',
				'description' => __( 'Content of an HTML block field to be displayed on the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'copyValuesOptionDefault' property.
	 */
	public static function copy_values_option_default() : array {
		return [
			'copyValuesOptionDefault' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the option to copy values is turned on or off by default.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'copyValuesOptionField' property.
	 */
	public static function copy_values_option_field() : array {
		return [
			'copyValuesOptionField' => [
				'type'        => 'Int',
				'description' => __( 'The field id of the field being used as the copy source.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'copyValuesOptionLabel' property.
	 */
	public static function copy_values_option_label() : array {
		return [
			'copyValuesOptionLabel' => [
				'type'        => 'String',
				'description' => __( 'The label that appears next to the copy values option when the form is displayed. The default value is “Same as previous”.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'cssClass' property.
	 */
	public static function css_class() : array {
		return [
			'cssClass' => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'dateFormat' property.
	 */
	public static function date_format() : array {
		return [
			'dateFormat' => [
				'type'        => DateFieldFormatEnum::$type,
				'description' => __( 'Determines how the date is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'dateType' property.
	 */
	public static function date_type() : array {
		return [
			'dateType' => [
				'type'        => DateTypeEnum::$type,
				'description' => __( 'The type of date field to display.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultCountry' property.
	 *
	 * @todo make enum.
	 */
	public static function default_country() : array {
		return [
			'defaultCountry' => [
				'type'        => 'String',
				'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "INTERATIONAL".', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultProvince' property.
	 *
	 * @todo make enum.
	 */
	public static function default_province() : array {
		return [
			'defaultProvince' => [
				'type'        => 'String',
				'description' => __( 'Contains the province that will be selected by default. Only applicable when "addressType" is set to "CANADA".', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultState' property.
	 *
	 * @todo make enum.
	 */
	public static function default_state() : array {
		return [
			'defaultState' => [
				'type'        => 'String',
				'description' => __( 'Contains the state that will be selected by default. Only applicable when "addressType" is set to "US".', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultValue' property.
	 */
	public static function default_value() : array {
		return [
			'defaultValue' => [
				'type'        => 'String',
				'description' => __( 'Contains the default value for the field. When specified, the field\'s value will be populated with the contents of this property when the form is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'delete_icon_url' property.
	 */
	public static function delete_icon_url() : array {
		return [
			'deleteIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the delete row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'description' property.
	 */
	public static function description() : array {
		return [
			'description' => [
				'type'        => 'String',
				'description' => __( 'Field description.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'descriptionPlacement' property.
	 */
	public static function description_placement() : array {
		return [
			'descriptionPlacement' => [
				'type'        => DescriptionPlacementPropertyEnum::$type,
				'description' => __( 'The placement of the field description.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source['descriptionPlacement'] ) ? $source['descriptionPlacement'] : 'inherit';
				},
			],
		];
	}

	/**
	 * Get 'disableMargins' property.
	 */
	public static function disable_margins() : array {
		return [
			'disableMargins' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the default margins are turned on to align the HTML content with other fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'displayAllCategories' property.
	 */
	public static function display_all_categories() : array {
		return [
			'displayAllCategories' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if all categories should be displayed on the Post Category drop down. If this property is true (display all categories), the Post Category drop down will display the categories hierarchically.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'displayAlt' property.
	 */
	public static function display_alt() : array {
		return [
			'displayAlt' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the alt metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'displayCaption' property.
	 */
	public static function display_caption() : array {
		return [
			'displayCaption' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the caption metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'displayDescription' property.
	 */
	public static function display_description() : array {
		return [
			'displayDescription' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the description metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'displayTitle' property.
	 */
	public static function display_title() : array {
		return [
			'displayTitle' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the title metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'email_confirm_enabled' property.
	 */
	public static function email_confirm_enabled() : array {
		return [
			'emailConfirmEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the Confirm Email field is active.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableAutocomplete' property.
	 */
	public static function enable_autocomplete() : array {
		return [
			'enableAutocomplete' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether autocomplete should be enabled for this field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableCalculation' property.
	 */
	public static function enable_calculation() : array {
		return [
			'enableCalculation' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the number field is a calculation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableChoiceValue' property.
	 */
	public static function enable_choice_value() : array {
		return [
			'enableChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableColumns' property.
	 */
	public static function enable_columns() : array {
		return [
			'enableColumns' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field should use multiple columns. Default is false.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableCopyValuesOption' property.
	 */
	public static function enable_copy_values_option() : array {
		return [
			'enableCopyValuesOption' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the copy values option can be used. This option allows users to skip filling out the field and use the same values as another. For example, if the mailing and billing address are the same.', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Get 'enableEnhancedUI' property.
	 */
	public static function enable_enhanced_ui() : array {
		return [
			'enableEnhancedUI' => [
				'type'        => 'Boolean',
				'description' => __( 'When set to true, the "Chosen" jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableOtherChoice' property.
	 */
	public static function enable_other_choice() : array {
		return [
			'enableOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the \'Enable "other" choice\' option is checked in the editor.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enablePasswordInput' property.
	 */
	public static function enable_password_input() : array {
		return [
			'enablePasswordInput' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if a text field input tag should be created with a "password" type.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enablePrice' property.
	 */
	public static function enable_price() : array {
		return [
			'enablePrice' => [
				'type'        => 'Boolean',
				'description' => __( 'This property is used when the radio button is a product option field and will be set to true. If not associated with a product, then it is false.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableRandomizeQuizChoices' property.
	 */
	public static function enable_randomize_quiz_choices() : array {
		return [
			'enableRandomizeQuizChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to randomize the order in which the answers are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $root['gquizEnableRandomizeQuizChoices'] ),
			],
		];
	}

	/**
	 * Get 'enableSelectAll' property.
	 */
	public static function enable_select_all() : array {
		return [
			'enableSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the "select all" choice should be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'enableWeightedScore' property.
	 */
	public static function enable_weighted_score() : array {
		return [
			'enableWeightedScore' => [
				'type'        => 'Boolean',
				'description' => __( 'If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source['gquizWeightedScoreEnabled'] ),
			],
		];
	}

	/**
	 * Get 'errorMessage' property.
	 */
	public static function error_message() : array {
		return [
			'errorMessage' => [
				'type'        => 'String',
				'description' => __( 'Contains the message that is displayed for fields that fail validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'customLabel' property for input.
	 */
	public static function input_custom_label() : array {
		return [
			'customLabel' => [
				'type'        => 'String',
				'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'id' property for input.
	 */
	public static function input_id() : array {
		return [
			'id' => [
				'type'        => 'Float',
				'description' => __( 'The input ID. Input IDs follow the following naming convention: FIELDID.INPUTID (i.e. 5.1), where FIELDID is the id of the containing field and INPUTID specifies the input field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isHidden' property for input.
	 */
	public static function input_is_hidden() : array {
		return [
			'isHidden' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'key' property for input.
	 */
	public static function input_key() : array {
		return [
			'key' => [
				'type'        => 'String',
				'description' => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'name' property for input.
	 */
	public static function input_name() : array {
		return [
			'name' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when allowsPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isRequired' property.
	 */
	public static function is_required() : array {
		return [
			'isRequired' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field requires the user to enter a value. Fields marked as required will prevent the form from being submitted if the user has not entered a value in it.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'label' property.
	 */
	public static function label() : array {
		return [
			'label' => [
				'type'        => 'String',
				'description' => __( 'Field label that will be displayed on the form and on the admin pages.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'labelPlacement' property.
	 */
	public static function label_placement() : array {
		return [
			'labelPlacement' => [
				'type'        => LabelPlacementPropertyEnum::$type,
				'description' => __( 'The field label position.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source['labelPlacement'] ) ? $source['labelPlacement'] : 'inherit';
				},
			],
		];
	}

	/**
	 * Get 'maxLength' property.
	 */
	public static function max_length() : array {
		return [
			'maxLength' => [
				'type'        => 'Int',
				'description' => __( 'Specifies the maximum number of characters allowed in a text or textarea (paragraph) field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( GF_Field $field ) : int {
					return (int) $field['maxLength'];
				},
			],
		];
	}

	/**
	 * Get 'maxFiles' property.
	 *
	 * @todo make Int
	 */
	public static function max_files() : array {
		return [
			'maxFiles' => [
				'type'        => 'String',
				'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'maxFileSize' property.
	 *
	 * @todo make Int
	 */
	public static function max_file_size() : array {
		return [
			'maxFileSize' => [
				'type'        => 'Int',
				'description' => __( 'The maximum size (in MB) an uploaded file may be .', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'maxRows' property.
	 *
	 * @todo make Int
	 */
	public static function max_rows() : array {
		return [
			'maxRows' => [
				'type'        => 'Int',
				'description' => __( 'The maximum number of rows the user can add to the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'minPasswordStrength' property.
	 */
	public static function min_password_strength() : array {
		return [
			'minPasswordStrength' => [
				'type'        => MinPasswordStrengthEnum::$type,
				'description' => __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'multipleFiles' property.
	 *
	 * @todo make Int
	 */
	public static function multiple_files() : array {
		return [
			'multipleFiles' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'inputName' property.
	 */
	public static function name() : array {
		return [
			'inputName' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when allowsPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'nameFormat' property.
	 *
	 * @todo make Enum
	 */
	public static function name_format() : array {
		return [
			'nameFormat' => [
				'type'        => 'String',
				'description' => __( 'The format of the name field. Originally, the name field could be a “normal” format with just First and Last being the fields displayed or an “extended” format which included prefix and suffix fields, or a “simple” format which just had one input field. These are legacy formats which are no longer used when adding a Name field to a form. The Name field was modified in a way which allows each of the components of the normal and extended formats to be able to be turned on or off. The nameFormat is now only “advanced”. Name fields in the previous formats are automatically upgraded to the new type if the form field is modified in the admin. The code is backwards-compatible and will continue to handle the “normal”, “extended”, “simple” formats for fields which have not yet been upgraded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'nextButton' property.
	 */
	public static function next_button() : array {
		return [
			'nextButton' => [
				'type'        => Button::$type,
				'description' => __( 'An array containing the the individual properties for the "Next" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'nameFormat' property.
	 */
	public static function number_format() : array {
		return [
			'numberFormat' => [
				'type'        => NumberFieldFormatEnum::$type,
				'description' => __( 'Specifies the format allowed for the number field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'noDuplicates' property.
	 */
	public static function no_duplicates() : array {
		return [
			'noDuplicates' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field allows duplicate submissions.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'passwordStrengthEnabled' property.
	 */
	public static function password_strength_enabled() : array {
		return [
			'passwordStrengthEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field displays the password strength indicator.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'penColor' property.
	 */
	public static function pen_color() : array {
		return [
			'penColor' => [
				'type'        => 'String',
				'description' => __( 'Color of the pen to be used for the signature. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'penSize' property.
	 */
	public static function pen_size() : array {
		return [
			'penSize' => [
				'type'        => 'Int',
				'description' => __( 'Size of the pen cursor.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'phoneFormat' property.
	 */
	public static function phone_format() : array {
		return [
			'phoneFormat' => [
				'type'        => PhoneFieldFormatEnum::$type,
				'description' => __( 'Determines the allowed format for phones. If the phone value does not conform with the specified format, the field will fail validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'placeholder' property.
	 */
	public static function placeholder() : array {
		return [
			'placeholder' => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'postCustomFieldName' property.
	 *
	 * @todo Convert to enum.
	 */
	public static function post_custom_field_name() : array {
		return [
			'postCustomFieldName' => [
				'type'        => 'String',
				'description' => __( 'The name of the Post Custom Field that the submitted value should be assigned to.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'postFeaturedImage' property.
	 */
	public static function post_featured_image() : array {
		return [
			'postFeaturedImage' => [
				'type'        => 'Boolean',
				'description' => __( "Whether the image field should be used to set the post's Featured Image", 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'previousButton' property.
	 */
	public static function previous_button() : array {
		return [
			'previousButton' => [
				'type'        => Button::$type,
				'description' => __( 'An array containing the the individual properties for the "Previous" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'productField' property.
	 */
	public static function product_field() : array {
		return [
			'productField' => [
				'type'        => 'Int',
				'description' => __( 'The id of the product field to which the field is associated.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'rangeMax' property.
	 */
	public static function range_max() : array {
		return [
			'rangeMax' => [
				'type'        => 'Float',
				'description' => __( 'Maximum allowed value for a number field. Values higher than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->rangeMax ) ? (float) $source->rangeMax : null,
			],
		];
	}

	/**
	 * Get 'rangeMin' property.
	 */
	public static function range_min() : array {
		return [
			'rangeMin' => [
				'type'        => 'Float',
				'description' => __( 'Minimum allowed value for a number field. Values lower than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->rangeMin ) ? (float) $source->rangeMin : null,
			],
		];
	}

	/**
	 * Get 'showAnswerExplanation' property.
	 */
	public static function show_answer_explanation() : array {
		return [
			'showAnswerExplanation' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to show an answer explanation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source) : bool => ! empty( $source['gquizShowAnswerExplanation'] ),
			],
		];
	}

	/**
	 * Get 'size' property.
	 */
	public static function size() : array {
		return [
			'size' => [
				'type'        => SizePropertyEnum::$type,
				'description' => __( 'Determines the size of the field when displayed on the page.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'simpleCaptchaBackgroundColor' property.
	 */
	public static function simple_captcha_background_color() : array {
		return [
			'simpleCaptchaBackgroundColor' => [
				'type'        => 'String',
				'description' => __( 'Determines the image’s background color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'simpleCaptchaFontColor' property.
	 */
	public static function simple_captcha_font_color() : array {
		return [
			'simpleCaptchaFontColor' => [
				'type'        => 'String',
				'description' => __( 'Determines the image’s font color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'simpleCaptchaFontSize' property.
	 */
	public static function simple_captcha_size() : array {
		return [
			'simpleCaptchaSize' => [
				'type'        => SizePropertyEnum::$type,
				'description' => __( 'Determines the CAPTCHA image size. Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'subLabelPlacement' property.
	 */
	public static function sub_label_placement() : array {
		return [
			'subLabelPlacement' => [
				'type'        => LabelPlacementPropertyEnum::$type,
				'description' => __( 'The placement of the labels for the subfields within the group. This setting controls all of the subfields, they cannot be set individually. They may be aligned above or below the inputs. If this property is not set, the “Sub-Label Placement” setting on the Form Settings->Form Layout page is used. If no setting is specified, the default is above inputs.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source['subLabelPlacement'] ) ? $source['subLabelPlacement'] : 'inherit';
				},
			],
		];
	}

	/**
	 * Get 'timeFormat' property.
	 */
	public static function time_format() : array {
		return [
			'timeFormat' => [
				'type'        => TimeFieldFormatEnum::$type,
				'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
	/**
	 * Get 'useRichTextEditor' property.
	 */
	public static function use_rich_text_editor() : array {
		return [
			'useRichTextEditor' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field uses the rich text editor interface.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
