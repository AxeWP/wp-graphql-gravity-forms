<?php
/**
 * Maps the Gravity Forms Field setting to the appropriate field settings.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use GF_Field;

/**
 * Class - PropertyMapper
 */
class PropertyMapper {
	/**
	 * Maps the `add_icon_url_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function add_icon_url_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::add_icon_url();
	}

	/**
	 * Maps the `address_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function address_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::address_type();
		$properties += FieldProperties::default_country();
		$properties += FieldProperties::default_province();
		$properties += FieldProperties::default_state();

		$input_fields = array_merge(
			FieldProperties::autocomplete_attribute(),
			FieldProperties::default_value(),
			FieldProperties::placeholder(),
			FieldProperties::label(),
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::input_is_hidden(),
			FieldProperties::input_key(),
			FieldProperties::input_name(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `admin_label_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function admin_label_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::admin_label();
	}

	/**
	 * Maps the `autocomplete_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function autocomplete_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_autocomplete();
		if ( ! in_array( $field->type, [ 'address', 'email', 'name' ], true ) ) {
			$properties += FieldProperties::autocomplete_attribute();
		}
	}


	/**
	 * Maps the `base_price_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function base_price_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::price();
		$properties += FieldProperties::formatted_price();
	}





	/**
	 * Maps the `calculation_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function calculation_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_calculation();
		$properties += FieldProperties::calculation_formula();
		$properties += FieldProperties::calculation_rounding();
	}

	/**
	 * Maps the `captcha_badge_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_badge_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::captcha_badge_position();
	}

	/**
	 * Maps the `captcha_bg_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_bg_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::simple_captcha_background_color();
	}

	/**
	 * Maps the `captcha_fg_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_fg_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::simple_captcha_font_color();
	}

	/**
	 * Maps the `captcha_language_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_language_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::captcha_language();
	}

	/**
	 * Maps the `captcha_size_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_size_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::simple_captcha_size();
	}

	/**
	 * Maps the `captcha_theme_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_theme_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::captcha_theme();
	}

	/**
	 * Maps the `captcha_type_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function captcha_type_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::captcha_type();
	}

	/**
	 * Maps the `checkbox_label_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function checkbox_label_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::checkbox_label();
	}

	/**
	 * Maps the `choices_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function choices_setting( GF_Field $field, array &$properties ) : void {
		$properties    += FieldProperties::has_choice_value();
		$choice_fields  = FieldProperties::choice_text();
		$choice_fields += FieldProperties::choice_value();
		$choice_fields += FieldProperties::choice_is_selected();

		// Add pricing property.
		if ( ! empty( $field->enablePrice ) || in_array( $field->type, [ 'product', 'option', 'shipping' ], true ) ) {
			$choice_fields += FieldProperties::choice_price();
			$choice_fields += FieldProperties::choice_formatted_price();
		}

		$properties += ChoiceMapper::map_choices( $field, $choice_fields );
	}


	/**
	 * Maps the `columns_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function columns_setting( GF_Field $field, array &$properties ) : void {
		$properties    += FieldProperties::has_columns();
		$choice_fields  = FieldProperties::choice_text();
		$choice_fields += FieldProperties::choice_value();

		$properties += ChoiceMapper::map_choices( $field, $choice_fields );
	}

	/**
	 * Maps the `conditional_logic_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function conditional_logic_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::conditional_logic();
	}

	/**
	 * Maps the `conditional_logic_page_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function conditional_logic_page_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::conditional_logic();
	}

	/**
	 * Maps the `content_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function content_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::content();
	}

	/**
	 * Maps the `copy_values_option` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function copy_values_option( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::should_copy_values_option();
		$properties += FieldProperties::copy_values_option_field_id();
		$properties += FieldProperties::copy_values_option_label();
	}

	/**
	 * Maps the `credit_card_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function credit_card_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::supported_credit_cards();

		$input_fields = array_merge(
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `css_class_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function css_class_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::css_class();
	}

	/**
	 * Maps the `date_format_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function date_format_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::date_format();

		$input_fields = array_merge(
			FieldProperties::autocomplete_attribute(),
			FieldProperties::default_value(),
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `date_input_type_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function date_input_type_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::date_type();
		$properties += FieldProperties::calendar_icon_type();
		$properties += FieldProperties::calendar_icon_url();
	}

	/**
	 * Maps the `default_value_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function default_value_setting( GF_Field $field, array &$properties ) : void {
		if ( 'email' !== $field->type ) {
			$properties += FieldProperties::default_value();
		}
	}

	/**
	 * Maps the `default_value_textarea_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function default_value_textarea_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::default_value();
	}

	/**
	 * Maps the `delete_icon_url_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function delete_icon_url_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::delete_icon_url();
	}

	/**
	 * Maps the `description_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function description_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::description();
	}

	/**
	 * Maps the `disable_margins_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function disable_margins_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_margins();
	}

	/**
	 * Maps the `disable_quantity_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function disable_quantity_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_quantity_disabled();
	}

	/**
	 * Maps the `duplicate_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function duplicate_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::should_allow_duplicates();
	}

	/**
	 * Maps the `email_confirm_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function email_confirm_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_email_confirmation();

		// This is not set in the settings.
		$properties += FieldProperties::sub_label_placement();

		$input_fields = array_merge(
			FieldProperties::autocomplete_attribute(),
			FieldProperties::default_value(),
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::input_name(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `error_message_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function error_message_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::error_message();
	}

	/**
	 * Maps the `enable_enhanced_ui_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function enable_enhanced_ui_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_enhanced_ui();
	}

	/**
	 * Maps the `file_extensions_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function file_extensions_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::allowed_extensions();
	}

	/**
	 * Maps the `file_size_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function file_size_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::max_file_size();
	}

	/**
	 * Maps the `force_ssl_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function force_ssl_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_ssl_forced();
	}

	/**
	 * Maps the `input_mask_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function input_mask_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::input_mask_value();
		$properties += FieldProperties::has_input_mask();
	}

	/**
	 * Maps the `label_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function label_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::label();
	}

	/**
	 * Maps the `label_placement_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function label_placement_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::description_placement();
		$properties += FieldProperties::label_placement();
	}

	/**
	 * Maps the `maxlen_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function maxlen_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::max_length();
	}

	/**
	 * Maps the `maxrows_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function maxrows_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::max_rows();
	}

	/**
	 * Maps the `multiple_files_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function multiple_files_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::max_files();
		$properties += FieldProperties::can_accept_multiple_files();
	}

	/**
	 * Maps the `name_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function name_setting( GF_Field $field, array &$properties ) : void {
		$input_fields = array_merge(
			FieldProperties::autocomplete_attribute(),
			FieldProperties::default_value(),
			FieldProperties::has_choice_value(),
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::input_is_hidden(),
			FieldProperties::input_key(),
			FieldProperties::input_name(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$choice_fields = array_merge(
			FieldProperties::choice_is_selected(),
			FieldProperties::choice_text(),
			FieldProperties::choice_value(),
		);

		$input_fields += ChoiceMapper::map_choices( $field, $choice_fields );

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `next_button_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function next_button_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::next_button();
	}

	/**
	 * Maps the `number_format_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function number_format_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::number_format();
	}

	/**
	 * Maps the `other_choice_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function other_choice_setting( GF_Field $field, array &$properties ): void {
		$properties   += FieldProperties::has_other_choice();
		$choice_fields = FieldProperties::choice_is_other();

		/**
		 * Quiz fields are registered on the QuizFieldChoice object.
		 *
		 * @todo move to GFExtensions.
		 */
		if ( 'quiz' === $field->type ) {
			return;
		}

