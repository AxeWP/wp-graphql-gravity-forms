<?php

namespace WPGraphQLGravityForms;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Form;
use WPGraphQLGravityForms\Types\Field;
use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Union;

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
		// Forms
		$this->instances['form_button']       = new Form\FormButton;
		$this->instances['save_and_continue'] = new Form\SaveAndContinue;
		$this->instances['form_notification'] = new Form\FormNotification;
		$this->instances['form_confirmation'] = new Form\FormConfirmation;
		$this->instances['form']              = new Form\Form;

		// Fields
		$this->instances['address_field']       = new Field\AddressField;
		$this->instances['captcha_field']       = new Field\CaptchaField;
		$this->instances['checkbox_field']      = new Field\CheckboxField;
		$this->instances['date_field']          = new Field\DateField;
		$this->instances['email_field']         = new Field\EmailField;
		$this->instances['file_upload_field']   = new Field\FileUploadField;
		$this->instances['hidden_field']        = new Field\HiddenField;
		$this->instances['html_field']          = new Field\HtmlField;
		$this->instances['name_field']          = new Field\NameField;
		$this->instances['number_field']        = new Field\NumberField;
		$this->instances['phone_field']         = new Field\PhoneField;
		$this->instances['post_category_field'] = new Field\PostCategoryField;
		$this->instances['post_content_field']  = new Field\PostContentField;
		$this->instances['post_custom_field']   = new Field\PostCustomField;
		$this->instances['post_excerpt_field']  = new Field\PostExcerptField;
		$this->instances['post_image_field']    = new Field\PostImageField;
		$this->instances['post_tags_field']     = new Field\PostTagsField;
		$this->instances['post_title_field']    = new Field\PostTitleField;
		$this->instances['radio_field']         = new Field\RadioField;
		$this->instances['section_field']       = new Field\SectionField;
		$this->instances['select_field']        = new Field\SelectField;
		$this->instances['text_area_field']     = new Field\TextAreaField;
		$this->instances['text_field']          = new Field\TextField;
		$this->instances['time_field']          = new Field\TimeField;
		$this->instances['website_field']       = new Field\WebsiteField;

		// Field Properties
		$this->instances['choice_property'] = new FieldProperty\ChoiceProperty;
		$this->instances['input_property']  = new FieldProperty\InputProperty;

		// Unions
		$this->instances['form_field_union'] = new Union\FormFieldUnion( $this->instances );

		// Entries
		// $this->instances['entry'] = new Types\Entry;
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
