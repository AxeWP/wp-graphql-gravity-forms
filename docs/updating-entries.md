# Updating Entries & Draft Entries.

## Update an Entry

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

## Updating a Draft Entry

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
