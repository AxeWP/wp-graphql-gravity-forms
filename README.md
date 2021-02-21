# 🚀📄 WPGraphQL for Gravity Forms

[![Project Status: Active.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active) ![GitHub](https://img.shields.io/github/license/harness-software/wp-graphql-gravity-forms) ![GitHub release (latest SemVer including pre-releases)](https://img.shields.io/github/v/release/harness-software/wp-graphql-gravity-forms?include_prereleases) ![GitHub commits since latest release (by SemVer)](https://img.shields.io/github/commits-since/harness-software/wp-graphql-gravity-forms/0.2.0) ![GitHub forks](https://img.shields.io/github/forks/harness-software/wp-graphql-gravity-forms?style=social) ![GitHub Repo stars](https://img.shields.io/github/stars/harness-software/wp-graphql-gravity-forms?style=social)

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms.

- [Join the WPGraphQL community on Slack.](https://join.slack.com/t/wp-graphql/shared_invite/zt-3vloo60z-PpJV2PFIwEathWDOxCTTLA)

## Overview <a name="overview" />

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations.

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

Fortunately, a GraphQL implementation exists for WordPress - [WPGraphQL](https://github.com/wp-graphql/wp-graphql).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to interact with the Gravity Forms data stored in a headless WordPress backend. This plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms.

## System Requirements <a name="system-requirements" />

- PHP 7.4+
- WPGraphQL 1.0.0+
- Gravity Forms 2.4+
- WordPress 5.4.1+

## Quick Install <a name="quick-install" />

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/).
2. Install & activate [Gravity Forms](https://www.gravityforms.com/) and any supported addons.
3. Download the zip of this repository and upload it to your WordPress install, and activate the plugin.

## Supported Features <a name="supported-features"/>

- Querying forms and entries.
- Updating and deleting draft entries, and submitting forms.
- Deleting entries.

### Supported Form Fields <a name="supported-fields">

| Field             | Querying<sup>[1](#supportsQuery)</sup> | Updating<sup>[2](#supportsMutation)</sup> |
| ----------------- | -------------------------------------- | ----------------------------------------- |
| Address           | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Captcha           | :heavy_check_mark:                     | N/A                                       |
| Chained Selects   | :heavy_check_mark:                     | :hammer:                                  |
| Checkbox          | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Date              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Email             | :heavy_check_mark:                     | :heavy_check_mark:                        |
| FileUpload        | :heavy_check_mark:                     | :hammer:                                  |
| Hidden            | :heavy_check_mark:                     | :hammer:                                  |
| HTML              | :heavy_check_mark:                     | N/A<sup>[3](#supportsNA)</sup>            |
| List              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Multiselect       | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Name              | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Number            | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Page              | :heavy_check_mark:                     | N/A<sup>[3](#supportsNA)</sup>            |
| Password          | :heavy_check_mark:                     | :hammer:                                  |
| Phone             | :heavy_check_mark:                     | :heavy_check_mark:                        |
| Post Category     | :heavy_check_mark:                     | :hammer:                                  |
| Post Content      | :heavy_check_mark:                     | :hammer:                                  |
| Post Custom Field | :heavy_check_mark:                     | :hammer:                                  |
| Post Excerpt      | :heavy_check_mark:                     | :hammer:                                  |
| Post Image        | :heavy_check_mark:                     | :hammer:                                  |
| Post Tags         | :heavy_check_mark:                     | :hammer:                                  |
| Post Title        | :heavy_check_mark:                     | :hammer:                                  |
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

## Future Feature Enhancements <a name="future-enhancements" />

- Add support for backwards pagination of lists of entries.
- Add support for updating draft entries with additional form fields.
- Ability to query for lists of draft entries, or both entries and draft entries
- Ability to get the total count for a list of entries.
- Ability to update an individual Gravity Forms entry.
- Ability to create, update, and delete an individual Gravity Form.
- Create & update integration tests.

# Documentation <a name="documentation" />

## Get a Form and its Fields <a name="documentation-get-form" />

The example query below shows how you can get a form and its fields.
If you want to get the form with an ID of `1`, you need to generate a global ID for that object and pass the global ID in as the `id` input. This can be done in JavaScript using the `btoa()` function like this, where `GravityFormsForm` is the GraphQL type and `1` is the form ID:

```js
const globalId = btoa(`GravityFormsForm:1`); // Results in "R3Jhdml0eUZvcm1zRm9ybTox"
```

For `fields`, pass in `first:300`, where `300` is the maximum number of fields you want to query for.

Inside of `fields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragment for every type of field that your form has.

### Example Query

```graphql
{
  gravityFormsForm(id: "R3Jhdml0eUZvcm1zRm9ybTox") {
    formId
    cssClass
    cssClassList
    fields(first: 300) {
      nodes {
        ... on TextField {
          type
          id
          label
          cssClass
          cssClassList
        }
        ... on TextAreaField {
          type
          id
          label
          cssClass
          cssClassList
        }
        ... on SelectField {
          type
          id
          label
          cssClass
          cssClassList
        }
      }
    }
  }
}
```

## Submit a Form Entry <a name="documentation-submit-form-entry" />

The form entry submission process works like this:

1. Send a `createGravityFormsDraftEntry` mutation to create a new draft form entry. This gives you back the `resumeToken`, which is the unique identifier for the draft entry that you need to pass in to all the mutations below.
2. Send as many "update" mutations (such as `updateDraftEntryTextFieldValue`, `updateDraftEntrySelectFieldValue`, etc.) as you need to update the values of the draft entry.
3. When ready, send a `submitGravityFormsDraftEntry` that turns the draft entry into a permanent entry.

If you're wondering why several mutations are required to submit a form entry rather than just a single mutation, please read the comments in [this issue](https://github.com/harness-software/wp-graphql-gravity-forms/issues/4#issuecomment-563305561).

For large forms, #2 on the list above could potentially result in the need to send a large number of "update" mutations to the backend to update form entry field values. Using something like Apollo Client's [apollo-link-batch-http](https://www.apollographql.com/docs/link/links/batch-http/) is recommended so that your app will be able to send a large number of mutations to the backend all within a single HTTP request to update the draft entry.

<strong>Note:</strong> Not all fields currently support updates. For a list of field types that are currently supported, please review the [Supported Form Fields table](#supported-fields).

### Example Mutations

#### Create a New Draft Entry

```graphql
mutation {
  createGravityFormsDraftEntry(
    input: { clientMutationId: "123abc", formId: 2 }
  ) {
    resumeToken
  }
}
```

#### Update a Draft Entry

The example below shows `updateDraftEntryTextFieldValue`, which can be used for updating the value of a text field. Similar mutations exist for other field types, such as `updateDraftEntrySelectFieldValue`, `updateDraftEntryAddressFieldValue`, etc.

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
      fields(first: 300) {
        edges {
          node {
            ... on TextField {
              id
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
    errors {
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
    "message": "The text entered exceeds the maximum number of characters."
  }
]
```

#### Submit a Draft Entry

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
      fields {
        edges {
          node {
            __typename
            ... on TextField {
              type
              id
            }
          }
          fieldValue {
            __typename
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

## Delete a Draft Entry <a name="documentation-delete-draft-entry" />

The mutation below shows how to delete a draft entry. The `resumeToken` of the deleted draft entry will be in the response.

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

## Get a Single Draft Entry <a name="documentation-get-draft-entry" />

The `gravityFormsEntry` query supports both entries and draft entries. See the "Get a Single Entry" section below.

## Get a List of Entries <a name="documentation-get-entries" />

The code comments in the example below explain how you can get a filtered list of entries.

The plugin supports first/after cursor-based pagination, but does not yet support before/last pagination.

Inside of `fields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragments inside of `node { ... }` and inside of `fieldValue { ... }` for every type of field that your form has.

### Example Query

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
      fieldFiltersMode: "all"
      fieldFilters: [
        # Find entries created by user ID 1.
        { key: "created_by", intValues: [1], operator: IN }
        # Find entries where field 5 has a value of "somevalue".
        { key: "5", stringValues: ["somevalue"], operator: IN }
      ]
    }
  ) {
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
      fields(first: 300) {
        edges {
          node {
            ... on TextField {
              type
              label
            }
            ... on SelectField {
              type
              label
            }
            ... on AddressField {
              type
              label
            }
          }
          fieldValue {
            ... on TextFieldValue {
              value
            }
            ... on SelectFieldValue {
              value
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
}
```

## Get a Single Entry <a name="documentation-get-single-entry" />

The example query below shows how you can get a single entry by ID, and data about the fields and their values.

If you want to get the form with an ID of `1`, you need to generate a global ID for that object and pass the global ID in as the `id` input. This can be done in JavaScript using the `btoa()` function like this, where `GravityFormsEntry` is the GraphQL type and `1` is the form ID:

```js
const globalId = btoa(`GravityFormsEntry:1`); // Results in "R3Jhdml0eUZvcm1zRW50cnk6MQ=="
```

The `id` input field can be the entry's ID, or the `resumeToken` if it is a draft entry.

For `fields`, pass in `first: 300`, where `300` is the maximum number of fields you want to query for.

Inside of `fields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragments inside of `node { ... }` and inside of `fieldValue { ... }` for every type of field that your form has.

### Example Query

```graphql
{
  gravityFormsEntry(id: "R3Jhdml0eUZvcm1zRW50cnk6MQ==") {
    id
    entryId
    formId
    isDraft
    resumeToken
    fields(first: 300) {
      edges {
        node {
          ... on TextField {
            type
            label
          }
          ... on SelectField {
            type
            label
          }
          ... on AddressField {
            type
            label
          }
        }
        fieldValue {
          ... on TextFieldValue {
            value
          }
          ... on SelectFieldValue {
            value
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

## Delete an Entry <a name="documentation-delete-entry" />

The mutation below shows how to delete an entry. The `entryId` of the deleted entry will be in the response.

### Example Mutation

```graphql
mutation {
  deleteGravityFormsEntry(input: { clientMutationId: "123abc", entryId: 5 }) {
    entryId
  }
}
```

## Get a List of Forms <a name="documentation-get-forms" />

The example query below shows how you can fetch data for multiple forms at once.

Cursor-based pagination is supported. You can use the `first`, `last`, `before` and `after` fields, along with the data inside of `pageInfo` and the cursors returned by the API to get each page of forms data.

Filtering is also supported. For the `where` field, you can specify a `status` to get forms that are active, inactive, in the trash, etc.

For `fields`, pass in `first: 300`, where `300` is the maximum number of fields you want to query for.

Inside of `fields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragment for every type of field that your forms have.

### Example Query

```graphql
{
  gravityFormsForms(first: 10, after: null, where: { status: ACTIVE }) {
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
        fields(first: 300) {
          nodes {
            ... on TextField {
              type
              id
              label
              cssClass
              cssClassList
            }
            ... on TextAreaField {
              type
              id
              label
              cssClass
              cssClassList
            }
            ... on SelectField {
              type
              id
              label
              cssClass
              cssClassList
            }
          }
        }
      }
    }
  }
}
```
