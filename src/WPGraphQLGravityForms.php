<?php
/**
 * Initializes a singleton instance of WPGraphQLGravityForms.
 *
 * @package WPGraphQLGravityForms
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Button\Button;
use WPGraphQLGravityForms\Types\Button\LastPageButton;
use WPGraphQLGravityForms\Types\ConditionalLogic;
use WPGraphQLGravityForms\Types\Enum;
use WPGraphQLGravityForms\Types\Form;
use WPGraphQLGravityForms\Types\Field;
use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;
use WPGraphQLGravityForms\Types\Field\FieldValue;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Union;
use WPGraphQLGravityForms\Types\Entry;
use WPGraphQLGravityForms\Types\Input;
use WPGraphQLGravityForms\Types\GraphQLInterface;
/**
 * Main plugin class.
 */
final class WPGraphQLGravityForms {
	/**
	 * Class instances.
	 *
	 * @var array $instances
	 */
	private static $instances = [];

	/**
	 * Main method for running the plugin.
	 */
	public static function run() : void {
		self::create_instances();
		self::register_hooks();
	}

	/**
	 * Returns the plugin's class instances.
	 */
	public static function instances() : array {
		if ( empty( self::$instances ) ) {
			self::create_instances();
		}
		return self::$instances;
	}

	/**
	 * Create instances.
	 */
	private static function create_instances() : void {
		// Settings.
		self::$instances['wpgraphql_settings'] = new Settings\WPGraphQLSettings();

		// Data manipulators.
		self::$instances['fields_data_manipulator']      = new DataManipulators\FieldsDataManipulator();
		self::$instances['form_data_manipulator']        = new DataManipulators\FormDataManipulator( self::$instances['fields_data_manipulator'] );
		self::$instances['entry_data_manipulator']       = new DataManipulators\EntryDataManipulator();
		self::$instances['draft_entry_data_manipulator'] = new DataManipulators\DraftEntryDataManipulator( self::$instances['entry_data_manipulator'] );

		// Data loaders.
		self::$instances['loader_registrar'] = new Data\Loader\LoadersRegistrar();

		// Buttons.
		self::$instances['button']           = new Button();
		self::$instances['last_page_button'] = new LastPageButton();

		// Conditional Logic.
		self::$instances['conditional_logic']      = new ConditionalLogic\ConditionalLogic();
		self::$instances['conditional_logic_rule'] = new ConditionalLogic\ConditionalLogicRule();

		// Forms.
		self::$instances['save_and_continue']         = new Form\SaveAndContinue();
		self::$instances['form_notification_routing'] = new Form\FormNotificationRouting();
		self::$instances['form_notification']         = new Form\FormNotification();
		self::$instances['form_confirmation']         = new Form\FormConfirmation();
		self::$instances['form_pagination']           = new Form\FormPagination();
		self::$instances['form']                      = new Form\Form( self::$instances['form_data_manipulator'] );

		// Field Properties.
		self::$instances['address_input_property']         = new FieldProperty\AddressInputProperty();
		self::$instances['chained_select_choice_property'] = new FieldProperty\ChainedSelectChoiceProperty();
		self::$instances['chained_select_input_property']  = new FieldProperty\ChainedSelectInputProperty();
		self::$instances['checkbox_input_property']        = new FieldProperty\CheckboxInputProperty();
		self::$instances['choice_property']                = new FieldProperty\ChoiceProperty();
		self::$instances['date_input_property']            = new FieldProperty\DateInputProperty();
		self::$instances['email_input_property']           = new FieldProperty\EmailInputProperty();
		self::$instances['input_property']                 = new FieldProperty\InputProperty();
		self::$instances['list_choice_property']           = new FieldProperty\ListChoiceProperty();
		self::$instances['name_input_property']            = new FieldProperty\NameInputProperty();
		self::$instances['password_input_property']        = new FieldProperty\PasswordInputProperty();
		self::$instances['radio_choice_property']          = new FieldProperty\RadioChoiceProperty();

		// Interfaces.
		self::$instances['field_interface'] = new GraphQLInterface\FormFieldInterface();

		// Fields.
		self::$instances['address_field']        = new Field\AddressField();
		self::$instances['captcha_field']        = new Field\CaptchaField();
		self::$instances['chained_select_field'] = new Field\ChainedSelectField();
		self::$instances['checkbox_field']       = new Field\CheckboxField();
		self::$instances['consent_field']        = new Field\ConsentField();
		self::$instances['date_field']           = new Field\DateField();
		self::$instances['email_field']          = new Field\EmailField();
		self::$instances['file_upload_field']    = new Field\FileUploadField();
		self::$instances['hidden_field']         = new Field\HiddenField();
		self::$instances['html_field']           = new Field\HtmlField();
		self::$instances['list_field']           = new Field\ListField();
		self::$instances['multiselect_field']    = new Field\MultiSelectField();
		self::$instances['name_field']           = new Field\NameField();
		self::$instances['number_field']         = new Field\NumberField();
		self::$instances['page_field']           = new Field\PageField();
		self::$instances['password_field']       = new Field\PasswordField();
		self::$instances['phone_field']          = new Field\PhoneField();
		self::$instances['post_category_field']  = new Field\PostCategoryField();
		self::$instances['post_content_field']   = new Field\PostContentField();
		self::$instances['post_custom_field']    = new Field\PostCustomField();
		self::$instances['post_excerpt_field']   = new Field\PostExcerptField();
		self::$instances['post_image_field']     = new Field\PostImageField();
		self::$instances['post_tags_field']      = new Field\PostTagsField();
		self::$instances['post_title_field']     = new Field\PostTitleField();
		self::$instances['radio_field']          = new Field\RadioField();
		self::$instances['section_field']        = new Field\SectionField();
		self::$instances['select_field']         = new Field\SelectField();
		self::$instances['signature_field']      = new Field\SignatureField();
		self::$instances['textarea_field']       = new Field\TextAreaField();
		self::$instances['text_field']           = new Field\TextField();
		self::$instances['time_field']           = new Field\TimeField();
		self::$instances['website_field']        = new Field\WebsiteField();

		// Field Values.
		self::$instances['address_field_value']        = new FieldValue\AddressFieldValue();
		self::$instances['chained_select_field_value'] = new FieldValue\ChainedSelectFieldValue();
		self::$instances['checkbox_input_value']       = new FieldValue\CheckboxInputValue();
		self::$instances['checkbox_field_values']      = new FieldValue\CheckboxFieldValue();
		self::$instances['consent_field_values']       = new FieldValue\ConsentFieldValue();
		self::$instances['date_field_values']          = new FieldValue\DateFieldValue();
		self::$instances['email_field_value']          = new FieldValue\EmailFieldValue();
		self::$instances['hidden_field_value']         = new FieldValue\HiddenFieldValue();
		self::$instances['file_upload_field_value']    = new FieldValue\FileUploadFieldValue();
		self::$instances['list_input_value']           = new FieldValue\ListInputValue();
		self::$instances['list_field_values']          = new FieldValue\ListFieldValue();
		self::$instances['multi_select_field_value']   = new FieldValue\MultiSelectFieldValue();
		self::$instances['name_field_value']           = new FieldValue\NameFieldValue();
		self::$instances['number_field_value']         = new FieldValue\NumberFieldValue();
		self::$instances['phone_field_values']         = new FieldValue\PhoneFieldValue();
		self::$instances['post_category_field_value']  = new FieldValue\PostCategoryFieldValue();
		self::$instances['post_content_field_value']   = new FieldValue\PostContentFieldValue();
		self::$instances['post_custom_field_value']    = new FieldValue\PostCustomFieldValue();
		self::$instances['post_excerpt_field_value']   = new FieldValue\PostExcerptFieldValue();
		self::$instances['post_image_field_value']     = new FieldValue\PostImageFieldValue();
		self::$instances['post_tags_field_value']      = new FieldValue\PostTagsFieldValue();
		self::$instances['post_title_field_value']     = new FieldValue\PostTitleFieldValue();
		self::$instances['radio_field_values']         = new FieldValue\RadioFieldValue();
		self::$instances['select_field_value']         = new FieldValue\SelectFieldValue();
		self::$instances['signature_field_value']      = new FieldValue\SignatureFieldValue();
		self::$instances['text_area_field_value']      = new FieldValue\TextAreaFieldValue();
		self::$instances['text_field_value']           = new FieldValue\TextFieldValue();
		self::$instances['time_field_value']           = new FieldValue\TimeFieldValue();
		self::$instances['website_field_value']        = new FieldValue\WebsiteFieldValue();

		// Field Value Property.
		self::$instances['address_value_property']              = new ValueProperty\AddressValueProperty();
		self::$instances['checkbox_value_property']             = new ValueProperty\CheckboxValueProperty();
		self::$instances['list_value_property']                 = new ValueProperty\ListValueProperty();
		self::$instances['name_value_property']                 = new ValueProperty\NameValueProperty();
		self::$instances['post_image_value_property']           = new ValueProperty\PostImageValueProperty();
		self::$instances['time_value_property']                 = new ValueProperty\TimeValueProperty();
		self::$instances['address_field_value_property']        = new ValueProperty\AddressFieldValueProperty();
		self::$instances['chained_select_field_value_property'] = new ValueProperty\ChainedSelectFieldValueProperty();
		self::$instances['checkbox_field_value_property']       = new ValueProperty\CheckboxFieldValueProperty();
		self::$instances['consent_field_value_property']        = new ValueProperty\ConsentFieldValueProperty();
		self::$instances['date_field_value_property']           = new ValueProperty\DateFieldValueProperty();
		self::$instances['email_field_value_property']          = new ValueProperty\EmailFieldValueProperty();
		self::$instances['file_upload_field_value_property']    = new ValueProperty\FileUploadFieldValueProperty();
		self::$instances['hidden_field_value_property']         = new ValueProperty\HiddenFieldValueProperty();
		self::$instances['list_field_value_property']           = new ValueProperty\ListFieldValueProperty();
		self::$instances['multiselect_field_value_property']    = new ValueProperty\MultiSelectFieldValueProperty();
		self::$instances['name_field_value_property']           = new ValueProperty\NameFieldValueProperty();
		self::$instances['number_field_value_property']         = new ValueProperty\NumberFieldValueProperty();
		self::$instances['phone_field_value_property']          = new ValueProperty\PhoneFieldValueProperty();
		self::$instances['post_category_field_value_property']  = new ValueProperty\PostCategoryFieldValueProperty();
		self::$instances['post_content_field_value_property']   = new ValueProperty\PostContentFieldValueProperty();
		self::$instances['post_custom_field_value_property']    = new ValueProperty\PostCustomFieldValueProperty();
		self::$instances['post_excerpt_field_value_property']   = new ValueProperty\PostExcerptFieldValueProperty();
		self::$instances['post_image_field_value_property']     = new ValueProperty\PostImageFieldValueProperty();
		self::$instances['post_tags_field_value_property']      = new ValueProperty\PostTagsFieldValueProperty();
		self::$instances['post_title_field_value_property']     = new ValueProperty\PostTitleFieldValueProperty();
		self::$instances['radio_field_value_property']          = new ValueProperty\RadioFieldValueProperty();
		self::$instances['select_field_value_property']         = new ValueProperty\SelectFieldValueProperty();
		self::$instances['signature_field_value_property']      = new ValueProperty\SignatureFieldValueProperty();
		self::$instances['text_area_field_value_property']      = new ValueProperty\TextAreaFieldValueProperty();
		self::$instances['text_field_value_property']           = new ValueProperty\TextFieldValueProperty();
		self::$instances['time_field_value_property']           = new ValueProperty\TimeFieldValueProperty();
		self::$instances['website_field_value_property']        = new ValueProperty\WebsiteFieldValueProperty();

		// Entries.
		self::$instances['entry']      = new Entry\Entry( self::$instances['entry_data_manipulator'], self::$instances['draft_entry_data_manipulator'] );
		self::$instances['entry_form'] = new Entry\EntryForm( self::$instances['form_data_manipulator'] );
		self::$instances['entry_user'] = new Entry\EntryUser();

		// Input.
		self::$instances['address_input']              = new Input\AddressInput();
		self::$instances['chained_select_input']       = new Input\ChainedSelectInput();
		self::$instances['checkbox_input']             = new Input\CheckboxInput();
		self::$instances['email_input']                = new Input\EmailInput();
		self::$instances['list_input']                 = new Input\ListInput();
		self::$instances['name_input']                 = new Input\NameInput();
		self::$instances['post_image_input']           = new Input\PostImageInput();
		self::$instances['entries_date_fiters_input']  = new Input\EntriesDateFiltersInput();
		self::$instances['entries_field_fiters_input'] = new Input\EntriesFieldFiltersInput();
		self::$instances['entries_sorting_input']      = new Input\EntriesSortingInput();
		self::$instances['field_values_input']         = new Input\FieldValuesInput();
		self::$instances['forms_sorting_input']        = new Input\FormsSortingInput();

		// Unions.
		self::$instances['object_field_value_union'] = new Union\ObjectFieldValueUnion();

		// Connections.
		self::$instances['entry_connections'] = new Connections\EntryConnections();
		self::$instances['form_connections']  = new Connections\FormConnections();
		self::$instances['field_connections'] = new Connections\FieldConnections();

		// Enums.
		self::$instances['address_type_enum']                   = new Enum\AddressTypeEnum();
		self::$instances['button_type_enum']                    = new Enum\ButtonTypeEnum();
		self::$instances['calendar_icon_type_enum']             = new Enum\CalendarIconTypeEnum();
		self::$instances['captcha_theme_enum']                  = new Enum\CaptchaThemeEnum();
		self::$instances['captcha_type_enum']                   = new Enum\CaptchaTypeEnum();
		self::$instances['chained_selects_alignmnet_enum']      = new Enum\ChainedSelectsAlignmentEnum();
		self::$instances['conditional_logic_action_type_enum']  = new Enum\ConditionalLogicActionTypeEnum();
		self::$instances['conditional_logic_logic_type_enum']   = new Enum\ConditionalLogicLogicTypeEnum();
		self::$instances['confirmation_type_enum']              = new Enum\ConfirmationTypeEnum();
		self::$instances['date_field_format_enum']              = new Enum\DateFieldFormatEnum();
		self::$instances['date_type_enum']                      = new Enum\DateTypeEnum();
		self::$instances['description_placement_property_enum'] = new Enum\DescriptionPlacementPropertyEnum();
		self::$instances['entry_status_enum']                   = new Enum\EntryStatusEnum();
		self::$instances['field_filters_mode_enum']             = new Enum\FieldFiltersModeEnum();
		self::$instances['field_filters_operator_input_enum']   = new Enum\FieldFiltersOperatorInputEnum();
		self::$instances['form_description_placement_enum']     = new Enum\FormDescriptionPlacementEnum();
		self::$instances['form_fields_enum']                    = new Enum\FormFieldsEnum();
		self::$instances['form_label_placement_enum']           = new Enum\FormLabelPlacementEnum();
		self::$instances['form_limit_entries_period_enum']      = new Enum\FormLimitEntriesPeriodEnum();
		self::$instances['form_status_enum']                    = new Enum\FormStatusEnum();
		self::$instances['form_sub_label_placement_enum']       = new Enum\FormSubLabelPlacementEnum();
		self::$instances['id_type_enum']                        = new Enum\IdTypeEnum();
		self::$instances['label_placement_property_enum']       = new Enum\LabelPlacementPropertyEnum();
		self::$instances['min_password_strength_enum']          = new Enum\MinPasswordStrengthEnum();
		self::$instances['notification_to_type_enum']           = new Enum\NotificationToTypeEnum();
		self::$instances['number_field_format_enum']            = new Enum\NumberFieldFormatEnum();
		self::$instances['page_progress_style_enum']            = new Enum\PageProgressStyleEnum();
		self::$instances['page_progress_type_enum']             = new Enum\PageProgressTypeEnum();
		self::$instances['phone_field_format_enum']             = new Enum\PhoneFieldFormatEnum();
		self::$instances['required_indicator_enum']             = new Enum\RequiredIndicatorEnum();
		self::$instances['rule_operator_enum']                  = new Enum\RuleOperatorEnum();
		self::$instances['signature_border_style_enum']         = new Enum\SignatureBorderStyleEnum();
		self::$instances['signature_border_width_enum']         = new Enum\SignatureBorderWidthEnum();
		self::$instances['size_property_enum']                  = new Enum\SizePropertyEnum();
		self::$instances['sorting_input_enum']                  = new Enum\SortingInputEnum();
		self::$instances['time_field_format_enum']              = new Enum\TimeFieldFormatEnum();
		self::$instances['visibility_property_enum']            = new Enum\VisibilityPropertyEnum();

		// Field errors.
		self::$instances['field_error'] = new FieldError();

		// Mutations.
		self::$instances['create_draft_entry']                            = new Mutations\CreateDraftEntry();
		self::$instances['delete_draft_entry']                            = new Mutations\DeleteDraftEntry();
		self::$instances['delete_entry']                                  = new Mutations\DeleteEntry();
		self::$instances['submit_draft_entry']                            = new Mutations\SubmitDraftEntry( self::$instances['entry_data_manipulator'] );
		self::$instances['submit_form']                                   = new Mutations\SubmitForm();
		self::$instances['update_draft_entry_address_field_value']        = new Mutations\UpdateDraftEntryAddressFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_chained_select_field_value'] = new Mutations\UpdateDraftEntryChainedSelectFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_checkbox_field_value']       = new Mutations\UpdateDraftEntryCheckboxFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_consent_field_value']        = new Mutations\UpdateDraftEntryConsentFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_date_field_value']           = new Mutations\UpdateDraftEntryDateFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_email_field_value']          = new Mutations\UpdateDraftEntryEmailFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_hidden_field_value']         = new Mutations\UpdateDraftEntryHiddenFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_list_field_value']           = new Mutations\UpdateDraftEntryListFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_multi_select_field_value']   = new Mutations\UpdateDraftEntryMultiSelectFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_name_field_value']           = new Mutations\UpdateDraftEntryNameFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_number_field_value']         = new Mutations\UpdateDraftEntryNumberFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_phone_field_value']          = new Mutations\UpdateDraftEntryPhoneFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_category_field_value']  = new Mutations\UpdateDraftEntryPostCategoryFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_content_field_value']   = new Mutations\UpdateDraftEntryPostContentFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_custom_field_value']    = new Mutations\UpdateDraftEntryPostCustomFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_excerpt_field_value']   = new Mutations\UpdateDraftEntryPostExcerptFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_tags_field_value']      = new Mutations\UpdateDraftEntryPostTagsFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_post_title_field_value']     = new Mutations\UpdateDraftEntryPostTitleFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_radio_field_value']          = new Mutations\UpdateDraftEntryRadioFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_select_field_value']         = new Mutations\UpdateDraftEntrySelectFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_signature_field_value']      = new Mutations\UpdateDraftEntrySignatureFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_text_area_field_value']      = new Mutations\UpdateDraftEntryTextAreaFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_text_field_value']           = new Mutations\UpdateDraftEntryTextFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_time_field_value']           = new Mutations\UpdateDraftEntryTimeFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry_website_field_value']        = new Mutations\UpdateDraftEntryWebsiteFieldValue( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_draft_entry']                            = new Mutations\UpdateDraftEntry( self::$instances['draft_entry_data_manipulator'] );
		self::$instances['update_entry']                                  = new Mutations\UpdateEntry( self::$instances['entry_data_manipulator'] );

		/**
		 * Filter for instantiating custom WPGraphQLGF class instances.
		 *
		 * @param array $instances The currently-registered class instances.
		 */
		self::$instances = apply_filters( 'wp_graphql_gf_instances', self::$instances );
	}

	/**
	 * Register all hooks to WordPress.
	 */
	private static function register_hooks() : void {
		foreach ( self::get_hookable_instances() as $instance ) {
			$instance->register_hooks();
		}
	}

	/**
	 * Get array of all hookable instances.
	 *
	 * @return array
	 */
	private static function get_hookable_instances() : array {
		return array_filter(
			self::$instances,
			fn ( $instance) => $instance instanceof Hookable
		);
	}
}
