# Querying Forms

## Get a Single Form

[Gravity Forms form objects]((https://docs.gravityforms.com/form-object/)) can be queried with `gfForm`. The example query below shows how you can get a Form and [its associated `formFields`](querying-formfields.md)).

The `id` input accepts either the Gravity Forms form ID (`idType: DATABASE_ID`) or a [global ID](using-global-ids.md) (`idType: ID`).

### Example Query

```graphql
{
  gfForm(id: 50, idType: DATABASE_ID) {
    cssClass
    databaseId
    dateCreated
    formFields {
      nodes {
        databaseId
        type
        ... on TextField {
          label
          description
        }
      }
    }
    pagination{
      lastPageButton {
        text
        type
      }
    }
    title
  }
}
```

## Get a List of Forms

The code comments in the example query below shows how you can fetch and filter data for multiple forms at once.

[Cursor-based pagination](https://www.wpgraphql.com/docs/connections/#solution-for-pagination-naming-conventions-and-contextual-data) is supported. You can use the `first`, `last`, `before` and `after` fields, along with the data inside of `pageInfo` and the cursors returned by the API to get each page of forms data.

### Example Query

```graphql
{
  gfForms(
    first: 10
    after: "YXJyYXljb25uZWN0aW9uOjM=" # Or pass null to start from the beginning.
    where: { 
      # List of all the form IDs to include.
      formIds: [1]
      # Sort fields in ascending order by title
      orderby: { order: ASC, field: "title" }
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
        databaseId
        title
        formFields(first: 300) {
          nodes {
            type
            databaseId
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
## Get an embedded Form from the Gravity Forms Block.

Gravity Forms can be embedded in a post or page using the [Gravity Forms block](https://docs.gravityforms.com/adding-a-form-using-block/).

When coupled with [WPGraphQL Content Blocks](https://github.com/wpengine/wp-graphql-content-blocks), you can query the embedded form directly from the parsed block content, using the `GravityformsForm.attributes.form` field.

> [!IMPORTANT]
> To query the `GfForm` object from the block content, you must have the `WPGraphQL Content Blocks` plugin version v4.0+ installed and activated.

### Example Query

```graphql
{
  post(id: $post_id, idType: DATABASE_ID) {
    databaseId
    editorBlocks { # Added by WPGraphQL Content Blocks
      name
      ... on GravityformsForm {
        attributes {
          form { # The GfForm object.
            databaseId
            formFields {
              nodes {
                databaseId
                type
                ... on TextField {
                  label
                  description
                }
              }
            }
            title
            # other GraphQL fields. 
          }
        }
      }
    }
  }
}
```
