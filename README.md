![alt text](./assets/icon-128x128.png "WPGraphQL for Gravity Forms Logo")
# WPGraphQL for Gravity Forms

A WordPress plugin that provides a GraphQL API for interacting with Gravity Forms.


![Packagist License](https://img.shields.io/packagist/l/harness-software/wp-graphql-gravity-forms?color=green) ![Packagist Version](https://img.shields.io/packagist/v/harness-software/wp-graphql-gravity-forms?label=stable) ![GitHub commits since latest release (by SemVer)](https://img.shields.io/github/commits-since/harness-software/wp-graphql-gravity-forms/v0.12.2) ![GitHub forks](https://img.shields.io/github/forks/harness-software/wp-graphql-gravity-forms?style=social) ![GitHub Repo stars](https://img.shields.io/github/stars/harness-software/wp-graphql-gravity-forms?style=social)<br />
[![Coverage Status](https://coveralls.io/repos/github/harness-software/wp-graphql-gravity-forms/badge.svg?branch=develop)](https://coveralls.io/github/harness-software/wp-graphql-gravity-forms?branch=develop) [![WordPress Coding Standards](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/code-standard.yml/badge.svg)](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/code-standard.yml) [![Code Quality](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/code-quality.yml/badge.svg)](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/code-quality.yml) [![Schema Linter](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/schema-linter.yml/badge.svg)](https://github.com/harness-software/wp-graphql-gravity-forms/actions/workflows/schema-linter.yml) 

* [Join the WPGraphQL community on Slack.](https://join.slack.com/t/wp-graphql/shared_invite/zt-3vloo60z-PpJV2PFIwEathWDOxCTTLA)
* [Documentation](#documentation)

## Overview

Using WordPress as a headless CMS with a separate JavaScript-powered frontend single-page app is an increasingly popular tech stack. Traditionally, REST APIs have been used for the purpose of sending data back & forth between the frontend and backend in setups like this but the REST architecture has its limitations.

Using GraphQL means that if your frontend app needs to fetch data for a number of different resources, all of that data can be fetched from the server with a single request. Your frontend app can even define which fields it requires for each of the resources, giving it full control over which pieces of data are fetched and included in the response.

Fortunately, a GraphQL implementation exists for WordPress - [WPGraphQL](https://www.wpgraphql.com/).

WPGraphQL for Gravity Forms extends the WPGraphQL plugin, allowing frontend apps to interact with the Gravity Forms data stored in a headless WordPress backend. This plugin couples the great forms functionality of Gravity Forms with the powerful WordPress-specific GraphQL implementation that WPGraphQL provides.

Our hope for this open source project is that it will enable more teams to leverage GraphQL for building fast, interactive frontend apps that source their data from WordPress and Gravity Forms.

## System Requirements

* PHP 7.4+ || 8.0
* WordPress 5.4.1+
* WPGraphQL 1.9.0+
* Gravity Forms 2.5+ (Recommend: v2.6+)
* **Recommended**: [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload) - used for [File Upload and Post Image submissions](docs/submitting-forms.md).

## Quick Install

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/).
2. Install & activate [Gravity Forms](https://www.gravityforms.com/) and any supported addons.
3. Download the [latest release](https://github.com/harness-software/wp-graphql-gravity-forms/releases) `.zip` file, upload it to your WordPress install, and activate the plugin.

### With Composer

```console
composer require harness-software/wp-graphql-gravity-forms
```


## Updating and Versioning
As we work [towards a 1.0 Release](https://github.com/harness-software/wp-graphql-gravity-forms/issues/179), we will need to introduce numerous breaking changes. We will do our best to group multiple breaking changes together in a single release, to make it easier on developers to keep their projects up-to-date.

Until we hit v1.0, we're using *a modified version* of [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

v0.x: "Major" releases. These releases introduce new features, and may contain breaking changes to either the PHP API or the GraphQL schema.
v0.x.y: "Minor" releases. These releases introduce new features and enhancements and address bugs. They do not contain breaking changes.
v0.x.y.z: "Patch" releases. These releases are reserved for addressing issue with the previous release only.

## Supported Features

* Querying forms and entries.
* Submitting forms.
* Updating entries and draft entries.
* Deleting entries and draft entries.
* Triggering builds with [WPGatsby](https://wordpress.org/plugins/wp-gatsby/) and [Jamstack Deployments](https://wordpress.org/plugins/wp-jamstack-deployments/)

## Future Feature Enhancements

[_View all Feature Requests_](https://github.com/harness-software/wp-graphql-gravity-forms/issues?q=is%3Aopen+is%3Aissue+label%3A%22type%3A+enhancement+%E2%9A%A1%22%2C%22type%3A+feature+%F0%9F%A6%8B%22%2C%22type%3A+idea+%F0%9F%92%A1%22)

* Add support for [remaining form fields](https://github.com/harness-software/wp-graphql-gravity-forms/issues/195)
* Ability to [query for lists of draft entries](https://github.com/harness-software/wp-graphql-gravity-forms/issues/114).
* Ability to [create, update, and delete an individual Gravity Form](https://github.com/harness-software/wp-graphql-gravity-forms/issues/115).

## Documentation

* [Supported Gravity Forms form fields](docs/form-field-support.md)
* [Using Global IDs vs Database IDs](docs/using-global-ids.md)
* [Querying forms](docs/querying-forms.md)
* [Querying entries & draft entries](docs/querying-entries.md)
* [Querying `formFields` and their values](docs/querying-formfields.md)
* [Submitting forms](docs/submitting-forms.md)
* [Updating entries & draft entries](docs/updating-entries.md)
* [Deleting entries & draft entries](docs/deleting-entries.md)
* [Actions & Filters](docs/actions-and-filters.md)

### Recipes

* [Register a Gravity Forms Form to a custom GraphQL field](docs/recipes/register-form-to-custom-field.md)
* [Register a custom GraphQL connection to Gravity Forms entries](docs/recipes/register-custom-entries-connection.md)
* [Register a custom Gravity Forms field to the GraphQL schema](docs/recipes/register-custom-form-field.md)
* [Add GraphQL mutation support for a custom Gravity Forms field](docs/recipes/register-custom-field-value-inputs.md) 
