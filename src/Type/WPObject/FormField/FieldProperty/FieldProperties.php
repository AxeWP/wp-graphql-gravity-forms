<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\WPObject\Button\FormButton;
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
				'type'        => Enum\AddressFieldTypeEnum::$type,
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
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'A comma-delimited list of the file extensions which may be uploaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->allowedExtensions ) ? explode( ',', $source->allowedExtensions ) : null,
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
	 */
	public static function calculation_rounding() : array {
		return [
			'calculationRounding' => [
				'type'        => 'Int',
				'description' => __( 'Specifies to how many decimal places the number should be rounded. This is available when isCalculation is true, but is not available when the chosen format is “Currency”.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calendarIconType' property.
	 */
	public static function calendar_icon_type() : array {
		return [
			'calendarIconType' => [
				'type'        => Enum\FormFieldCalendarIconTypeEnum::$type,
				'description' => __( 'Determines how the date field displays it’s calendar icon.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'calendarIconUrl' property.
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
	 * Get 'canAcceptMultipleFiles' property.
	 */
	public static function can_accept_multiple_files() : array {
		return [
			'canAcceptMultipleFiles' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->multipleFiles ),
			],
		];
	}

	/**
	 * Get 'canPrepopulate' property.
	 */
	public static function can_prepopulate() : array {
		return [
			'canPrepopulate' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field’s value can be pre-populated dynamically.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->allowsPrepopulate ),
			],
		];
	}

	/**
	 * Get 'captchaBadgePosition' property.
	 */
	public static function captcha_badge_position() : array {
		return [
			'captchaBadgePosition' => [
				'type'        => Enum\CaptchaFieldBadgePositionEnum::$type,
				'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => isset( $source->captchaBadge ) ? $source->captchaBadge : 'bottomright',
			],
		];
	}

	/**
	 * Get 'captchaLanguage' property.
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
				'type'        => Enum\CaptchaFieldThemeEnum::$type,
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
				'type'        => Enum\CaptchaFieldTypeEnum::$type,
				'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $root ) => $root['captchaType'] ?: 'recaptcha',
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
				'description' => __( 'Text of the consent checkbox.', 'wp-graphql-gravity-forms' ),
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
	 * Get the 'formattedPrice' choice property.
	 */
	public static function choice_formatted_price() : array {
		return [
			'formattedPrice' => [
				'type'        => 'String',
				'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source['price'] ) ? $source['price'] : null,
			],
		];
	}

	/**
	 * Get the 'price' choice property.
	 */
	public static function choice_price() : array {
		return [
			'price' => [
				'type'        => 'Float',
				'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source['price'] ) ? floatval( preg_replace( '/[^\d\.]/', '', $source['price'] ) ) : null,
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
	 * Get 'copyValuesOptionFieldId' property.
	 */
	public static function copy_values_option_field_id() : array {
		return [
			'copyValuesOptionFieldId' => [
				'type'        => 'Int',
				'description' => __( 'The field id of the field being used as the copy source.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->copyValuesOptionField ) ? $source->copyValuesOptionField : null,
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
				'description' => __( 'The label that appears next to the copy values option when the form is displayed. The default value is \“Same as previous\”.', 'wp-graphql-gravity-forms' ),
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
				'resolve'     => function ( $source ) {
					return $source->cssClass;
				},
			],
		];
	}

	/**
	 * Get 'dateFormat' property.
	 */
	public static function date_format() : array {
		return [
			'dateFormat' => [
				'type'        => Enum\DateFieldFormatEnum::$type,
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
				'type'        => Enum\DateFieldTypeEnum::$type,
				'description' => __( 'The type of date field to display.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultCountry' property.
	 */
	public static function default_country() : array {
		return [
			'defaultCountry' => [
				'type'        => Enum\AddressFieldCountryEnum::$type,
				'description' => __( 'Contains the country that will be selected by default. Only applicable when "addressType" is set to "INTERATIONAL".', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'defaultProvince' property.
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
	 * Get 'dropdownPlaceholder' property.
	 */
	public static function dropdown_placeholder() : array {
		return [
			'dropdownPlaceholder' => [
				'type'        => 'String',
				'description' => __( 'The dropdown placeholder for the field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->categoryInitialItem ) ? $source->categoryInitialItem : null,
			],
		];
	}

	/**
	 * Get 'descriptionPlacement' property.
	 */
	public static function description_placement() : array {
		return [
			'descriptionPlacement' => [
				'type'        => Enum\FormFieldDescriptionPlacementEnum::$type,
				'description' => __( 'The placement of the field description.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source->descriptionPlacement ) ? $source->descriptionPlacement : 'inherit';
				},
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
	 * Get 'formattedPrice' property.
	 */
	public static function formatted_price() : array {
		return [
			'formattedPrice' => [
				'type'        => 'String',
				'description' => __( 'The price of the product, prefixed by the currency.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->basePrice ) ? $source->basePrice : null,
			],
		];
	}

	/**
	 * Get 'hasAllCategories' property.
	 */
	public static function has_all_categories() : array {
		return [
			'hasAllCategories' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if all categories should be displayed on the Post Category drop down. If this property is true (display all categories), the Post Category drop down will display the categories hierarchically.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayAllCategories ),
			],
		];
	}

	/**
	 * Get 'hasAlt' property.
	 */
	public static function has_alt() : array {
		return [
			'hasAlt' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the alt metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayAlt ),
			],
		];
	}

	/**
	 * Get 'hasAutocomplete' property.
	 */
	public static function has_autocomplete() : array {
		return [
			'hasAutocomplete' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether autocomplete should be enabled for this field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableAutocomplete ),
			],
		];
	}

	/**
	 * Get 'hasCaption' property.
	 */
	public static function has_caption() : array {
		return [
			'hasCaption' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the caption metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayCaption ),
			],
		];
	}

	/**
	 * Get 'hasChoiceValue' property.
	 */
	public static function has_choice_value() : array {
		return [
			'hasChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableChoiceValue ),
			],
		];
	}

	/**
	 * Get 'hasColumns' property.
	 */
	public static function has_columns() : array {
		return [
			'hasColumns' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field should use multiple columns. Default is false.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableColumns ),
			],
		];
	}

	/**
	 * Get 'hasDescription' property.
	 */
	public static function has_description() : array {
		return [
			'hasDescription' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the description metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayDescription ),
			],
		];
	}


	/**
	 * Get 'hasEmailConfirmation' property.
	 */
	public static function has_email_confirmation() : array {
		return [
			'hasEmailConfirmation' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines whether the Confirm Email field is active.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->emailConfirmEnabled ),
			],
		];
	}

	/**
	 * Get 'hasEnhancedUI' property.
	 */
	public static function has_enhanced_ui() : array {
		return [
			'hasEnhancedUI' => [
				'type'        => 'Boolean',
				'description' => __( 'When set to true, the "Chosen" jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableEnhancedUI ),
			],
		];
	}

	/**
	 * Get 'hasInputMask' property.
	 */
	public static function has_input_mask() : array {
		return [
			'hasInputMask' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the field has an input mask.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->inputMask ),
			],
		];
	}

	/**
	 * Get 'hasMargins' property.
	 */
	public static function has_margins() : array {
		return [
			'hasMargins' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the default margins are turned on to align the HTML content with other fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => empty( $source->disableMargins ),
			],
		];
	}

	/**
	 * Get 'hasOtherChoice' property.
	 */
	public static function has_other_choice() : array {
		return [
			'hasOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the \'Enable "other" choice\' option is checked in the editor.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableOtherChoice ),
			],
		];
	}

	/**
	 * Get 'hasPasswordStrengthIndicator' property.
	 */
	public static function has_password_strength_indicator() : array {
		return [
			'hasPasswordStrengthIndicator' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field displays the password strength indicator.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->passwordStrengthEnabled ),
			],
		];
	}

	/**
	 * Get 'hasPasswordVisibilityToggle' property.
	 */
	public static function has_password_visibility_toggle() : array {
		return [
			'hasPasswordVisibilityToggle' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the Password visibility toggle should be enabled for this field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->passwordVisibilityEnabled ),
			],
		];
	}

	/**
	 * Get 'hasPrice' property.
	 */
	public static function has_price() : array {
		return [
			'hasPrice' => [
				'type'        => 'Boolean',
				'description' => __( 'This property is used when the radio button is a product option field and will be set to true. If not associated with a product, then it is false.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enablePrice ),
			],
		];
	}

	/**
	 * Get 'hasSelectAll' property.
	 */
	public static function has_select_all() : array {
		return [
			'hasSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the \"select all\" choice should be displayed.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->enableSelectAll ),
			],
		];
	}

	/**
	 * Get 'hasRichTextEditor' property.
	 */
	public static function has_rich_text_editor() : array {
		return [
			'hasRichTextEditor' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field uses the rich text editor interface.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->useRichTextEditor ),
			],
		];
	}

	/**
	 * Get 'hasTitle' property.
	 */
	public static function has_title() : array {
		return [
			'hasTitle' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the title metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->displayTitle ),
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
	 * Get 'inputMaskValue' property.
	 */
	public static function input_mask_value() : array {
		return [
			'inputMaskValue' => [
				'type'        => 'String',
				'description' => __( 'The pattern used for the input mask.', 'wp-graphql-gravity-forms' ),
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
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'isCalculation' property.
	 */
	public static function is_calculation() : array {
		return [
			'isCalculation' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the number field is a calculation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableCalculation ),
			],
		];
	}

	/**
	 * Get 'isFeaturedImage' property.
	 */
	public static function is_featured_image() : array {
		return [
			'isFeaturedImage' => [
				'type'        => 'Boolean',
				'description' => __( "Whether the image field should be used to set the post's Featured Image", 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->postFeaturedImage ),
			],
		];
	}

	/**
	 * Get 'isPasswordInput' property.
	 */
	public static function is_password_input() : array {
		return [
			'isPasswordInput' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if a text field input tag should be created with a "password" type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enablePasswordInput ),
			],
		];
	}

	/**
	 * Get 'isQuantityDisabled' property.
	 */
	public static function is_quantity_disabled() : array {
		return [
			'isQuantityDisabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the quantity property should be disabled for this field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->disableQuantity ),
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
	 * Get 'isSSLForced' property.
	 */
	public static function is_ssl_forced() : array {
		return [
			'isSSLForced' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field requires the user to enter a value. Fields marked as required will prevent the form from being submitted if the user has not entered a value in it.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->forceSSL ),
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
				'type'        => Enum\FormFieldLabelPlacementEnum::$type,
				'description' => __( 'The field label position.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source->labelPlacement ) ? $source->labelPlacement : 'inherit';
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
	 */
	public static function max_files() : array {
		return [
			'maxFiles' => [
				'type'        => 'Int',
				'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'maxFileSize' property.
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
				'type'        => Enum\PasswordFieldMinStrengthEnum::$type,
				'description' => __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' ),
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
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'nextButton' property.
	 */
	public static function next_button() : array {
		return [
			'nextButton' => [
				'type'        => FormButton::$type,
				'description' => __( 'An array containing the the individual properties for the "Next" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'numberFormat' property.
	 */
	public static function number_format() : array {
		return [
			'numberFormat' => [
				'type'        => Enum\NumberFieldFormatEnum::$type,
				'description' => __( 'Specifies the format allowed for the number field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'phoneFormat' property.
	 */
	public static function phone_format() : array {
		return [
			'phoneFormat' => [
				'type'        => Enum\PhoneFieldFormatEnum::$type,
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
	 */
	public static function post_custom_field_name() : array {
		return [
			'postMetaFieldName' => [
				'type'        => 'String',
				'description' => __( 'The post meta key to which the value should be assigned.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source) => ! empty( $source->postCustomFieldName ) ? $source->postCustomFieldName : null,
			],
		];
	}

	/**
	 * Get 'previousButton' property.
	 */
	public static function previous_button() : array {
		return [
			'previousButton' => [
				'type'        => FormButton::$type,
				'description' => __( 'An array containing the the individual properties for the "Previous" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get 'price' property.
	 */
	public static function price() : array {
		return [
			'price' => [
				'type'        => 'Float',
				'description' => __( 'The price of the product.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->basePrice ) ? floatval( preg_replace( '/[^\d\.]/', '', $source->basePrice ) ) : null,
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
	 * Get 'shouldAllowDuplicates' property.
	 */
	public static function should_allow_duplicates() : array {
		return [
			'shouldAllowDuplicates' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field allows duplicate submissions.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => empty( $source->noDuplicates ),
			],
		];
	}

	/**
	 * Get 'shouldCopyValuesOption' property.
	 */
	public static function should_copy_values_option() : array {
		return [
			'shouldCopyValuesOption' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the copy values option can be used. This option allows users to skip filling out the field and use the same values as another. For example, if the mailing and billing address are the same.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableCopyValuesOption ),
			],
		];
	}

	/**
	 * Get 'size' property.
	 */
	public static function size() : array {
		return [
			'size' => [
				'type'        => Enum\FormFieldSizeEnum::$type,
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
				'type'        => Enum\FormFieldSizeEnum::$type,
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
				'type'        => Enum\FormFieldSubLabelPlacementEnum::$type,
				'description' => __( 'The placement of the labels for the subfields within the group. This setting controls all of the subfields, they cannot be set individually. They may be aligned above or below the inputs. If this property is not set, the “Sub-Label Placement” setting on the Form Settings->Form Layout page is used. If no setting is specified, the default is above inputs.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source->subLabelPlacement ) ? $source->subLabelPlacement : 'inherit';
				},
			],
		];
	}


	/**
	 * Get 'supportedCreditCards' property.
	 */
	public static function supported_credit_cards() : array {
		return [
			'supportedCreditCards' => [
				'type'        => [ 'list_of' => Enum\FormCreditCardTypeEnum::$type ],
				'description' => __( 'The credit card type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->creditCards ) ? $source->creditCards : null,
			],
		];
	}


	/**
	 * Get 'timeFormat' property.
	 */
	public static function time_format() : array {
		return [
			'timeFormat' => [
				'type'        => Enum\TimeFieldFormatEnum::$type,
				'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
