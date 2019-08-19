<?php

namespace WPGraphQLGravityForms;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\DataManipulators;
use WPGraphQLGravityForms\Types\Button\Button;
use WPGraphQLGravityForms\Types\ConditionalLogic;
use WPGraphQLGravityForms\Types\Form;
use WPGraphQLGravityForms\Types\Field;
use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Field\FieldValue;
use WPGraphQLGravityForms\Types\Union;
use WPGraphQLGravityForms\Types\Connection;
use WPGraphQLGravityForms\Types\Entry;
use WPGraphQLGravityForms\Mutations;

/**
 * Main plugin class.
 */
final class WPGraphQLGravityForms {
	/**
	 * Class instances.
	 */
    private $instances = [];

	/**
	 * Main method for running the plugin.
	 */
	public function run() {
		$this->create_instances();
		$this->register_hooks();
    }

	private function create_instances() {
		// Data manipulators
		$this->instances['fields_data_manipulator'] = new DataManipulators\FieldsDataManipulator();
		$this->instances['form_data_manipulator']   = new DataManipulators\FormDataManipulator( $this->instances['fields_data_manipulator'] );
		$this->instances['entry_data_manipulator']  = new DataManipulators\EntryDataManipulator();

		// Buttons
		$this->instances['button'] = new Button();

		// Conditional Logic
		$this->instances['conditional_logic']      = new ConditionalLogic\ConditionalLogic();
		$this->instances['conditional_logic_rule'] = new ConditionalLogic\ConditionalLogicRule();

		// Forms
		$this->instances['save_and_continue']         = new Form\SaveAndContinue();
		$this->instances['form_notification_routing'] = new Form\FormNotificationRouting();
		$this->instances['form_notification']         = new Form\FormNotification();
		$this->instances['form_confirmation']         = new Form\FormConfirmation();
		$this->instances['form_pagination']           = new Form\FormPagination();
		$this->instances['form']                      = new Form\Form( $this->instances['form_data_manipulator'] );

		// Fields
		$this->instances['address_field']        = new Field\AddressField();
		$this->instances['calculation_field']    = new Field\CalculationField();
		$this->instances['captcha_field']        = new Field\CaptchaField();
		$this->instances['chained_select_field'] = new Field\ChainedSelectField();
		$this->instances['checkbox_field']       = new Field\CheckboxField();
		$this->instances['date_field']           = new Field\DateField();
		$this->instances['email_field']          = new Field\EmailField();
		$this->instances['file_upload_field']    = new Field\FileUploadField();
		$this->instances['hidden_field']         = new Field\HiddenField();
		$this->instances['html_field']           = new Field\HtmlField();
		$this->instances['list_field']           = new Field\ListField();
		$this->instances['multi_select_field']   = new Field\MultiSelectField();
		$this->instances['name_field']           = new Field\NameField();
		$this->instances['number_field']         = new Field\NumberField();
		$this->instances['page_field']           = new Field\PageField();
		$this->instances['password_field']       = new Field\PasswordField();
		$this->instances['phone_field']          = new Field\PhoneField();
		$this->instances['post_category_field']  = new Field\PostCategoryField();
		$this->instances['post_content_field']   = new Field\PostContentField();
		$this->instances['post_custom_field']    = new Field\PostCustomField();
		$this->instances['post_excerpt_field']   = new Field\PostExcerptField();
		$this->instances['post_image_field']     = new Field\PostImageField();
		$this->instances['post_tags_field']      = new Field\PostTagsField();
		$this->instances['post_title_field']     = new Field\PostTitleField();
		$this->instances['radio_field']          = new Field\RadioField();
		$this->instances['section_field']        = new Field\SectionField();
		$this->instances['signature_field']      = new Field\SignatureField();
		$this->instances['select_field']         = new Field\SelectField();
		$this->instances['text_area_field']      = new Field\TextAreaField();
		$this->instances['text_field']           = new Field\TextField();
		$this->instances['time_field']           = new Field\TimeField();
		$this->instances['website_field']        = new Field\WebsiteField();

		// Field Properties
		$this->instances['chained_select_choice_property'] = new FieldProperty\ChainedSelectChoiceProperty();
		$this->instances['checkbox_input_property']        = new FieldProperty\CheckboxInputProperty();
		$this->instances['choice_property']                = new FieldProperty\ChoiceProperty();
		$this->instances['input_property']                 = new FieldProperty\InputProperty();
		$this->instances['list_choice_property']           = new FieldProperty\ListChoiceProperty();
		$this->instances['multi_select_choice_property']   = new FieldProperty\MultiSelectChoiceProperty();
		$this->instances['password_input_property']        = new FieldProperty\PasswordInputProperty();

		// Field Values
		$this->instances['address_field_values']        = new FieldValue\AddressFieldValues();
		$this->instances['chained_select_field_values'] = new FieldValue\ChainedSelectFieldValues();
		$this->instances['file_upload_field_value']     = new FieldValue\FileUploadFieldValue();
		$this->instances['multi_select_field_values']   = new FieldValue\MultiSelectFieldValues();
		$this->instances['name_field_values']           = new FieldValue\NameFieldValues();
		$this->instances['signature_field_value']       = new FieldValue\SignatureFieldValue();
		$this->instances['string_field_value']          = new FieldValue\StringFieldValue();
		$this->instances['time_field_values']           = new FieldValue\TimeFieldValues();

		// Entries
		$this->instances['entries_date_fiters_input']  = new Entry\EntriesDateFiltersInput();
		$this->instances['entries_field_fiters_input'] = new Entry\EntriesFieldFiltersInput();
		$this->instances['entry']                      = new Entry\Entry( $this->instances['entry_data_manipulator'] );
		$this->instances['entry_form']                 = new Entry\EntryForm( $this->instances['form_data_manipulator'] );
		$this->instances['entry_user']                 = new Entry\EntryUser();

		// Unions
		$this->instances['object_field_union']       = new Union\ObjectFieldUnion( $this->instances );
		$this->instances['object_field_value_union'] = new Union\ObjectFieldValueUnion( $this->instances );

		// Connections
		$this->instances['form_field_connection']         = new Connections\FormFieldConnection();
		$this->instances['entry_field_connection']        = new Connections\EntryFieldConnection( $this->instances );
		$this->instances['root_query_entries_connection'] = new Connections\RootQueryEntriesConnection();

		// Mutations
		$this->instances['entry_text_value_input'] = new Mutations\EntryTextValueInput();
		$this->instances['create_entry_mutation']  = new Mutations\CreateEntryMutation( $this->instances['entry_data_manipulator'] );
	}

	private function register_hooks() {
		foreach ( $this->get_hookable_instances() as $instance ) {
			$instance->register_hooks();
		}
	}

	private function get_hookable_instances() {
		return array_filter( $this->instances, function( $instance ) {
			return $instance instanceof Hookable;
		} );
	}
}

// @TODO: Handle this more gracefully to bump up the number of form fields returned.
add_filter( 'graphql_connection_max_query_amount', function() {
	return 500;
}, 11 );
