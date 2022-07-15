# Submitting Forms

Form submissions are handled with the `submitGfForm` mutation.

This mutation can be used either to submit an Entry or to submit a draft entry, by toggling the `saveAsDraft` input to `true` .

The `fieldValues` input takes an array of objects containing the `id` of the field, and a value input that corresponds to the Gravity Forms Field type.

**Note**: Due to [GraphQL's current lack of support for Input Union types](https://github.com/harness-software/wp-graphql-gravity-forms/issues/4#issuecomment-563305561), you must use the specific value type specific to that field. A full list of field value types and their corresponding field fragments are below.

## Supported Field Value input types

| Field Value Input Type                                                  | Used for                                                                                                                                                                                                                                                 | Sub-fields                                                       |
| ----------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------- |
| `addressValues` _( obj )_                                               | `AddressField` | `city` <br> `country` <br> `lineTwo` <br> `state` <br> `street` <br> `zip` |
| `chainedSelectValues` _( [ obj ] )_ <sup>[1](#chainedSelectsNote)</sup> | `ChainedSelectField` | `inputId` <br> `value` |
| `checkboxValues` _( [ obj ] )_                                          | `CheckboxField` <br> `QuizField` <sup>[3](#quizNote)</sup>                                                                                                                                                                                                                                          | `inputId` <br> `value` |
| `consentValue` _( boolean )_                                                 | `ConsentField` | |
| `emailValues` _( obj )_                                                 | `EmailField` | `confirmationValue` <br/> `value` |
| `fileUploadValues` _( [ Upload ] )_<sup>[2](#uploadNote)</sup>          | `FileUploadField` | See [Submitting File Uploads](#submitting-file-uploads)                                                              |
| `listValues` _( [ obj ] )_                                              | `ListField` | `rowValues` _( [ String ] )_                                     |
| `nameValues` _( obj )_                                                  | `NameField` | `first` <br> `last` <br> `midele` <br> `prefix` <br> `suffix` |
| `postImageValues` _( obj )_ <sup>[2](#uploadNote)</sup>                 | `PostImageField` | `altText` <br> `caption` <br> `description` <br> `image` _(Upload)<sup>[2](#uploadNote)</sup>_ <br> `title` |
| `value` _( string )_                                                    | `CaptchaField` <sup>[3](#captchaNote)</sup><br> `ConsentField` <br> `DateField` <br> `HiddenField` <br> `NumberField` <br> `PhoneField` <br> `PostContentField` <br> `PostExcerptField` <br> `PostTitleField` <br> `QuizField` <sup>[4](#quizNote)</sup><br> `RadioField` <br> `SelectField` <br> `SignatureField` <br> `TextAreaField` <br> `TextField` <br> `TimeField` <br> `WebsiteField` <br> _Also used by default for custom fields._| n/a                                                              |
| `values` _( [ string ] )_                                               | `MultiSelectField` <br> `PostCategoryField` <br> `PostCustomField` <br> `PostTagsField` | n/a                                                              |

<a name="chainedSelectNote">1</a>: In order to use `chainedSelectValues` you must install and activate [Gravity Forms Chained Selects](https://www.gravityforms.com/add-ons/chained-selects/).<br>
<a name="uploadNote">2</a>: In order to use `fileUploadValues` or `postImageValues` , you must install and activate [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload). See [Submitting File Uploads](#submitting-file-uploads) below.<br>
<a name="captchaNote">2</a>: The `value` for a `Captcha` field is its validation token. See [Captcha Validation](#captcha-validation) below.<br>
<a name="quizNote">3</a>: [Gravity Forms Quiz Fields](https://docs.gravityforms.com/quiz-field/) can be either a Checkbox, Radio, or Select field. The field value input type is assigned accordingly.

### Example Mutation

```graphql
{
  submitGfForm(
    input: {
      formId: 50
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
          listValues: { rowValues: ["a", "b", "c"] }
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
      id # The field that failed validation.
      message
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
    "message": "The text entered exceeds the maximum number of characters."
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
      formId: 50
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

**Note**: The GraphQL Spec - and many GraphQL clients - does not natively implement support the [`graphql-multipart-request-spec`](https://github.com/jaydenseric/graphql-multipart-request-spec), and may require an additional dependency such as [apollo-upload-client](https://github.com/jaydenseric/apollo-upload-client).

### Example Mutation

```graphql
mutation submit( $exampleUploads: [ Upload ], $exampleImageUpload: Upload ){ 
  submitGfForm(
    input: {
      formId: 50
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
