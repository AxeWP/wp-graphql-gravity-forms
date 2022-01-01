# Updating Entries & Draft Entries.

## Update an Entry

You can update a [Gravity Forms entry](https://docs.gravityforms.com/entry-object/) with the `updateGfEntry` mutation. This mutation works similarly to [ `submitGfForm` ](submitting-forms.md).

### Example Mutation

```graphql
{
  updateGfEntry(
    input: {
      id: 1 # Either a DatabaseId or global Id.
      entryMeta: {
        isRead: false # Used to mark the entry as read.
        isStarred: false # Used to mark the entry as 'starred'.
        status: ACTIVE # Can be used to mark an entry as trash or spam.
      }
      fieldValues: [
        {
          # See the above section on using `submitGfForm`
          id: 1
          value: "This is a text field value."
        }
      ]
      shouldValidate: true # Whether to validate the form field values.
    }
  ) {
    errors {
      id # The field that failed validation.
      message
    }
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
  updateGfDraftEntry(
    input: {
      id: "f82a5d986f4d4f199893f751adee98e9"
      idType: 'RESUME_TOKEN',
      entryMeta: {
        dateCreatedGmt: 2021-12-31 23:59:59
      }
      fieldValues: [
        {
          # See the above section on using `submitGfForm`
          id: 1
          value: "This is a text field value."
        }
      ]
    }
  ) {
    draftEntry {
      # See docs querying Entries.
      resumeToken
    }
  }
}
```
