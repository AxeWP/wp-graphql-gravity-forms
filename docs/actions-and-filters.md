# Actions & Filters

## Filters

* [`graphql_gf_can_view_draft_entries`](#wp_graphql_gf_can_view_entries)
* [`graphql_gf_can_view_entries`](#wp_graphql_gf_can_view_entries)
* [`graphql_gf_entries_connection_query_args`](#graphql_gf_entries_connection_query_args)
* [`graphql_gf_form_field_child_types`](#graphql_gf_form_field_child_types)
* [`graphql_gf_form_field_setting_properties`](#graphql_gf_form_field_setting_properties)
* [`graphql_gf_form_field_value_properties`](#graphql_gf_form_field_value_properties)
* [`graphql_gf_form_object`](#graphql_gf_form_object)
* [`graphql_gf_forms_connection_query_args`](#graphql_gf_forms_connection_query_args)
* [`graphql_gf_ignored_field_types`](#graphql_gf_ignored_field_types)
* [`graphql_gf_registered_connection_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_entry_types`](#graphql_gf_registered_entry_types)
* [`graphql_gf_registered_enum_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_field_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_input_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_interface_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_mutation_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_object_classes`](#graphql_gf_registered_{type}_classes)
* [`wp_graphql_gf_field_value_type`](#wp_graphql_gf_field_value_type)
* [`wp_graphql_gf_prepare_field_value`](#wp_graphql_gf_prepare_field_value)

### `graphql_gf_can_view_draft_entries`

Filter to control whether the user should be allowed to view draft entries.

```php
apply_filters( 'graphql_gf_can_view_draft_entries', bool $can_view_entries, int|int[] $form_ids );
```

#### Parameters

* **`$can_view_entries`** _(bool)_ : Whether the user can view draft entries. By default this anyone. 
* **`$form_ids`** _(array|int)_ : An array of the GF form ids being queried by GraphQL.

### `graphql_gf_can_view_entries`

Filter to control whether the user should be allowed to view submitted entries.

```php
apply_filters( 'graphql_gf_can_view_entries', bool $can_view_entries, int|int[] $form_ids );
```

#### Parameters

* **`$can_view_entries`** _(bool)_ : Whether the user can view draft entries. By default this is the user who submitted the entry, and any user with the `gravityforms_view_entries` and `gform_full_access` capabilities.
* **`$form_ids`** _(array|int)_ : An array of the GF form ids being queried by GraphQL. `0` if all forms are being queried for entries.

### `graphql_gf_entries_connection_query_args`

Filter the Submitted Entry's  $query_args to allow folks to customize queries programmatically.

```php
apply_filters( 'graphql_gf_entries_connection_query_args', array $query_args, mixed $source, array $args, AppContext $context, ResolveInfo $info );
```

#### Parameters

* **`$query_args`** _(array)_ : The query args that will be passed to `GF_Query`.
* **`$source`** _(mixed)_ : The source passed down the Resolve Tree.
* **`$args`** _(array)_ : Array of arguments input in the field as part of the GraphQL query.
* **`$context`** _(AppContext)_ : Object passed down the GraphQL tree.
* **`$info`** _(ResolveInfo)_ : The ResolveInfo passed down the GraphQL tree.

### `graphql_gf_forms_connection_query_args`

Filter the Form $query_args to allow folks to customize queries programmatically.

```php
apply_filters( 'graphql_gf_forms_connection_query_args', array $query_args, mixed $source, array $args, AppContext $context, ResolveInfo $info );
```

#### Parameters

* **`$query_args`** _(array)_ : The query args that will be passed to `GFAPI::get_forms()`.
* **`$source`** _(mixed)_ : The source passed down the Resolve Tree.
* **`$args`** _(array)_ : Array of arguments input in the field as part of the GraphQL query.
* **`$context`** _(AppContext)_ : Object passed down the GraphQL tree.
* **`$info`** _(ResolveInfo)_ : The ResolveInfo passed down the GraphQL tree.

### `graphql_gf_form_field_child_types`

Filter for altering the child types of a specific GF_Field.

```php
apply_filters( 'graphql_gf_form_field_child_types', array $child_types, string $field_type );
```

#### Parameters

* **`$child_types`** _(array)_ : An array of GF_Field::$type => GraphQL type names. E.g.:

```php
// For $type = 'quiz'.
$child_types = [
  'checkbox' => 'QuizCheckboxField',
  'radio'    => 'QuizRadioField',
  'select'   => 'QuizSelectField',
];
```

* **`$field_type`** _(string)_ : The 'parent' `GF_Field::$type`. E.g. `quiz`.

### `graphql_gf_form_field_setting_properties`

Filter to modify the Form Field GraphQL fields based on `GF_Field::form_editor_field_settings()` .

```php
apply_filters( 'graphql_gf_form_field_setting_properties', array $properties, string $setting, GF_Field $field );
```

#### Parameters

* **`$properties`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/) .
* **`$setting`** _(string)_ : The `form_editor_field_settings()` key.
* **`$field`** _(GF_Field) : The Gravity Forms Field object.

### `graphql_gf_form_field_value_properties`

Filter to modify the Form Field value GraphQL fields.

```php
apply_filters( 'graphql_gf_form_field_setting_properties', array $properties, GF_Field $field );
```

#### Parameters

* **`$properties`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/) .
* **`$field`** _(GF_Field) : The Gravity Forms Field object.

### `graphql_gf_form_object`

Filter to modify the form data before it is sent to the client. This hook is somewhat similar to GF's `gform_pre_render` hook, and can be used for dynamic field input population among other things.

```php
apply_filters( 'graphql_gf_form_object', array $form );
```

#### Parameters

* **`$form`** _(array)_ : The GF [Form object](https://docs.gravityforms.com/form-object/).

### `graphql_gf_ignored_field_types`

Filters the list of ignored field types. Useful for adding/removing support for a specific Gravity Forms field.

```php
apply_filters( 'graphql_gf_ignored_field_types', array $ignored_fields );
```

#### Parameters

* **`$ignored_fields`** _(array)_ :  An array of `GF_Field::$type` names to be ignored by WPGraphQL.

### `graphql_gf_registered_{$type}_classes`

Filters the list of PHP classes that register GraphQL. Useful for adding/removing GF specific GraphQL types to the schema.

Possible types are `connection` (e.g. `graphql_gf_registered_connection_classes` ), `enum` , `field` , `input` , `interface` , `mutation` and `object` .

```php
apply_filters( 'graphql_gf_registered_{$type}_classes', array $classes_to_register );
```

#### Parameters

* **`$classes_to_register`** _(array)_ : Array of PHP classes with GraphQL types to be registered to the schema.

### `graphql_gf_registered_entry_types`

Filter for modifying the Gravity Forms Entry types supported by WPGraphQL.

```php
apply_filters( 'graphql_gf_registered_{$type}_classes', array $classes_to_register );
```

#### Parameters

* **`$entry_types`** _(array)_ : An array of Data Loader names => GraphQL Types. E.g: `[ [ 'gf_entry' => 'GfSubmittedEntry ] ]`

### `wp_graphql_gf_field_value_type`

Filter to modify the list of accepted `fieldValues` input types. Can be used to assign an expected `fieldValues` input type to a custom GF field.

```php
apply_filters( 'wp_graphql_gf_field_value_type', string $value_type_name, GF_Field $field, array $input_values );
```

#### Parameters

* **`$value_type_name`** _(string)_ : The GraphQL input type name that must be included in `fieldValues`.
* **`$field`** _(GF_Field)_ : The Gravity Forms [field object](https://docs.gravityforms.com/field-object/).
* **`input_values`** _(array )_ : The `fieldValues` input array.

### `wp_graphql_gf_prepare_field_value`

Filter to modify the field value submitted to GF.

```php
apply_filters( 'wp_graphql_gf_prepare_field_value', mixed $value, array $input_values, GF_Field $field, mixed $prev_value = null );
```

#### Parameters

* **`$value`** _(mixed)_ : The formatted value to be added to the [Gravity Forms submission object](https://docs.gravityforms.com/api-functions/#submit-form).
* **`$input_values`** _(array)_ : The `fieldValues` input array submitted to the the GraphQL mutation.
* **`$field`** _(GF_Field)_ : The Gravity Forms [field object](https://docs.gravityforms.com/field-object/).
* **`$prev_value`** _( mixed)_ : The previous value saved to GF entry or draft entry., if it exists.
