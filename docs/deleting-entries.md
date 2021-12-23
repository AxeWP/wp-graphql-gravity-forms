# Delete an Entry or Draft Entry

## Delete an Entry

You can use the `deleteGfEntry` mutation to delete a [Gravity Forms entry](https://docs.gravityforms.com/entry-object/). The `entryId` of the deleted entry will be in the response.

### Example Mutation

```graphql
mutation {
  deleteGfEntry(input: { entryId: 5 }) {
    entryId
  }
}
```

## Delete a Draft Entry

Similarly, you can use `deleteGfDraftEntry` to delete a [Gravity Forms draft entry](https://docs.gravityforms.com/database-storage-structure-reference/#wp-gf-draft-submissions). The `resumeToken` of the deleted draft entry will be in the response.

### Example Mutation

```graphql
mutation {
  deleteGfDraftEntry(
    input: { resumeToken: "524d5f3a30c845b29a8db35c9f2aaf29" }
  ) {
    resumeToken
  }
}
```
