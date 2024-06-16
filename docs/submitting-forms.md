# Submitting Forms

Form submissions are handled with the `submitGfForm` mutation.

This mutation can be used either to submit an Entry or to submit a draft entry, by toggling the `saveAsDraft` input to `true` .

The `fieldValues` input takes an array of objects containing the `id` of the field, and a value input that corresponds to the Gravity Forms Field type.

> [!IMPORTANT]
>
> Due to [GraphQL's current lack of support for Input Union types](https://github.com/axewp/wp-graphql-gravity-forms/issues/4#issuecomment-563305561), you must use the specific value type specific to that field. A full list of field value types and their corresponding field fragments are below.

## Supported Field Value input types

| Field Value Input Type                                                  | Used for                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Sub-fields                                                                                                  |
| ----------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------- |
| `addressValues` _( obj )_                                               | `AddressField`                                                                                                                                                                                                                                                                                                                                                                                                                                                           | `city` <br> `country` <br> `lineTwo` <br> `state` <br> `street` <br> `zip`                                  |
| `chainedSelectValues` _( [ obj ] )_ <sup>[1](#chainedSelectsNote)</sup> | `ChainedSelectField`                                                                                                                                                                                                                                                                                                                                                                                                                                                     | `inputId` <br> `value`                                                                                      |
| `checkboxValues` _( [ obj ] )_                                          | `CheckboxField` <br> `QuizField` <sup>[3](#quizNote)</sup>                                                                                                                                                                                                                                                                                                                                                                                                               | `inputId` <br> `value`                                                                                      |
| `emailValues` _( obj )_                                                 | `EmailField`                                                                                                                                                                                                                                                                                                                                                                                                                                                             | `confirmationValue` <br/> `value`                                                                           |
| `fileUploadValues` _( [ Upload ] )_<sup>[2](#uploadNote)</sup>          | `FileUploadField`                                                                                                                                                                                                                                                                                                                                                                                                                                                        | See [Submitting File Uploads](#submitting-file-uploads)                                                     |
| `listValues` _( [ obj ] )_                                              | `ListField`                                                                                                                                                                                                                                                                                                                                                                                                                                                              | `rowValues` _( [ String ] )_                                                                                |
| `nameValues` _( obj )_                                                  | `NameField`                                                                                                                                                                                                                                                                                                                                                                                                                                                              | `first` <br> `last` <br> `midele` <br> `prefix` <br> `suffix`                                               |
| `postImageValues` _( obj )_ <sup>[2](#uploadNote)</sup>                 | `PostImageField`                                                                                                                                                                                                                                                                                                                                                                                                                                                         | `altText` <br> `caption` <br> `description` <br> `image` _(Upload)<sup>[2](#uploadNote)</sup>_ <br> `title` |
| `productValues` _( obj )_ <sup>[3](#productNote)</sup>                  | `ProductField`                                                                                                                                                                                                                                                                                                                                                                                                                                                           | `price` <br> `quantity`                                                                                     |
| `value` _( string )_                                                    | `CaptchaField` <sup>[4](#captchaNote)</sup><br> `ConsentField`<sup>[5](#consentNote)</sup> <br> `DateField` <br> `HiddenField` <br> `NumberField` <br> `PhoneField` <br> `PostContentField` <br> `PostExcerptField` <br> `PostTitleField` <br> `QuizField` <sup>[6](#quizNote)</sup><br> `RadioField` <sup>[7](#radioNote)</sup><br> `SelectField` <br> `SignatureField` <br> `TextAreaField` <br> `TextField` <br> `TimeField` <br> `WebsiteField` <br> _Also used by default for custom fields._ | n/a                                                                                                         |
| `values` _( [ string ] )_                                               | `MultiSelectField` <br> `PostCategoryField` <br> `PostCustomField` <br> `PostTagsField`                                                                                                                                                                                                                                                                                                                                                                                  | n/a                                                                                                         |

<a name="chainedSelectNote">1</a>: In order to use `chainedSelectValues` you must install and activate [Gravity Forms Chained Selects](https://www.gravityforms.com/add-ons/chained-selects/).<br>
<a name="uploadNote">2</a>: In order to use `fileUploadValues` or `postImageValues` , you must install and activate [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload). See [Submitting File Uploads](#submitting-file-uploads) below.<br>
<a name="productNote">3</a>: There are multiple types of [Gravity Forms Product Fields](https://docs.gravityforms.com/product/), each with their own input requirements. See [Submitting Product Fields](#submitting-product-fields) below.
<a name="captchaNote">4</a>: The `value` for a `Captcha` field is its validation token. See [Captcha Validation](#captcha-validation) below.<br>
<a name="consentNote">5</a>: The `value` for a `Consent` field treats any truthy string value as `true`, and an empty string (or no submission value) as `false`.<br>
<a name="quizNote">6</a>: [Gravity Forms Quiz Fields](https://docs.gravityforms.com/quiz-field/) can be either a Checkbox, Radio, or Select field. The field value input type is assigned accordingly.<br>
<a name="radioNote">6</a>: Radio fields expect the (string) `value` corresponding to the selected `choice`. If the "Other" choice is enabled on the field, then any string value that does not match one of the defined choices will be saved as the `value`.<br>

### Example Mutation

```graphql
{
  submitGfForm(
    input: {
      id: 50
      entryMeta {
        createdById: 1 # The user ID.
        ip: ""         # IP address
      }
      fieldValues: [
        {
          # Text field value
          id: 1
          value: "This is a text field value."
        }
        {
          # MultiSelect field value
          id: 2
          values: ["First Choice", "Second Choice"]
        }
        {
          # Address field value
          id: 3
          addressValues: {
            street: "1600 Pennsylvania Avenue NW"
            lineTwo: "Office of the President"
            city: "Washington"
            state: "DC"
            zip: "20500"
            country: "USA"
          }
        }
        {
          # ChainedSelect field value
          id: 4
          chainedSelectValues: [
            { inputId: 4.1, value: "Choice 1" }
            { inputId: 4.2, value: "Choice 2" }
          ]
        }
        {
          # Checkbox field value
          id: 5
          checkboxValues: [
            { inputId: 5.1, value: "This checkbox field is selected" }
            { inputId: 5.2, value: "This checkbox field is also selected" }
          ]
        }
        {
          # Email field value
          id: 6
          emailValues: {
            value: "myemail@email.test"
            confirmationValue: "myemail@email.test" # Only necessary if Email confirmation is enabled.
          }
        }
        {
          # Multi-column List field value
          id: 6
          listValues: [
            { rowValues: ["a", "b", "c"] }
            { rowValues: ["d", "e", "f"] }
          ]
        }
        {
          # Name field value
          id: 7
          nameValues: {
            prefix: "Mr."
            first: "John"
            middle: "Edward"
            last: "Doe"
            suffix: "PhD"
          }
        }
      ]
      saveAsDraft: false # If true, the submission will be saved as a draft entry.
      # Set the following to validate part of a multipage form without saving the submission.
      sourcePage: 1
      targetPage: 0
    }
  ) {
    confirmation {
      type    
      message # The message HTML - if the confirmation type is a "MESSAGE".
      url     # The redirect URL - if the confirmation type is a "REDIRECT".
    }
    errors {
      id # The field ID that failed validation.
      message
      connectedFormField { # The full FormField object if you need more info.
        database
        type
      }
    }
    entry {
      # See docs on querying Entries.
      id
      ... on GfSubmittedEntry {
        databaseId
      }
      ... on GfDraftEntry {
        resumeToken
      }
    }
  }
}
```

## Submission Validation and Confirmation.

In addition to any frontend, or GraphQL validation checks, Gravity Forms validates the values of each `formField` , and returns them in the `errors` field.

If the field is updated successfully, the `errors` field will be `null`, the `confirmation` field will be a `SubmissionConfirmation` object, and the `entry` in the response will be the newly updated `GfEntry` object which resolves to either a `GfSubmittedEntry` or `GfDraftEntry`.

If the field is NOT updated successfully, such as when a field validation error occurs, the `confirmation` and `entry` objects in the response will be `null` , and the `errors` field will provide data about the error. Example:

```json
"errors": [
  {
    "id": "1",
    "message": "The text entered exceeds the maximum number of characters.",
    "connectedFormField": {
      "database": 1,
      "type": "TEXT"
    }
  }
]
```

## Captcha Validation

As of v0.11.0, WPGraphQL for Gravity Forms supports server-side captcha validation. This is particularly useful with Google reCAPTCHA, as it keeps your API secret key hidden.

To validate a reCAPTCHA field, you need to [fetch the captcha response token](https://developers.google.com/recaptcha/docs/verify), and pass that to the field's `value` input argument:

```graphql
mutation submit( $token: String ) {
  submitGfForm(
    input: {
      id: 50
      fieldValues: [
        # other form fields would go here.
        {
          # Captcha Field
          id: 1
          value: $token # The google reCAPTCHA response token.
        }
      }
    }
  ) {
    errors {
      id
      message
    }
    confirmation {
      message
    }
    entry {
      databaseId
    }
  }
}
```

## Submitting File Uploads
To enable WPGraphQL support for submitting files (via the `fileUploadValues` or `postImageValues` inputs ), you must first install and activate the [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload) extension, which will add the `Upload` scalar type to the GraphQL schema.

> [!IMPORTANT]
>
> The GraphQL Spec - and many GraphQL clients - does not natively implement support the [`graphql-multipart-request-spec`](https://github.com/jaydenseric/graphql-multipart-request-spec) and may require an additional dependency such as [apollo-upload-client](https://github.com/jaydenseric/apollo-upload-client).

### Example Mutation

```graphql
mutation submit( $exampleUploads: [ Upload ], $exampleImageUpload: Upload ){ 
  submitGfForm(
    input: {
      id: 50
      fieldValues: [
        # other form fields would go here.
        {
          # FileUpload field
          id: 1
          fileUploadValues: $exampleUploads # An array of Upload objects. 
        }
        {
          # PostImage field
          id: 2
          postImageValues {
            altText: "Some alt text"
            caption: "Some caption"
            image: $exampleImageUpload # The Upload object
            description: "Some description"
            title: "Some title"
          }
        }
      ]
    }
  ) {
    errors {
      id
      message
    }
    confirmation {
      message
    }
    entry {
      databaseId
    }
  }
}
```

## Submitting Product Fields

While most Product Fields can be submitted with a simple `value` input, some require the use of one or more fields on the `productValues` input. Even if a Product Field accepts a `value` input, you can choose to use the `productValues` input instead to provide more granular control over the Product Field's values.

The `productValues` input takes a `price` and a `quantity` field. When the Form Field is configured to have a separate price or quantity field, those respective values become optional and will be overwritten by the latter fields.

### Example Mutation
```graphql
mutation submit {
  submitGfForm(
    input: {
      id: 50
      fieldValues: [
        # other form fields would go here.
        {
          # Product Calculation field
          id: 1
          productValues: { # Both are required.
            price: 10.00
            quantity: 2
          }
        },
        {
          # Hidden Product Field
          id: 2
          productValues: { # Only the quantity is necessary.
            quantity: 2
          }
          # can also be set like this
          value: "2"
        },
        {
          # Price Field
          id: 2
          productValues: { # Only the price is necessary.
            price: 50.00
          }
          # can also be set like this
          value: "50.00"
        },
        {
          # Radio / Select Product Field
          id: 2
          # Same as a normal Radio/Select field
          value: "second-choice"
        },
        {
          # Single Product field
          id: 1
          productValues: { # Only the quantity is required.
            price: 10.00
            quantity: 2
          }
          # can also be set like this
          value: "50.00"
        },
      ]
    }
  ) {
    errors {
      id
      message
    }
    confirmation {
      message
    }
    entry {
      databaseId
    }
  }
}
```

## Submitting Multi-page Forms

When submitting a multi-page form, you can use the `sourcePage` and `targetPage` inputs to validate a specific page of the form before proceeding to the next page. This can then be combined with the Mutation payload's `targetPageNumber` and `targetPageFormFields` to serve the correct fields for the next _valid_ page.

When using a `sourcePage`, only the fields on that page will be validated. If that page fails validation, the `targetPageNumber` and `targetPageFormFields` will return the current page number and fields, instead of the provided `targetPage` input. Similarly, if the page passes validation, but the `targetPage` is not available (e.g. due to conditional page logic), the `targetPageNumber` and `targetPageFormFields` will return the next available page number and fields.

Only once the `targetPage` input is greater than the number of pages in the form, will the submission be processed, _all_ the values validated, and the entry created. As such, when using this pattern, it is recommended to submit all the user-provided `fieldValues` inputs to the mutation, and not just the fields on the current page.

### Example Mutation

```graphql
mutation submit {
	submitGfForm(
		input: {
			id: 50
			fieldValues: [
				# other form fields would go here.
				{
					# Text field value
					id: 1
					value: "This is a text field value."
				}
			]
			saveAsDraft: false
			sourcePage: 1 # The page we are validating
			targetPage: 2 # The page we want to navigate to.
		}
	) {
		errors {
			id
			message
		}
		confirmation {
			message
		}
		entry { # Will only be returned if the `targetPage` is greater than the number of pages in the form.
			databaseId
		}
		targetPageNumber # The page number to navigate to. Will be the same as the `sourcePage` if validation fails, and different than the `targetPage` if the `targetPage` is not available.
		targetPageFormFields { # The form fields for the next page.
			nodes {
				databaseId
				# Other field data
			}
		}
	}
}
```
