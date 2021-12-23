# Updating Entries & Draft Entries.

## Update an Entry

You can update a [Gravity Forms entry](https://docs.gravityforms.com/entry-object/) with the `updateGfEntry` mutation. This mutation works similarly to [`submitGfForm`](submitting-forms.md).

### Example Mutation

```graphql
{
  updateGfEntry(
    input: {
      entryId: 1
      isRead: false # Optional. Used to mark the entry as read.
      isStarred: false # Optional. Used to mark the entry as 'starred'.
      status: ACTIVE # Optional. Can be used to mark an entry as trash or spam.
      fieldValues: [
        {
          # See the above section on using `submitGfForm`
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

## Update a Draft Entry

Updating a [Gravity Forms draft entry](https://docs.gravityforms.com/database-storage-structure-reference/#wp-gf-draft-submissions) using the `updateGfDraftEntry` mutation follows a similar pattern to [updating an entry](#update-an-entry) :

### Example Mutation

```graphql
{
  updateGfEntry(
    input: {
      resumeToken: "f82a5d986f4d4f199893f751adee98e9"
      fieldValues: [
        {
          # See the above section on using `submitGfForm`
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
