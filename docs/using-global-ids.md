# Global IDs vs Database IDs

The `id` input for Form and Entry queries accepts either the Gravity Forms ID ( `idType: DATABASE_ID` ) assigned to the WordPress database, or a global (base-64 encoded) ID ( `idType: ID` ).

To generate global ID for an object, you encode the name of the WPGraphQL Data Loader, followed by the database ID. This can be done in JavaScript using the `btoa()` function like this, where `gf_form` is the GraphQL type and `1` is the form ID:

```js
const globalId = btoa(`gf_form:1`); // Results in "Z2ZfZm9ybTox"
```

You can also retrieve the global ID by returning the `id` field on the object.

The example query below shows how you can use a Global ID as your input, and how you can include the global ID in the query's response:

```graphql
query gfForm{
  gfForm(id: "Z2ZfZm9ybTox", idType: ID) {
    databaseId
    id # This is the (string) global `ID`.
    dateCreated
    isActive
    isTrash
  }
}
```
