<?php
/**
 * Registers GF types to the GraphQL schema.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Registry;

use Exception;
use WPGraphQL\GF\Connection;
use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Mutation;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\Input;
use WPGraphQL\GF\Type\WPInterface;
use WPGraphQL\GF\Type\WPObject;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - TypeRegistry
 */
class TypeRegistry {
	/**
	 * The local registry of registered types.
	 *
	 * @var array
	 */
	public static array $registry = [];

	/**
	 * The list of registered classes.
	 *
	 * @var class-string[]
	 */
	public static array $registered_classes;

	/**
	 * Gets an array of all the registered GraphQL types along with their class name.
	 */
	public static function get_registered_types(): array {
		if ( empty( self::$registry ) ) {
			self::initialize_registry();
		}

		return self::$registry;
	}

	/**
	 * Gets an array of all the registered GraphQL types along with their class name.
	 */
	public static function get_registered_classes(): array {
		if ( empty( self::$registered_classes ) ) {
			self::initialize_registry();
		}

		return self::$registered_classes;
	}

	/**
	 * Registers types, connections, unions, and mutations to GraphQL schema.
	 */
	public static function init(): void {
		/**
		 * Fires before all types have been registered.
		 */
		do_action( 'graphql_gf_before_register_types' );

		self::initialize_registry();

		/**
		 * Fires after all types have been registered.
		 */
		do_action( 'graphql_gf_after_register_types' );
	}

	/**
	 * Initializes the GF type registry. If $type_registry is nulled, these fields wont be (re)-registered to WPGraphQL.
	 */
	private static function initialize_registry(): void {
		$classes_to_register = array_merge(
			self::enums(),
			self::inputs(),
			self::interfaces(),
			self::objects(),
			self::fields(),
			self::connections(),
			self::mutations(),
		);

		self::$registered_classes = $classes_to_register;

		self::register_types( self::$registered_classes );
	}

