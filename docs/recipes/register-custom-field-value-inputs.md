# Recipes

## How to add mutation support from a custom Gravity Forms field.

When adding mutation support for custom Gravity Forms fields, we need to tell GraphQL what input data to accept, and then transform that data into a format that Gravity Forms understands.

While the needs of each form field are different, the following guide lays out some of the more common steps required.

### Step 1: Register the GraphQL Input Type (Optional).

Depending on how complex your Form Field is, you might want to create a custom GraphQL Input type for the form field value.

If your field is using a [preexisting `FormFieldValues` type](https://github.com/harness-software/wp-graphql-gravity-forms/blob/develop/src/Type/Input/FormFieldValuesInput.php#L34) ( e.g. `value` , `values` , `checkboxValues` ), you can skip this step. _However_, we recommend creating a custom input type for every custom GF form to [protect yourself against future breaking changes to the schema](https://www.apollographql.com/blog/graphql/basics/designing-graphql-mutations/).

For example:

```php
register_graphql_input_type(
	'MyCustomFieldValueInput',
	[
		'description' => __( 'The `fieldValues` input for a MyCustomField field.', 'my-plugin' ),
		'fields' => [ 
			//see https://www.wpgraphql.com/functions/register_graphql_input_type/
		],
	]
);
```

You would then add the GraphQL input field to `FormFieldValuesInput` with [the `graphql_gf_form_field_values_input_fields` filter](../actions-and-filters.md#graphql_gf_form_field_values_input_fields):

```php
// Add the custom input field to the mutation `fieldValues` input.
add_filter(
	'graphql_gf_form_field_values_input_fields',
	function( array $fields ) : array {
		$fields['myCustomFieldValues'] = [ // The field value name.
			'type' => 'MyCustomFieldValueInput', // This is registered above.
			'description' => __( 'The form field values for MyCustomField fields.', 'my-plugin'),
		];
	}
);
```

### Preparing the GraphQL data for Gravity Forms.

WPGraphQL for Gravity Forms passes the GraphQL input args through [the `AbstractFieldValueInput` class](https://github.com/harness-software/wp-graphql-gravity-forms/blob/develop/src/Data/FieldValueInput/AbstractFieldValueInput.php) to make it easy to process the data for Gravity Forms.

For custom fields, we recommend extending the above class, and then registering it with [the `graphql_gf_field_value_input_class` filter](../actions-and-filters.md#graphql_gf_field_value_input). If you wish to change the behavior of one of the default `FieldValueInput` s, you can make use of several WordPress filters.

#### Example 1: Extending `AbstractFieldValueInput`

The following example shows how to create a custom FieldValueInput object to handle submissions for your custom Form Field. For a full understanding, you should review the comments in [the source code](https://github.com/harness-software/wp-graphql-gravity-forms/blob/develop/src/Data/FieldValueInput/AbstractFieldValueInput.php).

```php
class MyCustomFieldValueInput extends \WPGraphQL\GF\Data\FieldValueInput\AbstractFieldValueInput {

	/**
	 * Gets the key for the GraphQL field value input.
	 *
	 * E.g. `nameValues`.
	 */
	protected function get_field_name() : string {
		return 'myCustomFieldValues'; // As registered to `graphql_gf_form_field_values_input_fields` above.
	}

	/**
	 * Converts the field value args to a format GravityForms can understand.
	 *
	 * @return string|array the sanitized value.
	 */
	protected function prepare_value() {
		// You probably want to replace this.
		return $this->args;
	}

	/**
	 * Adds the prepared value to the field values array for processing by Gravity Forms.
	 *
	 * @param array $field_values the existing field values array.
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		// Let's set a $_POST value that GF is looking for:
		$_POST[ 'input_' . $this->field->id . '_custom' ] = 'someCustomValue';

		// Now we add our prepared value.
		$field_values[ $this->field->id ] = $this->value;
	}
}
```

To begin using your new `FieldValueInput` with your custom field, use [the `graphql_gf_field_value_input_class` filter](../actions-and-filters.md#graphql_gf_field_value_input_class).

```php
add_filter(
	'graphql_gf_field_value_input_class',
	function( $field_value_input, array $args, \GF_Field $field, array $form, $entry, bool $is_draft_mutation ) {
		// Use MyCustomFieldValuesInput for `my_custom_field` Gravity Forms fields.
		if( 'my_custom_field' === $field->type ){
			$field_value_input = new MyCustomFieldValueInput( $args, $form, $is_draft, $field, $entry );
		}

		return $field_value_input;
	},
	10,
	6
);
```

#### Example 2: Using WordPress filters

The following examples show different ways to use WordPress filters to modify the data submitted to Gravity Forms.

##### Change the default FieldValueInput used to handle the type

[By default](../form-field-support.md), all custom Gravity Forms fields without a core `$inputType` use [the `ValueInput` class](../submitting-forms.md) for processing.

To use a different `AbstractFieldValueInput` class instance you can use [the `graphql_gf_field_value_input_class` filter](../actions-and-filters.md#graphql_gf_field_value_input_class):

```php
add_filter(
	'graphql_gf_field_value_input_class',
	function( $field_value_input, array $args, \GF_Field $field, array $form, $entry, bool $is_draft_mutation ) {
		// Check out the plugin source to see the included core FieldValueInput classes to chose from.
		if( 'my_custom_field' === $field->type ){
			$field_value_input = new \WPGraphQL\GF\Data\FieldValueInput\CheckboxValuesInput( $args, $form, $is_draft, $field, $entry );
		}

		return $field_value_input;
	},
	10,
	6
);
```

##### Change the default GraphQL input field name

[By default](../form-field-support.md), all custom Gravity Forms fields without a core `$inputType` accept the `value` input argument [../submitting-forms.md], and attempting to pass a different argument will throw a GraphQL error. 

You can overwrite the input argument used by your custom Gravity Forms Field with [the `graphql_gf_field_value_input_name` filter](../actions-and-filters.md#graphql_gf_field_value_input_name):

```php
add_filter(
	'graphql_gf_field_value_input_name',
	function ( string $field_name, \GF_Field $field ) : string {
		if( 'my_custom_field' === $field->type ){
			$field_name = 'checkboxValues';
		}
		return $field_name
	}
)
```