		ChoiceMapper::add_fields_to_choice( $field, $choice_fields );
	}

	/**
	 * Maps the `password_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function password_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_password_input();
	}

	/**
	 * Maps the `password_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function password_setting( GF_Field $field, array &$properties ) : void {
		$input_fields = array_merge(
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::input_is_hidden(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `password_strength_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function password_strength_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_password_strength_indicator();
		$properties += FieldProperties::min_password_strength();
	}

	/**
	 * Maps the `password_visibility_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function password_visibility_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_password_visibility_toggle();
	}


	/**
	 * Maps the `phone_format_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function phone_format_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::phone_format();
	}

	/**
	 * Maps the `placeholder_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function placeholder_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::placeholder();
	}

	/**
	 * Maps the `placeholder_textarea_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function placeholder_textarea_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::placeholder();
	}

	/**
	 * Maps the `post_category_checkbox_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function post_category_checkbox_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_all_categories();
	}

	/**
	 * Maps the `post_category_initial_item_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function post_category_initial_item_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::dropdown_placeholder();
	}

	/**
	 * Maps the `post_custom_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function post_custom_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::post_custom_field_name();
	}

	/**
	 * Maps the `post_image_featured_image` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function post_image_featured_image( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_featured_image();
	}

	/**
	 * Maps the `post_image_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function post_image_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::allowed_extensions();
		$properties += FieldProperties::has_alt();
		$properties += FieldProperties::has_caption();
		$properties += FieldProperties::has_description();
		$properties += FieldProperties::has_title();
	}

	/**
	 * Maps the `prepopulate_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function prepopulate_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::can_prepopulate();

		if ( ( empty( $field->inputs ) && ! in_array( $field->type, [ 'email', 'time', 'password' ], true ) ) || 'checkbox' === $field->type ) {
			$properties += FieldProperties::name();
		}
	}

	/**
	 * Maps the `previous_button_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function previous_button_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::previous_button();
	}

	/**
	 * Maps the `product_field_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function product_field_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::product_field();
	}

	/**
	 * Maps the `range_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function range_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::range_max();
		$properties += FieldProperties::range_min();
	}

	/**
	 * Maps the `rich_text_editor_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function rich_text_editor_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_rich_text_editor();
	}

	/**
	 * Maps the `rules_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function rules_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::is_required();
	}

	/**
	 * Maps the `size_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function size_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::size();
	}

	/**
	 * Maps the `select_all_choices_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function select_all_choices_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::has_select_all();

		$input_fields = array_merge(
			FieldProperties::input_id(),
			FieldProperties::input_name(),
			FieldProperties::label(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}

	/**
	 * Maps the `sub_label_placement_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function sub_label_placement_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::sub_label_placement();
	}

	/**
	 * Maps the `time_format_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function time_format_setting( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::time_format();

		$input_fields = array_merge(
			FieldProperties::autocomplete_attribute(),
			FieldProperties::default_value(),
			FieldProperties::input_custom_label(),
			FieldProperties::input_id(),
			FieldProperties::label(),
			FieldProperties::placeholder(),
		);

		$properties += InputMapper::map_inputs( $field, $input_fields );
	}
}
