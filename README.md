# 🚀📄 WPGraphQL for Gravity Forms

[![Project Status: Active.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)
![Packagist License](https://img.shields.io/packagist/l/harness-software/wp-graphql-gravity-forms?color=green)
![Packagist Version](https://img.shields.io/packagist/v/harness-software/wp-graphql-gravity-forms?label=stable)
![GitHub commits since latest release (by SemVer)](https://img.shields.io/github/commits-since/harness-software/wp-graphql-gravity-forms/0.4.1)
![GitHub forks](https://img.shields.io/github/forks/harness-software/wp-graphql-gravity-forms?style=social)
![GitHub Repo stars](https://img.shields.io/github/stars/harness-software/wp-graphql-gravity-forms?style=social)

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms.

- [Join the WPGraphQL community on Slack.](https://join.slack.com/t/wp-graphql/shared_invite/zt-3vloo60z-PpJV2PFIwEathWDOxCTTLA)
- [Supported Fields](#supported-fields)
- [Documentation](#documentation)
  - [Querying Forms & Entries](#querying-forms-and-entries)
    - [Get a form & its fields](#documentation-get-form)
    - [Get a list of forms](#documentation-get-forms)
    - [Get a single entry](#documentation-get-single-entry)
    - [Get a single draft entry](#documentation-get-draft-entry)
    - [Get a list of entries](#documentation-get-entries)
    - [Global IDs vs Database IDs](#documentation-using-global-ids)
  - [Submit a form](#documentation-submit-form)
    - [Using `submitGravityFormsForm` (v0.4.0+)](#documentation-submit-form-mutation)
    - [Building a draft entry incrementally](#documentation-submit-entry-incrementally)
  - [ Updating Entries and Draft Entries](#documentation-update-entry)
  - [ Deleting Entries and Draft Entries](#documentation-delete-entries)

## Overview

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations.

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

Fortunately, a GraphQL implementation exists for WordPress - [WPGraphQL](https://www.wpgraphql.com/).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to interact with the Gravity Forms data stored in a headless WordPress backend. This plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms.

## System Requirements

- PHP 7.4+
- WPGraphQL 1.0.0+
- Gravity Forms 2.4+
- WordPress 5.4.1+

## Quick Install

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/).
2. Install & activate [Gravity Forms](https://www.gravityforms.com/) and any supported addons.
3. Download the zip of this repository and upload it to your WordPress install, and activate the plugin.

## Supported Features

- Querying forms and entries.
- Submitting forms.
- Creating, updating, and deleting draft entries.
- Updating and deleting entries.

<a name="supported-fields" />

### Supported Form Fields

| Field             | Querying<sup>[1](#supportsQuery)</sup> | Updating<sup>[2](#supportsMutation)</sup> |
| ----------------- | -------------------------------------- | ----------------------------------------- |
| Address           | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Captcha           | :heavy_check_mark:                     | N/A<sup>[4](#supportsCaptcha)             |
| Chained Selects   | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Checkbox          | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Consent           | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Date              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Email             | :heavy_check_mark:                     | :heavy_check_mark:                        |
| FileUpload        | :heavy_check_mark:                     | :hammer:                                  |
| Hidden            | :heavy_check_mark:                     | :heavy_check_mark:                        |
| HTML              | :heavy_check_mark:                     | N/A<sup>[3](#supportsNA)</sup>            |
| List              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Multiselect       | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Name              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Number            | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Page              | :heavy_check_mark:                     | N/A<sup>[3](#supportsNA)</sup>            |
| Password          | :heavy_check_mark:                     | :hammer:                                  |
| Phone             | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Category     | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Content      | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Custom Field | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Excerpt      | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Image        | :heavy_check_mark:                     | :hammer:                                  |
| Post Tags         | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Title        | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Radio             | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Section           | :heavy_check_mark:                     | N/A<sup>[3](#supportsNA)</sup>            |
| Select            | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Signature         | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Text              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| TextArea          | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Time              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Website           | :heavy_check_mark:                     | :heavy_check_mark:                        |

<a name="supportsQuery">1</a>: Supports [querying the field and its properties](#documentation-get-form).

<a name="supportsMutation">2</a>: Supports updating the field.

<a name="supportsNA">3</a>: This field is for display purposes only, so there is no need for updating.

<a name="supportsCaptcha">4</a>: Captcha fields should be validated before the form is submitted, so it doesn't make sense to handle a server-side update. If you have a use case for when a captcha field should be validated server-side, please submit a Feature Request.

<a name="future-enhancements" />

## Future Feature Enhancements

- Add support for backwards pagination of lists of entries.
- Add support for updating draft entries with additional form fields.
- Ability to query for lists of draft entries, or both entries and draft entries
- Ability to get the total count for a list of entries.
- Ability to create, update, and delete an individual Gravity Form.
- Create & update integration tests.

# Documentation

## Querying Forms and Entries

<a name="documentation-get-form" />

### Get a Form and its Fields

The example query below shows how you can get a form and its fields.

The `id` input accepts either the Gravity Forms form ID (`idType: DATABASE_ID`) or a [global ID](#documentation-using-global-ids) (`idType: ID`).

For `formFields`, pass in `first:300`, where `300` is the maximum number of fields you want to query for.

Inside of `formFields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragment for every type of field that your form has.

#### Example Query

```graphql
{
  gravityFormsForm(id: 50, idType: DATABASE_ID) {
    formId
    cssClass
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

<a name="documentation-get-forms" />

### Get a List of Forms

The code comments in the example query below shows how you can fetch data for multiple forms at once.

Cursor-based pagination is supported. You can use the `first`, `last`, `before` and `after` fields, along with the data inside of `pageInfo` and the cursors returned by the API to get each page of forms data.

Filtering is also supported. For the `where` field, you can specify a `status` to get forms that are active, inactive, in the trash, etc.

#### Example Query

```graphql
{
  gravityFormsForms(
    first: 10
    after: "eyJvZmZzZXQiOjAsImluZGV4Ijo0fQ==" # Or pass null to start from the beginning.
    where: { status: ACTIVE }
  ) {
    pageInfo {
      startCursor
      endCursor
      hasPreviousPage
      hasNextPage
    }
    edges {
      cursor
      node {
        formId
        title
        formFields(first: 300) {
          nodes {
            type
            id
            cssClass
            ... on TextField {
              label
            }
          }
        }
      }
    }
  }
}
```

<a name="documentation-get-single-entry" />

### Get a Single Entry

The example query below shows how you can get a single entry by ID, and data about the fields and their values.

The `id` input accepts either the Gravity Forms Entry ID (`idType: DATABASE_ID`), or a [global ID](#documentation-using-global-ids) (`idType: ID`). The `id` input can also accept the `resumeToken` for a draft entry when `idType` is set to `ID`.

For `formFields`, pass in `first: 300`, where `300` is the maximum number of fields you want to query for.

Inside of `formFields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragments inside of `node { ... }` and inside of `fieldValue { ... }` for every type of field that your form has.

#### Example Query

```graphql
{
  gravityFormsEntry(id: 2977, idType: DATABASE_ID) {
    id # global ID
    entryId # database ID
    formId
    isDraft
    resumeToken
    formFields(first: 300) {
      edges {
        node {
          id
          type
          ... on TextField {
            label
          }
          ... on CheckboxField {
            label
            choices {
              isSelected
            }
          }
          ... on AddressField {
            label
            inputs {
              isHidden
            }
          }
        }
        fieldValue {
          ... on TextFieldValue {
            value
          }
          ... on CheckboxFieldValue {
            checkboxValues {
              inputId
              value
            }
          }
          ... on AddressFieldValue {
            street
            lineTwo
            city
            state
            zip
            country
          }
        }
      }
    }
  }
}
```

<a name="documentation-get-draft-entry" />

### Get a Single Draft Entry

The `gravityFormsEntry` query supports both entries and draft entries. See ["Get a Single Entry"](#documentation-get-single-entry) above.

To query a Draft Entry, simply pass the `resumeToken` to the input `id` field, and set `idType` to ID.

#### Example Query

```graphql
{
  gravityFormsEntry(id: "f82a5d986f4d4f199893f751adee98e9", idType: ID) {
		# The fields you want to return.
	}
}
```

<a name="documentation-get-entries" />
### Get a List of Entries

The code comments in the example query below explain how you can get a filtered list of entries.

The plugin supports first/after cursor-based pagination, but does not yet support before/last pagination.

#### Example Query

```graphql
{
  gravityFormsEntries(
    first: 20
    after: "eyJvZmZzZXQiOjAsImluZGV4Ijo0fQ==" # Or pass null to start from the beginning.
    where: {
      # List of all the form IDs to include.
      formIds: [1]
      # Find entries between this start & end date.
      dateFilters: {
        startDate: "2019-09-22 02:26:23"
        endDate: "2019-10-25 02:26:23"
      }
      fieldFiltersMode: ALL
      fieldFilters: [
        # Find entries created by user ID 1.
        { key: "created_by", intValues: [1], operator: IN }
        # Find entries where field 5 has a value of "somevalue".
        { key: "5", stringValues: ["somevalue"], operator: IN }
      ]
      # Sort fields in ascending order by "date_created"
      sort: { direction: ASC, isNumeric: false, key: "date_created" }
      # Show only active entries.
      status: ACTIVE
    }
  ) {
    pageInfo {
      startCursor
      endCursor
      hasPreviousPage
      hasNextPage
    }
    nodes {
      entryId
      formId
      isDraft
      status
      dateCreated
      createdBy {
        node {
          userId
          name
        }
      }
      formFields(first: 300) {
        edges {
          node {
            type
            ... on TextField {
              type
            }
          }
          fieldValue {
            ... on TextFieldValue {
              value
            }
          }
        }
      }
    }
  }
}
```

<a name="documentation-using-global-ids" />

### Global IDs vs Database IDs

The `id` inputs for Form and Entry queries accepts either the Gravity Forms ID (`idType: DATABASE_ID`) assigned to the database, or a Global (base-64 encoded) ID (`idType: ID`).

To generate global ID for an object, you encode the name of the GraphQL type, followed by the database ID. This can be done in JavaScript using the `btoa()` function like this, where `GravityFormsForm` is the GraphQL type and `1` is the form ID:

```js
const globalId = btoa(`GravityFormsForm:1`); // Results in "R3Jhdml0eUZvcm1zRm9ybTox"
```

The example query below shows how you can use a Global ID as your input:

```graphql
{
  gravityFormsForm(id: "R3Jhdml0eUZvcm1zRm9ybTox", idType: ID) {
    formId
    dateCreated
    isActive
    isTrash
  }
}
```

<a name="documentation-submit-form" />

## Submit a Form

There are two different ways to submit forms: using `submitGravityFormsForm` (which allows you to batch submit all of the field values at once), or by incrementally building a draft entry.

<strong>Note:</strong> Not all form fields currently support updates. For a list of field types that are currently supported, please review the [Supported Form Fields table](#supported-fields).

<a name="documentation-submit-form-mutation" />

### Submit a form with `submitGravityFormsForm`

The example mutation below shows how you can submit a form.

The `submitGravityFormsForm` can also be used to submit draft entries by setting the `saveAsDraft` input to `true`.

The `fieldValues` input takes an array of objects containing the `id` of the field, and a value field that is determined by the field type. Most fields use `value`, however string arrays values (e.g. MultiSelect, Post Category, etc) use `values`, and more complex fields use their own value type. An example of each is included below.

This is an interim solution until [the GraphQL Spec adds support for Input Unions](https://github.com/harness-software/wp-graphql-gravity-forms/issues/4#issuecomment-563305561).

```graphql
{
  submitGravityFormsForm(
    input: {
      formId: 50
      clientMutationId: "123abc"
      createdBy: 1 # The user ID.
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
            {
              inputId: 5.1
              value: "This checkbox field is selected"
            }
            {
              inputId: 5.2
              value: "This checkbox field is also selected"
            }
          ]
        }
        {
          # Multi-column List field value
          id: 6
          listValues: {
            rowValues: ["a", "b", "c"]
          }
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
      ip: "" # IP address
      saveAsDraft: false # If true, the submission will be saved as a draft entry.
      # Set the following to validate part of a multipage form without saving the submission.
      sourcePage: 1
      targetPage: 0
    }
  ) {
    errors {
      id # The field that failed validation.
      message
    }
    entryId # Will return null if submitting a draft entry
    resumeToken # Will return null if submitting an entry.
    entry {
      # See above section on querying Entries.
      id
    }
  }
}
```

If the field is updated successfully, the `errors` field will be `null`, and the `entry` in the response will be the newly updated entry object. Depending on whether `saveAsDraft` is `true`, you will either get the new `entryId` or the `resumeToken`, with the other value set to `null`.

If the field is NOT updated successfully, such as when a field validation error occurs, the `entry` object in the response will be `null`, and the `errors` field will provide data about the error. Example:

```json
"errors": [
  {
    "id": "1",
    "message": "The text entered exceeds the maximum number of characters."
  }
]
```

<a name="documentation-submit-entry-incrementally">

### Submit a form by building a draft entry incremmentally.

Another way to submit forms is to incrementally build a draft entry. **This process may be deprecated and removed in future versions of the plugin**.

The submission process works like this:

1. Send a `createGravityFormsDraftEntry` mutation to create a new draft form entry. This gives you back the `resumeToken`, which is the unique identifier for the draft entry that you need to pass in to all the mutations below.
2. Send as many "update" mutations (such as `updateDraftEntryTextFieldValue`, `updateDraftEntrySelectFieldValue`, etc.) as you need to update the values of the draft entry.
3. When ready, send a `submitGravityFormsDraftEntry` that turns the draft entry into a permanent entry.

For large forms, #2 on the list above could potentially result in the need to send a large number of "update" mutations to the backend to update form entry field values. Using something like Apollo Client's [apollo-link-batch-http](https://www.apollographql.com/docs/link/links/batch-http/) is recommended so that your app will be able to send a large number of mutations to the backend all within a single HTTP request to update the draft entry.

#### Example Mutations

##### Create a New Draft Entry

The example belows how to create a new draft entry using the `createGravityFormsDraftEntry` mutation. If you wish, you can create a new draft entry by cloning an existing entry using the `fromEntryId` field.

```graphql
mutation {
  createGravityFormsDraftEntry(
    input: {
      clientMutationId: "123abc"
      formId: 2
      fromEntryId: 20 # Optional. This will copy all of the values from Entry 20 into the newly created draft entry.
    }
  ) {
    resumeToken
  }
}
```

##### Update an individual Draft Entry value.

The example below shows `updateDraftEntryTextFieldValue`, which can be used for updating the value of a text field. Similar mutations exist for other field types, such as `updateDraftEntrySelectFieldValue`, `updateDraftEntryAddressFieldValue`, etc. The `value` shape is the same as used [in the `fieldValues` input of `submitGravityFormsForm`](#documentation-submit-gravity-forms-form).

Use the `resumeToken` from the `createGravityFormsDraftEntry` mutation's response. It is this draft entry's unique identifier.

```graphql
mutation {
  updateDraftEntryTextFieldValue(
    input: {
      clientMutationId: "123abc"
      resumeToken: "524d5f3a30c845b29a8db35c9f2aaf29"
      fieldId: 5
      value: "new text field value"
    }
  ) {
    resumeToken
    entry {
      entryId # This will be null, since draft entries don't have an ID yet.
      resumeToken # This will be the same resumeToken that was passed in.
      isDraft # This will be set to true.
      formFields(first: 300) {
        edges {
          fieldValue {
            ... on TextFieldValue {
              value
            }
          }
        }
      }
    }
    errors {
      id
      message
    }
  }
}
```

If the field is updated successfully, the `errors` field will be `null`, and the `entry` in the response will be the newly updated entry, with the new field value.

If the field is NOT updated successfully, such as when a field validation error occurs, the `entry` object in the response will be unchanged (the new field value will NOT have been applied), and the `errors` field will provide data about the error. Example:

```json
"errors": [
  {
    "id": "1",
    "message": "The text entered exceeds the maximum number of characters."
  }
]
```

##### Submit a Draft Entry

Once all updates have been performed, the `submitGravityFormsDraftEntry` mutation shown below can be run to submit the draft entry so that it becomes a permanent entry.

The `entry` field in the response contains data for the newly created entry.

```graphql
mutation {
  submitGravityFormsDraftEntry(
    input: {
      clientMutationId: "123abc"
      resumeToken: "5df948218f40484d81e808d0ebc8651b"
    }
  ) {
    entryId # This will be the ID of the newly created entry.
    entry {
      entryId # This will be the ID of the newly created entry.
      resumeToken # This will be null, since the entry is no longer a draft.
      isDraft # This will be set to false.
      formFields {
        edges {
          fieldValue {
            __typename
            ... on TextFieldValue {
              value
            }
          }
        }
      }
    }
    errors {
      id
      message
    }
  }
}
```

If the field is updated successfully, the `errors` field will be `null`, and the `entry` in the response will be the newly updated entry.

If the field is NOT updated successfully, such as when a field validation error occurs, the `entry` object in the response will be `null`, and the `errors` field will provide data about the error.

<a name="documentation-update-entry" />
## Update an Entry or Draft Entry

You can update an entry with the `updateGravityFormsEntry` mutation. This mutation works similarly to [`submitGravityFormsForm`](#documentation-submit-gravity-forms-form).

### Example Mutation

```graphql
{
  updateGravityFormsEntry(
    input: {
      clientMutationId: "123abc"
      entryId: 1
      isRead: false # Optional. Used to mark the entry as read.
      isStarred: false # Optional. Used to mark the entry as 'starred'.
      status: ACTIVE # Optional. Can be used to mark an entry as trash or spam.
      fieldValues: [
        {
          # See the above section on using `submitGravityFormsForm`
          id: 1
          value: "This is a text field value."
        }
      ]
    }
  ) {
    errors {
      id # The field that failed validation.
      message
    }
    entryId
    entry {
      # See above section on querying Entries.
      id
    }
  }
}
```

<a name="documentation-update-draft-entry" />

Updating a draft entry using the `updateGravityFormsDraftEntry` mutation follows a similar pattern: :

### Example Mutation

```graphql
{
  updateGravityFormsEntry(
    input: {
      clientMutationId: "123abc"
      resumeToken: "f82a5d986f4d4f199893f751adee98e9"
      fieldValues: [
        {
          # See the above section on using `submitGravityFormsForm`
          id: 1
          value: "This is a text field value."
        }
      ]
    }
  ) {
    errors {
      id # The field that failed validation.
      message
    }
    entryId
    entry {
      # See above section on querying Entries.
      id
    }
  }
}
```

<a name="documentation-delete-entry" />

## Delete an Entry or Draft Entry

You can use the `deleteGravityFormsEntry` mutation to delete an entry. The `entryId` of the deleted entry will be in the response.

### Example Mutation

```graphql
mutation {
  deleteGravityFormsEntry(input: { clientMutationId: "123abc", entryId: 5 }) {
    entryId
  }
}
```

Similarly, you can use `deleteGravityFormsDraftEntry` to delete a draft entry. The `resumeToken` of the deleted draft entry will be in the response.

### Example Mutation

```graphql
mutation {
  deleteGravityFormsDraftEntry(
    input: {
      clientMutationId: "123abc"
      resumeToken: "524d5f3a30c845b29a8db35c9f2aaf29"
    }
  ) {
    resumeToken
  }
}
```
