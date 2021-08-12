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

The code comments in the example query below shows how you can fetch and filter data for multiple forms at once.

[Cursor-based pagination](https://www.wpgraphql.com/docs/connections/#solution-for-pagination-naming-conventions-and-contextual-data) is supported. You can use the `first`, `last`, `before` and `after` fields, along with the data inside of `pageInfo` and the cursors returned by the API to get each page of forms data.

#### Example Query

```graphql
{
  gravityFormsForms(
    first: 10
    after: "YXJyYXljb25uZWN0aW9uOjM=" # Or pass null to start from the beginning.
    where: { 
			# List of all the form IDs to include.
      formIds: [1]
			# Sort fields in ascending order by "title
			sort: { direction: ASC, key: "title }
			# Show only active forms.
			status: ACTIVE 
			}
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
