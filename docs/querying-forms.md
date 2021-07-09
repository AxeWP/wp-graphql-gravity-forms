# Querying Forms

## Get a Single Form

[Gravity Forms form objects]((https://docs.gravityforms.com/form-object/)) can be queried with `gravityFormsForm`. The example query below shows how you can get a Form and [its associated `formFields`](querying-formfields.md)).

The `id` input accepts either the Gravity Forms form ID (`idType: DATABASE_ID`) or a [global ID](using-global-ids.md) (`idType: ID`).

### Example Query

```graphql
{
  gravityFormsForm(id: 50, idType: DATABASE_ID) {
    formId
    cssClass
    dateCreated
    formFields {
      nodes {
        id
        type
        ... on TextField {
          label
          description
        }
      }
    }
    lastPageButton {
      text
      type
    }
    title
  }
}
```

## Get a List of Forms.

The code comments in the example query below shows how you can fetch data for multiple forms at once.

[Cursor-based pagination](https://www.wpgraphql.com/docs/connections/#solution-for-pagination-naming-conventions-and-contextual-data) is supported. You can use the `first`, `last`, `before` and `after` fields, along with the data inside of `pageInfo` and the cursors returned by the API to get each page of forms data.

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
