# 🚀📄 WPGraphQL for Gravity Forms

WordPress plugin that provides a GraphQL API for interacting with Gravity Forms. It is currently an unfinished work in progress that is being actively developed.

## What can it do?

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this, and WordPress even ships with native REST API support. The REST architecture has its limitations, though. You often end up making several, synchronous requests to several different REST endpoints just to get the data that the current page requires. Your frontend app often has to fire off a few REST requests, wait until they resolve, use the data in their responeses to fire off additional REST requests, wait for those to resolve, then finally use the data to render the page. All those synchronous round trips back to the server take time and can make a frontend app feel slow. With REST, you also have no control over how much or how little of the data is sent across the wire, on top of several other shortcomings. For a while, that was the state of things for decouple apps.

Then, suddenly...a new hero emerged: GraphQL! Facebook's Engineering team was feeling the pain of working with REST APIs and open sourced the GraphQL specification in 2015. It aims to solve the issues mentioned above. Using GraphQL means that if your frontend app needs to fetch data for a number of different resources (such as data about the current user, the menu items to put in the nav bar, the current page content, and data for the last 5 blog posts), all of that data can be fetched from the server with a single request 😲. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

If you work on the WordPress platform, you’ll be happy to learn that a GraphQL implementation exists for WordPress – the [WPGraphQL](https://github.com/wp-graphql/wp-graphql) plugin! The project was started by [Jason Bahl](https://twitter.com/jasonbahl) and is being actively being developed by him and a number of other [contributors](https://github.com/wp-graphql/wp-graphql/graphs/contributors). The plugin takes two existing PHP libraries for GraphQL ([graphql-php](https://github.com/webonyx/graphql-php) & [graphql-relay-php](https://github.com/ivome/graphql-relay-php/)), and layers WordPress-specific functionality on top of them, so that it’s possible to run queries for blog posts, pages, taxonomies, settings, users, and many other WordPress-y things (these are referred to as “types” in GraphQL parlance).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to also interact with the Gravity Forms data stored in a headless WordPress backend. Using the plugin, your frontend app can send requests to the `/graphql` endpoint to get, create, update and delete Gravity Forms forms, entries, fields, and other data. The plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

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
- Ability to delete multiple Gravity Forms by their IDs.
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
