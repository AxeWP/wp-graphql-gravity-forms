# Gravity Forms Field Support

Gravity Forms is known for it's robust ecosystem of extenions. We aim to make it easy to support such customization, but there are limitations to what we can support out of the box.

By default, WPGraphQL for Gravity Forms adds basic query support for _all_ Form fields recognized by Gravity Forms - including custom ones.

These types inherit the `FormField` interface.

*Note:* As of v0.10.0, [Experimental fields are hidden by default and must be enabled](#experimental-fields).

Currently, only certain Form fields are supported by GraphQL mutations and [can be used to submit entries and draft entries](submitting-forms.md).

## Form Field properties (GraphQL fields) <a name="form-field-properties" />

GraphQL fields are automatically registered to the type, based on the editor settings returned from `GF_Field::get_form_editor_field_settings()` .

Below is a list of supported editor settings:

* `add_icon_url_setting`
* `address_setting`
* `admin_label_setting`
* `autocomplete_setting`
* `background_color_setting`
* `base_price_setting`
* `border_color_setting`
* `border_style_setting`
* `border_width_setting`
* `box_width_setting`
* `calculation_setting`
* `captcha_badge_setting`
* `catcha_bg_setting`
* `catcha_fg_setting`
* `catcha_language_setting`
* `catcha_size_setting`
* `catcha_theme_setting`
* `catcha_type_setting`
* `chained_selects_alignment_setting`
* `chained_selects_hide_inactive_setting`
* `checkbox_label_setting`
* `choices_setting`
* `chained_choices_setting`
* `columns_setting`
* `conditional_logic_field_setting`
* `conditional_logic_page_setting`
* `content_setting`
* `copy_values_option`
* `credit_card_setting`
* `css_class_setting`
* `date_format_setting`
* `date_input_type_setting`
* `default_value_setting`
* `default_value_textarea_setting`
* `delete_icon_url_setting`
* `description_setting`
* `disable_margins_setting`
* `disable_quantity_setting`
* `duplicate_setting`
* `email_confirm_setting`
* `error_message_setting`
* `enable_enhanced_ui_setting`
* `file_extensions_setting`
* `file_size_setting`
* `force_ssl_field_setting`
* `gquiz-setting-choices`
* `gquiz-setting-show-answer-explanation`
* `gquiz-setting-randomize-quiz-choices`
* `input_mask_setting`
* `label_setting`
* `label_placement_setting`
* `maxlen_setting`
* `maxrows_setting`
* `multiple_files_setting`
* `name_setting`
* `next_button_setting`
* `number_format_setting`
* `other_choice_setting`
* `password_field_setting`
* `password_setting`
* `password_strength_setting`
* `password_visibility_setting`
* `pen_color_setting`
* `phone_format_setting`
* `placeholder_setting`
* `placeholder_textarea_setting`
* `post_category_checkbox_setting`
* `post_category_initial_item_setting`
* `post_custom_field_setting`
* `post_image_featured_image`
* `post_image_setting`
* `prepopulate_field_setting`
* `previous_button_setting`
* `product_field_setting`
* `range_setting`
* `rich_text_editor_setting`
* `rules_setting`
* `size_setting`
* `select_all_choices_setting`
* `sub_label_placement_setting`
* `time_format_setting`

Form fields that implement the above settings will have their GraphQL fields automatically registered to the type. Custom field settings can be registered with [the `graphql_gf_form_field_setting_properties` filter](recipes/register-custom-form-field.md).

## Form Field entry values

All Form fields have access to the `value` GraphQL field, which provides a string representation of the Form field's entry value, generated from `GF_Field::get_value_export()` .

Additionally, [currently-supported Form Fields](#supported-fields) use [type-specific value GraphQL fields](querying-fields.md).

## Form Field input types

By default, complex Gravity Forms form fields inherit the GraphQL fields set by their `GF_Field::$inputType` .

Some Gravity Forms form fields (for example, the Quiz Field or Custom Post Field) can be resolved dynamically to multiple types (for example, a `Checkbox` or `Radio` ).

For Gravity Forms core, these form fields are automatically registered as GraphQL interfaces, with each possible input type as GraphQL object that implements the interface.

For an example of the PostCategory field:

```graphql
 gfEntries{
  formFields {
    nodes {
      id
      ... on PostCategoryField { # the Interface
        hasAllCategories
        inputType
        ... on PostCategoryCheckboxField {
          checkboxValues {
            id
            value
          }
        }
      }
      # works the same as this:
      ... on PostCategorySelectField {
        hasEnhancedUI
        value
      }
    }
  }
 }
```

Developers wishing to support a custom Gravity Forms field that can resolve into multiple input types can make use of [the `graphql_gf_form_field_child_types` filter](recipes/register-custom-form-field.md).

## `Supported` , `Experimental` , and `Unsupported` fields

At this stage of development, we category Gravity Forms fields into three types.

### Supported fields

These core (and a few first-party extension) Form fields are _explicitly_ supported by the plugin. Their type-specific properties are registered in WPGraphQL, and they are extensively tested.

Currently supported form fields:

* `AddressField`
* `CaptchaField`
* `ChainedSelectField`
* `CheckboxField`
* `ConsentField`
* `DateField`
* `EmailField`
* `HiddenField`
* `HtmlField`
* `FileUploadField`
* `ListField`
* `MultiSelectField`
* `NameField`
* `NumberField`
* `PageField`
* `PhoneField`
* `PostCategoryField`
* `PostContentField`
* `PostExcerptField`
* `PostImageField`
* `PostTagsField`
* `PostTitleField`
* `QuizField`
* `RadioField`
* `SectionField`
* `SelectField`
* `SignatureField`
* `TextAreaField`
* `TextField`
* `TimeField`
* `WebsiteField`

### Experimental fields

These _Gravity Forms core-only_ fields are not yet explicitly supported by the plugin. The only properties they have are those registered by the [supported field settings](#form-field-properties) and the [string-formatted entry `value`](#form-field-entry-values) and they are untested. Further, they are not supported by [Form submissions](submitting-forms.md).

These forms are hidden **by default**:

* `CreditcardField`
* `OptionField`
* `PasswordField` [@todo]
* `PostCustomField` [@todo]
* `PriceField`
* `ProductField`
* `QuantityField`
* `ShippingField`
* `TotalField`

To enable these plugins, you can define the `WPGRAPHQL_GF_EXPERIMENTAL_FIELDS` constant to true in wp-config.php[https://wordpress.org/support/article/editing-wp-config-php/].

```php
// wp-config.php
define( `WPGRAPHQL_GF_EXPERIMENTAL_FIELDS`, true );
```

You can also use [the `graphql_gf_ignored_field_types` filter](actions-and-filters.md) to add support on a field-by-field basis.

When a production-level version of this plugin is released, it is expected that all core Gravity Forms fields will be supported.

## Unsupported Fields

These Form fields - either custom or from extensions - are given basic query support out of the box, depending on their use use of [supported field settings](#form-field-properties), and  support for additional GraphQL fields, input types, and submit/update mutations must be [added manually](recipes/register-custom-form-field.md).

## Enabling/Disabling Field Support <a name="enable-field-support" />

You can manually enable/disable support for individual Gravity Forms using the `graphql_gf_ignored_field_types` filter.

```php
add_filter(
  'graphql_gf_ignored_field_types', 
  function( array $ignored_fields ) : array {

    // Disable the 'MultiSelect' field.
    $ignored_fields[] = 'multiselect';

    // Enable the repeater field:
    $ignored_fields = array_diff( $ignored_fields, ['repeater'] );

    return $ignored_fields;

  }
);
```
