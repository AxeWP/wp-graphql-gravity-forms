# 🚀📄 WPGraphQL for Gravity Forms

[![Project Status: Active.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)
![Packagist License](https://img.shields.io/packagist/l/harness-software/wp-graphql-gravity-forms?color=green)
![Packagist Version](https://img.shields.io/packagist/v/harness-software/wp-graphql-gravity-forms?label=stable)
![GitHub commits since latest release (by SemVer)](https://img.shields.io/github/commits-since/harness-software/wp-graphql-gravity-forms/0.7.3)
![GitHub forks](https://img.shields.io/github/forks/harness-software/wp-graphql-gravity-forms?style=social)
![GitHub Repo stars](https://img.shields.io/github/stars/harness-software/wp-graphql-gravity-forms?style=social)

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms.

- [Join the WPGraphQL community on Slack.](https://join.slack.com/t/wp-graphql/shared_invite/zt-3vloo60z-PpJV2PFIwEathWDOxCTTLA)
- [Documentation](#documentation)

## Overview

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations.

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

Fortunately, a GraphQL implementation exists for WordPress - [WPGraphQL](https://www.wpgraphql.com/).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to interact with the Gravity Forms data stored in a headless WordPress backend. This plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms.

## System Requirements

- PHP 7.4+
- WPGraphQL 1.0.0+
- Gravity Forms 2.4+
- WordPress 5.4.1+

## Quick Install

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/).
2. Install & activate [Gravity Forms](https://www.gravityforms.com/) and any supported addons.
3. Download the zip of this repository and upload it to your WordPress install, and activate the plugin.

## Supported Features

- Querying forms and entries.
- Submitting forms.
- Updating entries and draft entries.
- Deleting entries and draft entries.

## Future Feature Enhancements

- Add support for missing form fields [Github Issue](https://github.com/harness-software/wp-graphql-gravity-forms/issues/119)
- Add support for backwards pagination of lists of entries.
- Ability to query for lists of draft entries, or both entries and draft entries
- Ability to get the total count for a list of entries.
- Ability to create, update, and delete an individual Gravity Form.
- Create & update integration tests. [Github Issue](https://github.com/harness-software/wp-graphql-gravity-forms/issues/116).

## Documentation

- [Using Global IDs vs Database IDs](docs/using-global-ids.md)
- [Querying forms](docs/querying-forms.md)
- [Querying entries & draft entries ](docs/querying-entries.md)
- [Querying `formFields` and their values](docs/querying-formfields.md)
- [Submitting forms](docs/submitting-forms.md)
- [Updating entries & draft entries](docs/updating-entries.md)
- [Deleting entries & draft entries](docs/deleting=-entries.md)
- [Actions & Filters](docs/actions-and-filters.md)
