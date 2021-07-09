# Submit a form by building a draft entry incremmentally.

While it is recommended to [use the `submitGravityFormsForm` mutation for submissions](submitting-forms.md), another way to submit forms is to incrementally build a draft entry. **This process may be deprecated and removed in future versions of the plugin**.

The submission process works like this:

1. Send a `createGravityFormsDraftEntry` mutation to create a new draft form entry. This gives you back the `resumeToken`, which is the unique identifier for the draft entry that you need to pass in to all the mutations below.
2. Send as many "update" mutations (such as `updateDraftEntryTextFieldValue`, `updateDraftEntrySelectFieldValue`, etc.) as you need to update the values of the draft entry.
3. When ready, send a `submitGravityFormsDraftEntry` that turns the draft entry into a permanent entry.

For large forms, #2 on the list above could potentially result in the need to send a large number of "update" mutations to the backend to update form entry field values. Using something like Apollo Client's [apollo-link-batch-http](https://www.apollographql.com/docs/link/links/batch-http/) is recommended so that your app will be able to send a large number of mutations to the backend all within a single HTTP request to update the draft entry.

## Example Mutations

### Create a New Draft Entry

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

### Update an individual Draft Entry value.

The example below shows `updateDraftEntryTextFieldValue`, which can be used for updating the value of a text field. Similar mutations exist for other field types, such as `updateDraftEntrySelectFieldValue`, `updateDraftEntryAddressFieldValue`, etc. The `value` shape is the same as used [in the `fieldValues` input of `submitGravityFormsForm`](submitting-forms.md).

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
        nodes {
          ... on TextField {
            value
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

### Submit a Draft Entry

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
      formFields(first: 300) {
        nodes {
          ... on TextField {
            value
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
