# Querying `formFields`

## Getting the `formFields` from a form or entry

Both [forms](https://docs.gravityforms.com/form-object/) and [entries](https://docs.gravityforms.com/entry-object/) use the `formFields` GraphQL Interface to retrieve information about [Gravity Forms fields](https://docs.gravityforms.com/field-object/), and their submission values.

In addition to the shared fields available on the `FormField` Interface, each Gravity Forms Field type implements a number of additional interfaces that correspond to their defined [Gravity Forms Field Settings](https://docs.gravityforms.com/category/developers/php-api/field-framework/field-framework-settings/)

By default, all Gravity Forms Fields - including custom ones - are automatically registered to the GraphQL schema - included custom ones!, and their specific GraphQL fields generated from `GF_Field::get_form_editor_field_settings()` . If you are using custom editor field settings, you will need to [register those manually](recipes/register-custom-form-field.md).

### Basic Usage: Querying `FormField` types determinately.
The easiest way to query individual `FormField`s by requesting their properties directly. You can then map these fields directly to specific frontend component props. 
This method is best if you have a small form with a limited number of field types that rarely (if ever) changes, however is hard to scale while maintaining the Drag & Drop customizability of Gravity Forms.

#### Example Query

```graphql
{
  gfForm(id: 1, idType: DATABASE_ID) {
    databaseId
    formFields(first: 300) {
      nodes {
        databaseId
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

### Advanced Usage: Querying `FormField` types indeterminately with GraphQL Interfaces.

With so many different types of Form Fields and possible settings, querying for `FormField`s determinately quickly becomes unscalable, requiring unnecessarily-specific frontend components and often forcing developers to limited fields/settings supported by the frontend.

Luckily, we can use [GraphQL Interfaces](https://graphql.org/learn/schema/#interfaces) to craft queries indeterminely based on possible Gravity Form Field settings, instead of tying them to an individual GraphQL object. While a bit more verbose to write initially, this method makes both GraphQL queries and frontend `FormField` components DRYer and more reusable.

#### Example
```graphql
{
  gfForm(id: 1, idType: DATABASE_ID) {
    databaseId
    formFields(where: {pageNumber: 1}) {
      nodes {
        databaseId
        inputType
        type
        ... on GfFieldWithLabelSetting {
          label
        }
        ... on GfFieldWithRulesSetting {
          isRequired
        }
        ... on GfFieldWithAddressSetting {
          defaultState
          defaultCountry
          defaultProvince
          addressType
        }
        ... on GfFieldWithInputs {
          inputs {
            id
            label
            ... on AddressInputProperty {
              autocompleteAttribute
              customLabel
              placeholder
            }
          }
        }
        ... on GfFieldWithChoices {
          choices {
            text
            value
            ... on GfFieldChoiceWithChoicesSetting {
              isSelected
            }
            ... on ChainedSelectFieldChoice {
              choices {
                text
                value
                isSelected
              }
            }
          }
        }
      }
    }
  }
}
```

## Getting `formField` entry values

Entry values can be accessed similarly to other Gravity Forms Field properties, by including the corresonding GraphQL field in the fragment.

> [!IMPORTANT]
>
> Due to GraphQL limitations regarding Union types, you must use the specific value type specific to that field. A full list of field value types and their corresponding field fragments are below.


As of v0.10.0, all `formFields` have access to the `value` GraphQL field, which provides the string representation of the entry value, created by `GF_Field::get_value_export()`. Certain [supported `formFields`](form-field-support.md) provide a value type specific to that field, as follows:

| Field Value Type               | Used by                                                                                                                                                                                                                                                                  | Available subfields                                              |
| ------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------- |
| `addressValues` _( obj )_      | `AddressField` | `city` <br> `country` <br> `lineTwo` <br> `state` <br> `street` <br> `zip` |
| `checkboxValues` _( [ obj ] )_ | `CheckboxField` | `connectedChoice` _( GfFieldChoice )_ <br> `connectedInput` _(GfFieldInput)_ <br>`inputId` <br> `text` <br> `value` |
| `fileUploadValues` _( [ obj ] )_ | `FileUploadField` | `basePath` <br> `baseUrl` <br> `filename` <br> `url` |
| `imageValues` _( obj )_        | `ImageField` | `altText` <br> `basePath` <br> `baseUrl` <br> `caption` <br> `description` <br> `filename` <br>  `title` <br> `url` <br>  |
| `listValues` _( [ obj ] )_     | `ListField` | `values` _( [ string ] )_                                        |
| `nameValues` _( obj )_         | `NameField` | `first` <br> `last` <br> `middle` <br> `prefix` <br> `suffix` |
| `timeValues` _( obj )_         | `TimeField` | `amPm` <br> `displayValue` <br> `hours` <br> `minutes` |
| `values` _( [ string] )_       | `ChainedSelectField` <br> `MultiSelectField` <br> `PostCategoryField` <br> `PostTagsField` <br> `QuizField` | n/a                                                              |

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
            ... on CheckboxFieldChoice {
              isSelected
            }
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
        databaseId
        type
      }
    }
  }
}
```