	/**
	 * List of Enum classes to register.
	 *
	 * @return class-string[]
	 */
	private static function enums(): array {
		// Enums to register.
		$classes_to_register = [
			Enum\AddressFieldCountryEnum::class,
			Enum\AddressFieldProvinceEnum::class,
			Enum\AddressFieldStateEnum::class,
			Enum\AddressFieldTypeEnum::class,
			Enum\AmPmEnum::class,
			Enum\CaptchaFieldBadgePositionEnum::class,
			Enum\CaptchaFieldThemeEnum::class,
			Enum\CaptchaFieldTypeEnum::class,
			Enum\CurrencyEnum::class,
			Enum\ConditionalLogicActionTypeEnum::class,
			Enum\ConditionalLogicLogicTypeEnum::class,
			Enum\DateFieldFormatEnum::class,
			Enum\DateFieldTypeEnum::class,
			Enum\DraftEntryIdTypeEnum::class,
			Enum\EntryIdTypeEnum::class,
			Enum\EntryStatusEnum::class,
			Enum\EntryTypeEnum::class,
			Enum\FieldFiltersModeEnum::class,
			Enum\FieldFiltersOperatorInputEnum::class,
			Enum\FormButtonTypeEnum::class,
			Enum\FormConfirmationTypeEnum::class,
			Enum\FormCreditCardTypeEnum::class,
			Enum\FormDescriptionPlacementEnum::class,
			Enum\FormFieldCalendarIconTypeEnum::class,
			Enum\FormFieldDescriptionPlacementEnum::class,
			Enum\FormFieldLabelPlacementEnum::class,
			Enum\FormFieldRequiredIndicatorEnum::class,
			Enum\FormFieldSizeEnum::class,
			Enum\FormFieldSubLabelPlacementEnum::class,
			Enum\FormFieldTypeEnum::class,
			Enum\FormFieldVisibilityEnum::class,
			Enum\FormIdTypeEnum::class,
			Enum\FormLabelPlacementEnum::class,
			Enum\FormLimitEntriesPeriodEnum::class,
			Enum\FormNotificationToTypeEnum::class,
			Enum\FormPageProgressStyleEnum::class,
			Enum\FormPageProgressTypeEnum::class,
			Enum\FormRetentionPolicyEnum::class,
			Enum\FormRuleOperatorEnum::class,
			Enum\FormStatusEnum::class,
			Enum\FormSubLabelPlacementEnum::class,
			Enum\FormSubmitButtonLocationEnum::class,
			Enum\FormSubmitButtonWidthEnum::class,
			Enum\FormsConnectionOrderByEnum::class,
			Enum\NumberFieldFormatEnum::class,
			Enum\PasswordFieldMinStrengthEnum::class,
			Enum\PhoneFieldFormatEnum::class,
			Enum\PostFormatTypeEnum::class,
			Enum\RecaptchaTypeEnum::class,
			Enum\SubmissionConfirmationTypeEnum::class,
			Enum\SubmittedEntryIdTypeEnum::class,
			Enum\TimeFieldFormatEnum::class,
		];

		/**
		 * Filters the list of enum classes to register.
		 *
		 * Useful for adding/removing GF specific enums to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_enum_classes', $classes_to_register );
	}

	/**
	 * List of Input classes to register.
	 *
	 * @return class-string[]
	 */
	private static function inputs(): array {
		$classes_to_register = [
			Input\AddressFieldInput::class,
			Input\CheckboxFieldInput::class,
			Input\CreditCardFieldInput::class,
			Input\EmailFieldInput::class,
			Input\EntriesConnectionOrderbyInput::class,
			Input\EntriesDateFiltersInput::class,
			Input\EntriesFieldFiltersInput::class,
			Input\FormsConnectionOrderbyInput::class,
			Input\ListFieldInput::class,
			Input\NameFieldInput::class,
			Input\ProductFieldInput::class,
			Input\SubmitFormMetaInput::class,
			Input\UpdateDraftEntryMetaInput::class,
			Input\UpdateEntryMetaInput::class,
		];

		if ( Utils::is_graphql_upload_enabled() ) {
			$classes_to_register[] = Input\PostImageFieldInput::class;
		}

		// Register late, since it depends on above inputs.
		$classes_to_register[] = Input\FormFieldValuesInput::class;

		/**
		 * Filters the list of input classes to register.
		 *
		 * Useful for adding/removing GF specific inputs to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_input_classes', $classes_to_register );
	}

	/**
	 * List of Interface classes to register.
	 *
	 * @return class-string[]
	 */
	public static function interfaces(): array {
		$classes_to_register = [
			WPInterface\Entry::class,
			WPInterface\FormField::class,
			WPInterface\FieldChoice::class,
			WPInterface\FieldWithChoices::class,
			... array_values( self::form_field_setting_choices() ),
			WPInterface\FieldWithInputs::class,
			WPInterface\FieldInput::class,
			...array_values( self::form_field_setting_inputs() ),
			WPInterface\FieldWithPersonalData::class,
			...array_values( self::form_field_settings() ),
			WPInterface\NodeWithForm::class,
			WPinterface\FieldValue\FieldValueWithChoice::class,
			WPinterface\FieldValue\FieldValueWithInput::class,
		];

		/**
		 * Filters the list of interfaces classes to register.
		 *
		 * Useful for adding/removing GF specific interfaces to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_interface_classes', $classes_to_register );
	}

	/**
	 * List of Object classes to register.
	 *
	 * @return class-string[]
	 */
	public static function objects(): array {
		$classes_to_register = [
			// Buttons.
			WPObject\Button\FormButton::class,
			WPObject\Button\FormLastPageButton::class,
			// Conditional Logic.
			WPObject\ConditionalLogic\ConditionalLogic::class,
			WPObject\ConditionalLogic\ConditionalLogicRule::class,
			// Orders.
			WPObject\Order\OrderItemOption::class,
			WPObject\Order\OrderItem::class,
			WPObject\Order\OrderSummary::class,
			// Entries.
			WPObject\Entry\DraftEntry::class,
			WPObject\Entry\SubmittedEntry::class,
			// Forms.
			WPObject\Form\Form::class,
			WPObject\Form\FormConfirmation::class,
			WPObject\Form\FormDataPolicies::class,
			WPObject\Form\FormEntryLimits::class,
			WPObject\Form\FormEntryDataPolicy::class,
			WPObject\Form\FormLogin::class,
			WPObject\Form\FormNotification::class,
			WPObject\Form\FormNotificationRouting::class,
			WPObject\Form\FormPagination::class,
			WPObject\Form\FormPersonalData::class,
			WPObject\Form\FormPostCreation::class,
			WPObject\Form\FormScheduleDetails::class,
			WPObject\Form\FormSchedule::class,
			WPObject\Form\FormSaveAndContinue::class,
			WPObject\Form\FormSubmitButton::class,
			WPObject\FormField\FormFieldDataPolicy::class,
			// Form Field Value properties.
			WPObject\FormField\FieldValue\ValueProperty\AddressFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\CheckboxFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\ImageFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\FileUploadFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\ListFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\NameFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\ProductFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\TimeFieldValue::class,
			// Form Fields.
			WPObject\FormField\FormFields::class,
			// Field Error.
			WPObject\FieldError::class,
			// Submission Confirmation.
			WPObject\SubmissionConfirmation::class,
			// GF Settings.
			WPObject\Settings\Logger::class,
			WPObject\Settings\SettingsLogging::class,
			WPObject\Settings\SettingsRecaptcha::class,
			WPObject\Settings\Settings::class,
		];

		/**
		 * Filters the list of object classes to register.
		 *
		 * Useful for adding/removing GF specific objects to the schema.
		 *
		 * @param array $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_object_classes', $classes_to_register );
	}

	/**
	 * Returns an array of Gravity Forms field settings mapped to their corresponding PHP class.
	 *
	 * @return array<string, class-string>
	 */
	public static function form_field_settings(): array {
		// Key-value pairs of settings and their corresponding class name.
		$classes_to_register = [
			'add_icon_url_setting'               => WPInterface\FieldSetting\FieldWithAddIconUrl::class,
			'address_setting'                    => WPInterface\FieldSetting\FieldWithAddress::class,
			'admin_label_setting'                => WPInterface\FieldSetting\FieldWithAdminLabel::class,
			'autocomplete_setting'               => WPInterface\FieldSetting\FieldWithAutocomplete::class,
			'base_price_setting'                 => WPInterface\FieldSetting\FieldWithBasePrice::class,
			'calculation_setting'                => WPInterface\FieldSetting\FieldWithCalculation::class,
			'captcha_badge_setting'              => WPInterface\FieldSetting\FieldWithCaptchaBadge::class,
			'captcha_bg_setting'                 => WPInterface\FieldSetting\FieldWithCaptchaBackground::class,
			'captcha_fg_setting'                 => WPInterface\FieldSetting\FieldWithCaptchaForeground::class,
			'captcha_language_setting'           => WPInterface\FieldSetting\FieldWithCaptchaLanguage::class,
			'captcha_size_setting'               => WPInterface\FieldSetting\FieldWithCaptchaSize::class,
			'captcha_theme_setting'              => WPInterface\FieldSetting\FieldWithCaptchaTheme::class,
			'captcha_type_setting'               => WPInterface\FieldSetting\FieldWithCaptchaType::class,
			'checkbox_label_setting'             => WPInterface\FieldSetting\FieldWithCheckboxLabel::class,
			'choices_setting'                    => WPInterface\FieldSetting\FieldWithChoices::class,
			'columns_setting'                    => WPInterface\FieldSetting\FieldWithColumns::class,
			'conditional_logic_setting'          => WPInterface\FieldSetting\FieldWithConditionalLogic::class,
			'content_setting'                    => WPInterface\FieldSetting\FieldWithContent::class,
			'copy_values_option'                 => WPInterface\FieldSetting\FieldWithCopyValuesOption::class,
			'credit_card_setting'                => WPInterface\FieldSetting\FieldWithCreditCard::class,
			'css_class_setting'                  => WPInterface\FieldSetting\FieldWithCssClass::class,
			'date_format_setting'                => WPInterface\FieldSetting\FieldWithDateFormat::class,
			'date_input_type_setting'            => WPInterface\FieldSetting\FieldWithDateInputType::class,
			'default_value_setting'              => WPInterface\FieldSetting\FieldWithDefaultValue::class,
			'delete_icon_url_setting'            => WPInterface\FieldSetting\FieldWithDeleteIconUrl::class,
			'description_setting'                => WPInterface\FieldSetting\FieldWithDescription::class,
			'disable_margins_setting'            => WPInterface\FieldSetting\FieldWithDisableMargins::class,
			'disable_quantity_setting'           => WPInterface\FieldSetting\FieldWithDisableQuantity::class,
			'duplicate_setting'                  => WPInterface\FieldSetting\FieldWithDuplicates::class,
			'email_confirm_setting'              => WPInterface\FieldSetting\FieldWithEmailConfirmation::class,
			'enable_enhanced_ui_setting'         => WPInterface\FieldSetting\FieldWithEnhancedUI::class,
			'error_message_setting'              => WPInterface\FieldSetting\FieldWithErrorMessage::class,
			'file_extensions_setting'            => WPInterface\FieldSetting\FieldWithFileExtensions::class,
			'file_size_setting'                  => WPInterface\FieldSetting\FieldWithFileSize::class,
			'force_ssl_field_setting'            => WPInterface\FieldSetting\FieldWithForceSSLField::class,
			'input_mask_setting'                 => WPInterface\FieldSetting\FieldWithInputMask::class,
			'label_setting'                      => WPInterface\FieldSetting\FieldWithLabel::class,
			'label_placement_setting'            => WPInterface\FieldSetting\FieldWithLabelPlacement::class,
			'maxlen_setting'                     => WPInterface\FieldSetting\FieldWithMaxLength::class,
			'maxrows_setting'                    => WPInterface\FieldSetting\FieldWithMaxRows::class,
			'multiple_files_setting'             => WPInterface\FieldSetting\FieldWithMultipleFiles::class,
			'name_setting'                       => WPInterface\FieldSetting\FieldWithName::class,
			'next_button_setting'                => WPInterface\FieldSetting\FieldWithNextButton::class,
			'number_format_setting'              => WPInterface\FieldSetting\FieldWithNumberFormat::class,
			'other_choice_setting'               => WPInterface\FieldSetting\FieldWithOtherChoice::class,
			'password_setting'                   => WPInterface\FieldSetting\FieldWithPassword::class,
			'password_field_setting'             => WPInterface\FieldSetting\FieldWithPasswordField::class,
			'password_strength_setting'          => WPInterface\FieldSetting\FieldWithPasswordStrength::class,
			'password_visibility_setting'        => WPInterface\FieldSetting\FieldWithPasswordVisibility::class,
			'phone_format_setting'               => WPInterface\FieldSetting\FieldWithPhoneFormat::class,
			'placeholder_setting'                => WPInterface\FieldSetting\FieldWithPlaceholder::class,
			'post_category_checkbox_setting'     => WPInterface\FieldSetting\FieldWithPostCategoryCheckbox::class,
			'post_category_initial_item_setting' => WPInterface\FieldSetting\FieldWithPostCategoryInitialItem::class,
			'post_custom_field_setting'          => WPInterface\FieldSetting\FieldWithPostCustomField::class,
			'post_image_setting'                 => WPInterface\FieldSetting\FieldWithPostImage::class,
			'post_image_featured_image'          => WPInterface\FieldSetting\FieldWithPostImageFeaturedImage::class,
			'prepopulate_field_setting'          => WPInterface\FieldSetting\FieldWithPrepopulateField::class,
			'previous_button_setting'            => WPInterface\FieldSetting\FieldWithPreviousButton::class,
			'product_field_setting'              => WPInterface\FieldSetting\FieldWithProductField::class,
			'range_setting'                      => WPInterface\FieldSetting\FieldWithRange::class,
			'rich_text_editor_setting'           => WPInterface\FieldSetting\FieldWithRichTextEditor::class,
			'rules_setting'                      => WPInterface\FieldSetting\FieldWithRules::class,
			'select_all_choices_setting'         => WPInterface\FieldSetting\FieldWithSelectAllChoices::class,
			'single_product_inputs'              => WPInterface\FieldSetting\FieldWithSingleProductInputs::class,
			'size_setting'                       => WPInterface\FieldSetting\FieldWithSize::class,
			'sub_label_placement_setting'        => WPInterface\FieldSetting\FieldWithSubLabelPlacement::class,
			'time_format_setting'                => WPInterface\FieldSetting\FieldWithTimeFormat::class,
		];

		/**
		 * Filters the list of PHP classes that register GraphQL Interfaces based on a particular Gravity Forms field setting.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		$classes_to_register = apply_filters( 'graphql_gf_registered_form_field_setting_classes', $classes_to_register );

		return $classes_to_register;
	}

	/**
	 * Returns an array of Gravity Forms field settings mapped to their InputProperty's corresponding interface PHP class.
	 *
	 * @return array<string, class-string>
	 */
	public static function form_field_setting_inputs(): array {
		// Key-value pairs of settings and their corresponding class name.
		$classes_to_register = [
			'address_setting'            => WPInterface\FieldInputSetting\InputWithAddress::class,
			'date_format_setting'        => WPInterface\FieldInputSetting\InputWithDateFormat::class,
			'email_confirm_setting'      => WPInterface\FieldInputSetting\InputWithEmailConfirmation::class,
			'name_setting'               => WPInterface\FieldInputSetting\InputWithName::class,
			'password_setting'           => WPInterface\FieldInputSetting\InputWithPassword::class,
			'select_all_choices_setting' => WPInterface\FieldInputSetting\InputWithSelectAllChoices::class,
			'time_format_setting'        => WPInterface\FieldInputSetting\InputWithTimeFormat::class,
			'single_product_inputs'      => WPInterface\FieldInputSetting\InputWithSingleProduct::class,
		];

		/**
		 * Filters the list of PHP classes that register GraphQL Interfaces for Form Field choices based on a particular Gravity Forms field setting.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		$classes_to_register = apply_filters( 'graphql_gf_registered_form_field_setting_input_classes', $classes_to_register );

		return $classes_to_register;
	}

	/**
	 * Returns an array of Gravity Forms field settings mapped to their FieldChoice's corresponding interface PHP class.
	 *
	 * @return array<string, class-string>
	 */
	public static function form_field_setting_choices(): array {
		// Key-value pairs of settings and their corresponding class name.
		$classes_to_register = [
			'choices_setting'      => WPInterface\FieldChoiceSetting\ChoiceWithChoices::class,
			'columns_setting'      => WPInterface\FieldChoiceSetting\ChoiceWithColumns::class,
			'name_setting'         => WPInterface\FieldChoiceSetting\ChoiceWithName::class,
			'other_choice_setting' => WPInterface\FieldChoiceSetting\ChoiceWithOtherChoice::class,
		];

		/**
		 * Filters the list of PHP classes that register GraphQL Interfaces for Form Field choices based on a particular Gravity Forms field setting.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		$classes_to_register = apply_filters( 'graphql_gf_registered_form_field_setting_choice_classes', $classes_to_register );

		return $classes_to_register;
	}

	/**
	 * List of Field classes to register.
	 */
	public static function fields(): array {
		$classes_to_register = [];

		/**
		 * Filters the list of field classes to register.
		 *
		 * Useful for adding/removing GF specific fields to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_field_classes', $classes_to_register );
	}

	/**
	 * List of Connection classes to register.
	 */
	public static function connections(): array {
		$classes_to_register = [
			Connection\EntriesConnection::class,
			Connection\FormsConnection::class,
			Connection\FormFieldsConnection::class,
		];

		/**
		 * Filters the list of connection classes to register.
		 *
		 * Useful for adding/removing GF specific connections to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_connection_classes', $classes_to_register );
	}

	/**
	 * Registers mutation.
	 */
	public static function mutations(): array {
		$classes_to_register = [
			Mutation\DeleteDraftEntry::class,
			Mutation\DeleteEntry::class,
			Mutation\SubmitDraftEntry::class,
			Mutation\SubmitForm::class,
			Mutation\UpdateDraftEntry::class,
			Mutation\UpdateEntry::class,
		];

		/**
		 * Filters the list of connection classes to register.
		 *
		 * Useful for adding/removing GF specific connections to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		$classes_to_register = apply_filters( 'graphql_gf_registered_mutation_classes', $classes_to_register );

		return $classes_to_register;
	}

	/**
	 * Loops through a list of classes to manually register each GraphQL to the registry, and stores the type name and class in the local registry.
	 *
	 * Classes must extend WPGraphQL\Type\AbstractType.
	 *
	 * @param array $classes_to_register .
	 *
	 * @throws \Exception .
	 */
	private static function register_types( array $classes_to_register ): void {
		// Bail if there are no classes to register.
		if ( empty( $classes_to_register ) ) {
			return;
		}

		foreach ( $classes_to_register as $class ) {
			if ( ! is_a( $class, Hookable::class, true ) ) {
				// translators: PHP class.
				throw new Exception( sprintf( __( 'To be registered to the GF GraphQL schema, %s needs to implement WPGraphQL\Interfaces\Registrable.', 'wp-graphql-gravity-forms' ), $class ) );
			}

			$class::register_hooks();

			// Saves the Type => ClassName to the local registry.
			if ( isset( $class::$type ) ) {
				self::$registry[ $class::$type ] = $class;
			}
		}
	}
}
