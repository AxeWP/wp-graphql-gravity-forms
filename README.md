# 🚀📄 WPGraphQL for Gravity Forms

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms. It is currently an unfinished work in progress that is being actively developed.

## What can it do?

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request 😲. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

A GraphQL implementation exists for WordPress – the [WPGraphQL](https://github.com/wp-graphql/wp-graphql) plugin! The project was started by [Jason Bahl](https://twitter.com/jasonbahl) and is being actively being developed by him and a number of other [contributors](https://github.com/wp-graphql/wp-graphql/graphs/contributors). 

WPGraphQL for Gravity Forms will extend the WPGraphQL plugin, allowing frontend apps to also interact with the Gravity Forms data stored in a headless WordPress backend. Using the plugin, your frontend app can send requests to the `/graphql` endpoint to:
- get a list of forms
- create, update and delete forms
- retrieve, create, and update entries
- Retrieve fields from within a form & their attributes 

This plugin will couple the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms. 🙌🏼

## Getting Started

1. Use [Composer](https://getcomposer.org/) to require the plugin as a dependency of your project. Alternatively, you can download it into your `plugins` directory, just like any other WordPress plugin.
1. Activate the plugin, along with the [WPGraphQL](https://www.wpgraphql.com/) and [Gravity Forms](https://www.gravityforms.com/) plugins that it depends on.
1. Use a tool like [GraphiQL](https://electronjs.org/apps/graphiql) to view the schema and send a few test requests to your `/graphql` endpoint to interact with Gravity Forms data, and start sending requests from your frontend app.

## Project Roadmap

Phase 1:
- Ability to fetch an individual Gravity Form by its ID, including all of its fields.
- Ability to fetch an individual Gravity Form entry by its ID, including all of its field values.
- Ability to fetch a list of entries for a particular form. Limiting results to a date range, and using the other filtering options that Gravity Forms provides will be supported.
- Add support for pagination of lists of entries.
- Ability to get the total count for a list of entries.
- Write tests for phase 1 features.

Phase 2:
- Ability to create an individual Gravity Forms entry.
- Support GF's "Save and Continue Later" feature for partially completed form entries.
- Write tests for phase 2 features.

Phase 3:
- Ability to update an individual Gravity Forms entry.
- Write tests for phase 3 features.

Phase 4:
- Ability to delete an individual Gravity Forms entry by its ID.
- Ability to delete multiple Gravity Forms entries by their IDs.
- Write tests for phase 4 features.

Future enhancements:
- Ability to fetch a list of Gravity Forms by their IDs.
- Ability to fetch an individual field
- Ability to fetch a list of fields
- Ability to create an individual Gravity Form.
- Ability to create multiple Gravity Forms.
- Ability to create multiple Gravity Forms entries all at once.
- Ability to update an individual Gravity Form.
- Ability to update multiple Gravity Forms by their IDs.
- Ability to delete an individual Gravity Form by its ID.
