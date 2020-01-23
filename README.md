# 🚀📄 WPGraphQL for Gravity Forms

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms. This is currently an unfinished work in progress that is being actively developed.

## What can it do?

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations.

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

Fortunately, a GraphQL implementation exists for WordPress - [WPGraphQL](https://github.com/wp-graphql/wp-graphql).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to interact with the Gravity Forms data stored in a headless WordPress backend. This plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms.

## Getting Started

1. Use [Composer](https://getcomposer.org/) to require the plugin as a dependency of your project. Alternatively, you can download it into your `plugins` directory, just like any other WordPress plugin.
1. Activate the plugin, along with the [WPGraphQL](https://www.wpgraphql.com/) and [Gravity Forms](https://www.gravityforms.com/) plugins that it depends on.
1. Use a tool like [GraphiQL](https://electronjs.org/apps/graphiql) to view the schema and send a few test requests to your `/graphql` endpoint to interact with Gravity Forms data, and start sending requests from your frontend app.

---

# Documentation

## Get a Form and its Fields

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

## Submit a Form Entry

The form entry submission process works like this:

1. Send a `createGravityFormsDraftEntry` mutation to create a new draft form entry. This gives you back the `resumeToken`, which is the unique identifier for the draft entry that you need to pass in to all the mutations below.
2. Send as many "update" mutations (such as `updateDraftEntryTextFieldValue`, `updateDraftEntrySelectFieldValue`, etc.) as you need to update the values of the draft entry.
3. When ready, send a `submitGravityFormsDraftEntry` that turns the draft entry into a permanent entry.

If you're wondering why several mutations are required to submit a form entry rather than just a single mutation, please read the comments in [this issue](https://github.com/harness-software/wp-graphql-gravity-forms/issues/4#issuecomment-563305561).

For large forms, #2 on the list above could potentially result in the need to send a large number of "update" mutations to the backend to update form entry field values. Using something like Apollo Client's [apollo-link-batch-http](https://www.apollographql.com/docs/link/links/batch-http/) is recommended so that your app will be able to send a large number of mutations to the backend all within a single HTTP request to update the draft entry.

As of Dec. 23, 2019, updating draft entries with file upload field data is not yet supported, along with a few other field types.

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
    resumeToken # This will be null, since the entry is no longer a draft.
    isDraft # This will be set to false.
    entry {
      entryId
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

## Delete a Draft Entry

The mutation below shows how to delete a draft entry. The `resumeToken` of the deleted draft entry will be in the response.

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

## Get a Single Draft Entry

The `gravityFormsEntry` query supports both entries and draft entries. See the "Get a Single Entry" section below.

## Get a List of Entries

The code comments in the example below explain how you can get a filtered list of entries.

As of Oct. 28, 2019, entries pagination is not supported. Support will be added soon.

Inside of `fields`, you must include query fragments indicating what data you'd like back for each field, as shown below. You'll want to make sure that you have a fragments inside of `node { ... }` and inside of `fieldValue { ... }` for every type of field that your form has.

### Example Query

```graphql
{
  gravityFormsEntries(
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
        { key: "created_by", intValues: [1], operator: "in" }
        # Find entries where field 5 has a value of "somevalue".
        { key: "5", stringValues: ["somevalue"], operator: "in" }
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

## Get a Single Entry

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

## Feature Roadmap

### Coming soon

- Add support for pagination of lists of entries.
- Ability to query for lists of draft entries, or both entries and draft entries
- Add support for updating draft entries with file upload data.
- Ability to get the total count for a list of entries.
- Ability to delete an individual Gravity Forms entry.
- Ability to update an individual Gravity Forms entry.
- Create & update integration tests.

### Future enhancements

- Ability to fetch a list of Gravity Forms by their IDs.
- Ability to create an individual Gravity Form.
- Ability to update an individual Gravity Form.
- Ability to delete an individual Gravity Form.
- Ability to fetch an individual field
