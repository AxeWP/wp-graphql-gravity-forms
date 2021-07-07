# Actions & Filters

## Filters

### `wp_graphql_gf_can_view_entries`
Filter for modifying whether the user can view the GF entries being queried.
```php
apply_filters( 'wp_graphql_gf_can_view_entries', bool $can_view_entries, array $entry_ids );
```
##### Parameters
- **`$can_view_entries`** _(bool)_ : Whether the user has `gravityforms_view_entries` or `gform_full_access` permissions.
- **`$entry_ids`** _(array)_ : An array of the GF entry ids being queried by GraphQL.

### `wp_graphql_gf_connection_config`

Filters the GraphQL connection `$config` array used to register the connection in WPGraphQL.

```php
apply_filters( 'wp_graphql_gf_connection_config', array $config, string $from_type, string $to_type );
```
##### Parameters
- **`$config`** _(array)_ : An array containing the [WPGraphQL connection `$config`](https://www.wpgraphql.com/functions/register_graphql_connection/#parameters).
- **`$from_type`** _(string)_ : The `from` connection type.
- **`$to_type`** _(string)_ : The `to` connection type.

### `wp_graphql_gf_custom_properties`
Filter to register custom GraphQL fields to GF `formFields`.
```php
apply_filters( 'wp_graphql_gf_custom_properties', array $custom_properties, string $gf_type );
```
Parameters:
- **`$custom_properties`** _(array)_ : An array of [WPGraphQL field `$config`](https://www.wpgraphql.com/functions/register_graphql_field/#parameters) .
 - **`$gf_type`** _(string)_ : The gravity forms field type  the GraphQL field should be associated with.

### `wp_graphql_gf_field_value_type`
Filter to modify the list of accepted `fieldValues` input types. Can be used to assign an expected `fieldValues` input type to a custom GF field.
```php
apply_filters( 'wp_graphql_gf_field_value_type', string $value_type_name, GF_Field $field, array $input_values );
```
##### Parameters
- **`$value_type_name`** _(string)_ : The GraphQL input type name that must be included in `fieldValues`.
- **`$field`** _(GF_Field)_ : The Gravity Forms [field object](https://docs.gravityforms.com/field-object/).
- **`input_values`** _(array )_ : The `fieldValues` input array.

### `wp_graphql_gf_form_object`
Filter to modify the form data before it is sent to the client. This hook is somewhat similar to GF's `gform_pre_render` hook, and can be used for dynamic field input population among other things. 
```php
apply_filters( 'wp_graphql_gf_form_object', array $form );
```
##### Parameters
  - **`$form`** _(array)_ : The GF [Form object](https://docs.gravityforms.com/form-object/).

### `wp_graphql_gf_instances`

Filter for modifying the plugin's class instances. Can be used to extend supported functionality, such as mutations, custom form fields, etc.

```php
apply_filters( 'wp_graphql_gf_instances', array $instances );
```
##### Parameters
- **`$instances`** _(array)_ : An array of `AbstractFormField` instances. 

### `wp_graphql_gf_prepare_field_value`
Filter to modify the field value submitted to GF.
```php
apply_filters( 'wp_graphql_gf_prepare_field_value', mixed $value, array $input_values, GF_Field $field, mixed $prev_value = null );
```
##### Parameters
- **`$value`** _(mixed)_ : The formatted value to be added to the GF submission object [link].
- **`$input_values`** _(array)_ : The `fieldValues` input array submitted to the the GraphQL mutation.
- **`$field`** _(GF_Field)_ : The Gravity Forms [field object](https://docs.gravityforms.com/field-object/).
- **`$prev_value`** _( mixed)_ : The previous value saved to GF entry or draft entry., if it exists.

### `wp_graphl_gf_type_config`
Filter to modify the WPGraphQL type `$config` array used to register Gravity Forms types.
Can also be used as `wp_graphql_gf_{$type}_type_config( $config )`.
```php
apply_filters( 'wp_graphql_gf_type_config', array $config, string $type );
apply_filters( 'wp_graphql_gf_{$type}_type_config', array $config );
```
##### Parameters
- **`$config`** _(array)_ : The [`$config` array for the WPGraphQL type](https://www.wpgraphql.com/functions/register_graphql_object_type/#parameters).
- **`$type`** _(string)_ : The GraphQL type to be registered.

### `wp_graphql_gf_{$enumType}_values( $values )`. 
Filters registered values for an Enum.
```php
apply_filters( 'wp_graphql_gf_{$enumType}_values, array $values );
```
##### Parameters:
- **`$values`** _(array)_ : The values for the registered Enum type.
