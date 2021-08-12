# Global IDs vs Database IDs

The `id` input for Form and Entry queries accepts either the Gravity Forms ID ( `idType: DATABASE_ID` ) assigned to the WordPress database, or a global (base-64 encoded) ID ( `idType: ID` ).

To generate global ID for an object, you encode the name of the GraphQL type, followed by the database ID. This can be done in JavaScript using the `btoa()` function like this, where `GravityFormsForm` is the GraphQL type and `1` is the form ID:

```js
const globalId = btoa(`GravityFormsForm:1`); // Results in "R3Jhdml0eUZvcm1zRm9ybTox"
```

You can also retrieve the global ID by returning the `id` field on the object.

The example query below shows how you can use a Global ID as your input, and how you can include the global ID in the query's response:

```graphql
query GravityFormsForm{
  gravityFormsForm(id: "R3Jhdml0eUZvcm1zRm9ybTox", idType: ID) {
    formId # This is the (int) `DATABASE_ID`.
    id # This is the (string) global `ID`.
    dateCreated
    isActive
    isTrash
  }
}
```
