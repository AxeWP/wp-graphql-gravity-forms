# Querying Entries & Draft Entries

## Get a single entry

[Gravity Forms entry objects](https://docs.gravityforms.com/entry-object/) can be queried with `gravityFormsEntry`. The example query below shows how you can get a single entry by ID, and [data about the fields and their values](querying-formfields.md).

The `id` input accepts either the Gravity Forms Entry ID (`idType: DATABASE_ID`), or a [global ID](using-global-ids.md) (`idType: ID`). The `id` input can also accept the `resumeToken` for a draft entry when `idType` is set to `ID`.

### Example Query

```graphql
{
  gravityFormsEntry(id: 2977, idType: DATABASE_ID) {
    id # global ID
    entryId # database ID
    formId
    isDraft
    resumeToken
    formFields(first: 300) {
      nodes {
        id
        type
        ... on TextField {
          label
          value # The field value
        }
        ... on CheckboxField {
          label
          choices {
            isSelected
          }
          checkboxValues {
            # The field value
            inputId
            value
          }
        }
        ... on AddressField {
          label
          inputs {
            key
            isHidden
          }
          addressValues {
            # The field value
            street
            lineTwo
            city
            state
            zip
            country
          }
        }
      }
    }
  }
}
```

## Get a Single Draft Entry

The `gravityFormsEntry` query supports both entries and [draft entries](https://docs.gravityforms.com/database-storage-structure-reference/#wp-gf-draft-submissions). See ["Get a Single Entry"](#get-a-single-entry) above.

To query a Draft Entry, simply pass the `resumeToken` to the input `id` field, and set `idType` to ID.

### Example Query

```graphql
{
  gravityFormsEntry(id: "f82a5d986f4d4f199893f751adee98e9", idType: ID) {
    # The fields you want to return.
  }
}
```

## Get a List of Entries

The code comments in the example query below explain how you can get a filtered list of entries.

The plugin supports `first, after` and `last, before` cursor-based [pagination](https://www.wpgraphql.com/docs/connections/#solution-for-pagination-naming-conventions-and-contextual-data), but does not support `first, before` or `last, after` pagination. It also does not yet support querying for a list of draft entries.

By default, WPGraphQL sets the maximum query amount to 100. This can be overwritten using the [`graphql_connection_max_query_amount` filter](https://www.wpgraphql.com/filters/graphql_connection_max_query_amount/).

```graphql
{
  gravityFormsEntries(
    first: 20
    after: "eyJvZmZzZXQiOjAsImluZGV4Ijo0fQ==" # Or pass null to start from the beginning.
    where: {
      # List of all the form IDs to include.
      formIds: [1]
      # Find entries between this start & end date.
      dateFilters: {
        startDate: "2019-09-22 02:26:23"
        endDate: "2019-10-25 02:26:23"
      }
      fieldFiltersMode: ALL
      fieldFilters: [
        # Return specific list of entries.
        { key: "id", intValues: [5, 27, 350] }
        # Find entries created by user ID 1.
        { key: "created_by", intValues: [1], operator: IN }
        # Find entries where field 5 has a value of "somevalue".
        { key: "5", stringValues: [ "somevalue" ], operator: IN }
      ]
      # Sort fields in ascending order by "date_created"
      sort: { direction: ASC, isNumeric: false, key: "date_created" }
      # Show only active entries.
      status: ACTIVE
    }
  ) {
    pageInfo {
      startCursor
      endCursor
      hasPreviousPage
      hasNextPage
    }
    nodes {
      entryId
      formId
      isDraft
      status
      dateCreated
      createdBy {
        node {
          userId
          name
        }
      }
      formFields(first: 300) {
        nodes {
          ... on TextField {
            type
            value
          }
        }
      }
    }
  }
}
```

## Get a List of Draft Entries

This is currently not supported by the plugin.
