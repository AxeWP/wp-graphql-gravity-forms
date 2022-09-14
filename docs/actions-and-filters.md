# Actions & Filters

## Actions

* [`graphql_gf_init`](#graphql_gf_init)
* [`graphql_gf_after_register_types`](#graphql_gf_after_register_types)
* [`graphql_gf_before_register_types`](#graphql_gf_before_register_types)
* [`graphql_gf_after_register_form_field`](#graphql_gf_after_register_form_field)
* [`graphql_gf_after_register_form_field_object`](#graphql_gf_after_register_form_field_object)
* [`graphql_gf_register_form_field_choices`](#graphql_gf_register_form_field_choices)
* [`graphql_gf_register_form_field_inputs`](#graphql_gf_register_form_field_inputs)

### `graphql_gf_init`

Fires after the plugin has been initialized.

```php
do_action( 'graphql_gf_init', $instance );
```

#### Parameters

* **`$instance`** _(GF)_ : The plugin instance.

### `graphql_gf_after_register_types`

Fires after plugin registers types to the GraphQL schema

```php
do_action( 'graphql_gf_after_register_types' );
```

### `graphql_gf_before_register_types`

Fires before plugin registers types to the GraphQL schema

```php
do_action( 'graphql_gf_before_register_types' );
```

### `graphql_gf_after_register_form_field`
#### `gaphql_gf_after_register_form_field_{graphql_type}`

Fires after the Gravity Forms field has been hooked to be registered WPGraphQL schema.

The fields themselves will only be registered on the next get_graphql_register_action() call.

```php
do_action( 'graphql_gf_after_register_form_field', $field, $field_settings );
do_action( 'graphql_gf_after_register_form_field_' . $field->graphql_single_name, $field, $field_settings );
```

#### Parameters

* **`$field`** _(GF_Field)_ : The Gravity Forms field object.
* **`$field_settings`** _(array)_ : The Gravity Forms field settings.


#### Parameters

* **`$field`** _(GF_Field)_ : The Gravity Forms field object.
* **`$field_settings`** _(array)_ : The Gravity Forms field settings.
* **`$config`** _(array)_ : The config array as expected by WPGraphQL.

### `graphql_gf_after_register_form_field_object`
#### `gaphql_gf_after_register_form_field_object_{graphql_type}`

Fires after the Gravity Forms field object has been registered to WPGraphQL schema.

```php
do_action( 'graphql_gf_after_register_form_field_object', $field, $field_settings, $config );
do_action( 'graphql_gf_after_register_form_field_object_' . $field->graphql_single_name, $field, $field_settings, $config );
```

#### Parameters

* **`$field`** _(GF_Field)_ : The Gravity Forms field object.
* **`$field_settings`** _(array)_ : The Gravity Forms field settings.
* **`$config`** _(array)_ : The config array as expected by WPGraphQL.

### `graphql_gf_register_form_field_choices`
#### `gaphql_gf_register_form_field_choices_{graphql_type}`

Fires after the Gravity Forms field choices have been registered to WPGraphQL schema.
```php
do_action( 'graphql_gf_register_form_field_choices', $field, $field_settings, $config );
do_action( 'graphql_gf_register_form_field_choices_' . $field->graphql_single_name, $field, $field_settings, $config );
```

#### Parameters

* **`$field`** _(GF_Field)_ : The Gravity Forms field object.
* **`$field_settings`** _(array)_ : The Gravity Forms field settings.
* **`$config`** _(array)_ : The config array as expected by WPGraphQL.


### `graphql_gf_register_form_field_inputs`
#### `graphql_gf_register_form_field_inputs_{graphql_type}`

Fires after the Gravity Forms field choices have been registered to WPGraphQL schema.
```php
do_action( 'graphql_gf_register_form_field_inputs', $field, $field_settings, $config );
do_action( 'graphql_gf_register_form_field_inputs_' . $field->graphql_single_name, $field, $field_settings, $config );
```

## Filters

* [`graphql_gf_can_view_draft_entries`](#graphql_gf_can_view_entries)
* [`graphql_gf_can_view_entries`](#graphql_gf_can_view_entries)
* [`graphql_gf_entries_connection_query_args`](#graphql_gf_entries_connection_query_args)
* [`graphql_gf_field_value_input_class`](#graphql_gf_field_value_input_class)
* [`graphql_gf_field_value_input_args`](#graphql_gf_field_value_input_args)
* [`graphql_gf_field_value_input_name`](#graphql_gf_field_value_input_name)
* [`graphql_gf_field_value_input_prepared_value`](#graphql_gf_field_value_input_prepared_value)
* [`graphql_gf_forms_connection_query_args`](#graphql_gf_forms_connection_query_args)
* [`graphql_gf_form_field_child_types`](#graphql_gf_form_field_child_types)
* [`graphql_gf_form_field_name_map`](#graphql_gf_form_fields_name_map)
* [`graphql_gf_form_field_setting_choice_fields`](#graphql_gf_form_fields_setting_choice_fields)
* [`graphql_gf_form_field_setting_input_fields`](#graphql_gf_form_fields_setting_input_fields)
* [`graphql_gf_form_field_setting_fields`](#graphql_gf_form_fields_setting_fields)
* [`graphql_gf_form_field_value_fields`](#graphql_gf_form_fields_value_fields)
* [`graphql_gf_form_field_values_input_fields`](#graphql_gf_form_field_value_input_fields)
* [`graphql_gf_form_object`](#graphql_gf_form_object)
* [`graphql_gf_gatsby_enabled_actions`](#graphql_gf_gatsby_enabled_actions)
* [`graphql_gf_ignored_field_types`](#graphql_gf_ignored_field_types)
* [`graphql_gf_registered_connection_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_entry_types`](#graphql_gf_registered_entry_types)
* [`graphql_gf_registered_enum_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_form_field_setting_classes`](#graphql_gf_registered_form_field_setting_classes)
* [`graphql_gf_registered_form_field_setting_input_classes`](#graphql_gf_registered_form_field_setting_input_classes)
* [`graphql_gf_registered_form_field_setting_choice_classes`](#graphql_gf_registered_form_field_setting_choice_classes)
* [`graphql_gf_registered_field_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_input_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_interface_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_mutation_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_registered_object_classes`](#graphql_gf_registered_{type}_classes)
* [`graphql_gf_update_repo_url`](#graphql_gf_update_repo_url)

### `graphql_gf_can_view_draft_entries`

Filter to control whether the user should be allowed to view draft entries.

```php
apply_filters( 'graphql_gf_can_view_draft_entries', bool $can_view_entries, int $form_id, string $resume_token, array $draft_entry );
```

#### Parameters

* **`$can_view_entries`** _(bool)_ : Whether the user can view draft entries. By default this anyone. 
* **`$form_id`** _(int)_ : The GF form ID being queried by GraphQL.
* **`$resume_token`** _(string)_ : The draft entry resume token being queried by GraphQL.
* **`$draft_entry`** _(array)_ : The Gravity Forms draft entry data array.

### `graphql_gf_can_view_entries`

Filter to control whether the user should be allowed to view submitted entries.

```php
apply_filters( 'graphql_gf_can_view_entries', bool $can_view_entries, int $form_id, int $entry_id, array $entry );
```

#### Parameters

* **`$can_view_entries`** _(bool)_ : Whether the user can view draft entries. By default this is the user who submitted the entry, and any user with the `gravityforms_view_entries` and `gform_full_access` capabilities.
* **`$form_id`** _(int)_ : The GF form ID being queried by GraphQL.
* **`$entry_id`** _(string)_ : The entry ID being queried by GraphQL.
* **`$draft_entry`** _(array)_ : The Gravity Forms entry data array.


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

### `graphql_gf_field_value_input_class`

Filters the AbstractFieldValueInput class used to process form field submissions.
Useful for adding mutation support for custom Gravity Forms fields.

```php
apply_filters( 'graphql_gf_field_value_input_class', string $input_class, array $args, GF_Field $field, array $form, array|null $entry, bool $is_draft_mutation  );
```

#### Parameters

* **`$input_class`** _(string)_ :  The FieldValueInput class to use. The class must extend AbstractFieldValueInput.
* **`$args`** _(array)_ : The GraphQL input value name to use. E.g. `checkboxValues`.
* **`$field`** _(GF_Field)_ : The current Gravity Forms field object.
* **`$form`** _(array)_ : The current Gravity Forms form object.
* **`$entry`** _(array|null)_ : The current Gravity Forms entry object. Only set when using update (`gfUpdateEntry`,   `gfUpdateDraftEntry`) mutations.
* **`$is_draft_mutation`** _(bool)_ : Whether the mutation is handling a Draft Entry, i.e. on `gfUpdateDraftEntry`, or `gfSubmitForm` with `saveAsDraft` is `true`).

### `graphql_gf_field_value_input_args`

Filters the GraphQL input args provided from the field value input.
Useful for supporting custom Gravity Forms field value input types.

```php
apply_filters( 'graphql_gf_field_value_input_args', array|string $args, GF_Field $field, array $form, array|null $entry, bool $is_draft_mutation, string $field_name );
```

#### Parameters

* **`$args`** _(string|array)_ : The input args from the field value input.
* **`$field`** _(GF_Field)_ : The current Gravity Forms field object.
* **`$form`** _(array)_ : The current Gravity Forms form object.
* **`$entry`** _(array|null)_ : The current Gravity Forms entry object. Only set when using update (`gfUpdateEntry`,   `gfUpdateDraftEntry`) mutations.
* **`$is_draft_mutation`** _(bool)_ : Whether the mutation is handling a Draft Entry, i.e. on `gfUpdateDraftEntry`, or `gfSubmitForm` with `saveAsDraft` is `true`).
* **`$name`** _(string)_ : The GraphQL input value name to use. E.g. `checkboxValues`.

### `graphql_gf_field_value_input_name`

Filters the accepted GraphQL input value key for the form field.
Useful for adding support for custom Gravity Forms field value inputs.

```php
apply_filters( 'graphql_gf_field_value_input_name', string $name, GF_Field $field, array $form, array|null $entry, bool $is_draft_mutation  );
```

#### Parameters

* **`$name`** _(string)_ : The GraphQL input value name to use. E.g. `checkboxValues`.
* **`$field`** _(GF_Field)_ : The current Gravity Forms field object.
* **`$form`** _(array)_ : The current Gravity Forms form object.
* **`$entry`** _(array|null)_ : The current Gravity Forms entry object. Only set when using update (`gfUpdateEntry`,   `gfUpdateDraftEntry`) mutations.
* **`$is_draft_mutation`** _(bool)_ : Whether the mutation is handling a Draft Entry, i.e. on `gfUpdateDraftEntry`, or `gfSubmitForm` with `saveAsDraft` is `true`).

### `graphql_gf_field_value_input_prepared_value`

Filters the prepared field value to be submitted to Gravity Forms. Useful for supporting custom Gravity Forms field value submissions.

```php
apply_filters( 'graphql_gf_field_value_input_prepared_value', array|string $prepared_field_value, array|string $args, GF_Field $field, array $form, array|null $entry, bool $is_draft_mutation, string $field_name );
```

#### Parameters

* **`$prepared_field_value`** : The field value formatted for use in Gravity Forms submissions.
* **`$args`** _(string|array)_ : The input args from the field value input.
* **`$field`** _(GF_Field)_ : The current Gravity Forms field object.
* **`$form`** _(array)_ : The current Gravity Forms form object.
* **`$entry`** _(array|null)_ : The current Gravity Forms entry object. Only set when using update (`gfUpdateEntry`,   `gfUpdateDraftEntry`) mutations.
* **`$is_draft_mutation`** _(bool)_ : Whether the mutation is handling a Draft Entry, i.e. on `gfUpdateDraftEntry`, or `gfSubmitForm` with `saveAsDraft` is `true`).
* **`$name`** _(string)_ : The GraphQL input value name to use. E.g. `checkboxValues`.

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

#### Parameters

* **`$properties`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/) .
* **`$field`** _(GF_Field) : The Gravity Forms Field object.

### `graphql_gf_form_field_setting_choice_fields`
#### `graphql_gf_form_field_setting_choice_fields_{graphql_type}`

Filter to modify the Form Field Choice GraphQL fields.

```php
apply_filters( 'graphql_gf_form_field_setting_choice_fields', $fields, $choice_name, $field, $settings, $interfaces );
apply_filters( 'graphql_gf_form_field_setting_choice_fields_' . $choice_name, $fields, $field, $settings, $interfaces );
```

#### Parameters

* **`$fields`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/).
* **`$choice_name`** _(string)_ : The name of the choice type.
* **`$field`** _(GF_Field)_ : The Gravity Forms Field object.
* **`$settings`** _(array)_ : The `form_editor_field_settings()` keys.
* **`$interfaces`** _(array)_ : The list of interfaces for the GraphQL type.

### `graphql_gf_form_field_setting_input_fields`
#### `graphql_gf_form_field_setting_input_fields_{graphql_type}`

Filter to modify the Form Field Input GraphQL fields.

```php
apply_filters( 'graphql_gf_form_field_setting_input_fields', $fields, $input_name, $field, $settings, $interfaces );
apply_filters( 'graphql_gf_form_field_setting_input_fields_' . $input_name, $fields, $field, $settings, $interfaces );
```

#### Parameters

* **`$fields`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/).
* **`$input_name`** _(string)_ : The name of the input type.
* **`$field`** _(GF_Field)_ : The Gravity Forms Field object.
* **`$settings`** _(array)_ : The `form_editor_field_settings()` keys.
* **`$interfaces`** _(array)_ : The list of interfaces for the GraphQL type.


### `graphql_gf_form_field_setting_fields`
#### `graphql_gf_form_field_setting_fields_{graphql_type}`

Filter to modify the Form Field Input GraphQL fields.

```php
apply_filters( 'graphql_gf_form_field_setting_fields', $fields, $field, $settings, $interfaces );
apply_filters( 'graphql_gf_form_field_setting_fields_' . $field->graphql_single_nane, $fields, $field, $settings, $interfaces );
```

#### Parameters

* **`$fields`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/).
* **`$field`** _(GF_Field)_ : The Gravity Forms Field object.
* **`$settings`** _(array)_ : The `form_editor_field_settings()` keys.
* **`$interfaces`** _(array)_ : The list of interfaces for the GraphQL type.

### `graphql_gf_form_field_value_fields`
#### `graphql_gf_form_field_value_fields_{graphql_type}`

Filter to modify the Form Field Input GraphQL fields.

```php
apply_filters( 'graphql_gf_form_field_value_fields', $fields, $field );
apply_filters( 'graphql_gf_form_field_value_fields_' . $field->graphql_single_nane, $fields, $field);
```

#### Parameters

* **`$fields`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/).
* **`$field`** _(GF_Field)_ : The Gravity Forms Field object.


### `graphql_gf_form_field_values_input_fields`

Filter to modify the Form Field value GraphQL fields.
Useful for adding support for inputs used by custom Gravity Forms fields.

```php
apply_filters( 'graphql_gf_form_field_values_input_fields', array $fields );
```

#### Parameters

* **`$fields`** _(array)_ : An array of [WPGraphQL field `$config` arrays](https://www.wpgraphql.com/functions/register_graphql_fields/) .

### `graphql_gf_form_fields_name_map`

Filter to map the Gravity Forms Field type to a safe GraphQL type (in PascalCase ).

```php
apply_filters( 'graphql_gf_form_fields_name_map', array $fields_to_map );
```

#### Parameters

* **`$fields_to_map`** _(array)_ : An array of GF field types to GraphQL type names. E.g. ` 'fileupload' => 'FileUpload'`.

### `graphql_gf_form_object`

Filter to modify the form data before it is sent to the client. This hook is somewhat similar to GF's `gform_pre_render` hook, and can be used for dynamic field input population among other things.

```php
apply_filters( 'graphql_gf_form_object', array $form );
```

#### Parameters

* **`$form`** _(array)_ : The GF [Form object](https://docs.gravityforms.com/form-object/).

### `graphql_gf_gatsby_enabled_actions`

Filter for overriding the list of enabled actions that WPGatsby should monitor.

```php
apply_filters( 'graphql_gf_gatsby_enabled_actions', array $enabled_actions );
```

#### Parameters

* **`$enabled`** _(array)_ : An array of the enabled actions for WPGatsby to monitor. Possible array values: `create_form`,  `update_form`,  `delete_form`,  `create_entry`,  `update_entry`.

### `graphql_gf_ignored_field_types`

Filters the list of ignored field types. Useful for adding/removing support for a specific Gravity Forms field.

```php
apply_filters( 'graphql_gf_ignored_field_types', array $ignored_fields );
```

#### Parameters

* **`$ignored_fields`** _(array)_ :  An array of `GF_Field::$type` names to be ignored by WPGraphQL.

### `graphql_gf_registered_{$type}_classes`

Filters the list of PHP classes that register GraphQL types. Useful for adding/removing GF specific GraphQL types to the schema.

Possible types are `connection` (e.g. `graphql_gf_registered_connection_classes` ), `enum` , `field` , `input` , `interface` , `mutation` and `object` .

```php
apply_filters( 'graphql_gf_registered_{$type}_classes', array $classes_to_register );
```

#### Parameters

* **`$classes_to_register`** _(array)_ : Array of PHP classes with GraphQL types to be registered to the schema.

### `graphql_gf_registered_form_field_setting_classes`
### `graphql_gf_registered_form_field_setting_choice_classes`
### `graphql_gf_registered_form_field_setting_input_classes`

Filters the list of PHP classes that register GraphQL Interfaces based on a particular Gravity Forms field setting.


```php
apply_filters( 'graphql_gf_registered_{$type}_classes', array $classes_to_register );
```

#### Parameters

* **`$classes_to_register`** _(array<string, class-string>)_ : Array of Gravity Forms setting keys and their PHP class that registers the setting's GraphQL Interface.

### `graphql_gf_registered_entry_types`

Filter for modifying the Gravity Forms Entry types supported by WPGraphQL.

```php
apply_filters( 'graphql_gf_registered_entry_types', array $entry_types );
```

#### Parameters

* **`$entry_types`** _(array)_ : An array of Data Loader names => GraphQL Types. E.g: `[ [ 'gf_entry' => 'GfSubmittedEntry ] ]`

### `graphql_gf_update_repo_url`

Filters the repo url used in the update checker.

Useful for checking updates against a fork.

```php
apply_filters( 'graphql_gf_update_repo_url', string $repo_link );
```

#### Parameters

* **`$repo_link`** _(string)_ : The url to the repo, [as required by `plugin-update-checker`](https://github.com/YahnisElsts/plugin-update-checker#github-integration).
