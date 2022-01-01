# Querying `formFields`

## Getting the `formFields` from a form or entry

Both [forms](https://docs.gravityforms.com/form-object/) and [entries](https://docs.gravityforms.com/entry-object/) use the `formFields` GraphQL Interface to retrieve information about [Gravity Forms fields](https://docs.gravityforms.com/field-object/), and their submission values.

In addition to the shared fields available on the Interface, each Gravity Forms Field type has its own set of GraphQL fields that are accessible with query fragments.

As of v0.10.0, all Gravity Forms Fields - including custom ones - are automatically registered to the GraphQL schema - included custom ones!, and their specific GraphQL fields generated from `GF_Field::get_form_editor_field_settings()` . If you are using custom editor field settings, you will need to [register those manually](recipes/register-custom-form-field.md).

### Example Query

```graphql
{
  gfForm(id: 1, idType: DATABASE_ID) {
    formDatabaseId
    formFields(first: 300) {
      nodes {
        id
        type
        cssClass
        ... on TextField {
          description
          label
          isRequired
        }
        ... on CheckboxField {
          description
          label
          choices {
            isSelected
            text
            value
          }
        }
        ... on NameField {
          description
          label
          inputs {
            isHidden
            label
            placeholder
          }
        }
      }
    }
  }
}
```

## Getting `formField` entry values

Entry values can be accessed similarly to other Gravity Forms Field properties, by including the corresonding GraphQL field in the fragment.

**Note**: Due to GraphQL limitations regarding Union types, you must use the specific value type specific to that field. A full list of field value types and their corresponding field fragments are below.

As of v0.10.0, all `formFields` have access to the `value` GraphQL field, which provides the string representation of the entry value, created by `GF_Field::get_value_export()` . Certain [supported `formFields`](form-field-support.md) provide a value type specific to that field, as follows:

| Field Value Type               | Used by                                                                                                                                                                                                                                                                  | Available subfields                                              |
| ------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------- |
| `addressValues` _( obj )_      | `AddressField` | `city` <br> `country` <br> `lineTwo` <br> `state` <br> `street` <br> `zip` |
| `checkboxValues` _( [ obj ] )_ | `CheckboxField` | `inputId` <br> `text` <br> `value` |
| `imageValues` _( obj )_        | `ImageField` | `altText` <br> `caption` <br> `description` <br> `title` <br> `url` <br>  |
| `listValues` _( [ obj ] )_     | `ListField` | `values` _( [ string ] )_                                        |
| `nameValues` _( obj )_         | `NameField` | `first` <br> `last` <br> `middle` <br> `prefix` <br> `suffix` |
| `timeValues` _( obj )_         | `TimeField` | `amPm` <br> `displayValue` <br> `hours` <br> `minutes` |
| `values` _( [ string] )_       | `ChainedSelectField` <br> `FileUploadField` <br> `MultiSelectField` <br> `PostCategoryField` <br> `PostCustomField` <br> `PostTagsField` <br> `QuizField` | n/a                                                              |

### Example Query

```graphql
{
  gfEntry(id: 1, idType: DATABASE_ID) {
    databaseId
    formFields(first: 300) {
      nodes {
        ... on CheckboxField {
          checkboxValues {
            inputId
            value
          }
        }
        ... on NameField {
          nameValues {
            prefix
            first
            middle
            last
            suffix
          }
        }
        ... on TextField {
          value
        }
      }
    }
  }
}
```

## Filtering `formFields`

The code comments in the example query below explain how you can get a filtered list of form fields.

```graphql
{
  gfEntry(id: 1, idType: DATABASE_ID) {
    databaseId
    formFields(
      first: 300
      after: "YXJyYXljb25uZWN0aW9uOjI=" # Or pass null to start from the beginning.
      where: {
        # Return a specific list of form fields.
        ids: [1,4,8]
        # Find form fields by their `adminLabel`.
        adminLabels: ['projectID', 'mylabel']
        # Filter form fields by field types.
        fieldTypes: [ ADDRESS, MULTISELECT, TEXTAREA ]
        # Filter form fields by the page number in multi-page forms.
        pageNumber: 2
      }
      ) {
      nodes {
        id
        type
    }
  }
}
```
