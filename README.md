![alt text](./assets/icon-128x128.png "WPGraphQL for Gravity Forms Logo")
# WPGraphQL for Gravity Forms

A WordPress plugin that adds <a href="https://wpgraphql.com" target="_blank">WPGraphQL</a> support for <a href="https://gravityforms.com" target="_blank">Gravity Forms</a>.

* [Join the WPGraphQL community on Discord.](https://discord.gg/Hp6fQbqvwe)
* [Documentation](#documentation)

-----

![Packagist License](https://img.shields.io/packagist/l/harness-software/wp-graphql-gravity-forms?color=green) ![Packagist Version](https://img.shields.io/packagist/v/harness-software/wp-graphql-gravity-forms?label=stable) ![GitHub commits since latest release (by SemVer)](https://img.shields.io/github/commits-since/axewp/wp-graphql-gravity-forms/v0.13.4) ![GitHub forks](https://img.shields.io/github/forks/axewp/wp-graphql-gravity-forms?style=social) ![GitHub Repo stars](https://img.shields.io/github/stars/axewp/wp-graphql-gravity-forms?style=social)<br />
[![Coverage Status](https://codecov.io/gh/AxeWP/wp-graphql-gravity-forms/graph/badge.svg?token=VIYRD2ZSYR)](https://codecov.io/gh/AxeWP/wp-graphql-gravity-forms) [![WordPress Coding Standards](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/phpcs.yml/badge.svg)](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/phpcs.yml) [![Code Quality](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/phpstan.yml/badge.svg)](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/phpstan.yml) [![Schema Linter](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/schema-linter.yml/badge.svg)](https://github.com/axewp/wp-graphql-gravity-forms/actions/workflows/schema-linter.yml)

## Overview

The WPGraphQL for Gravity Forms plugin is a powerful extension for [WPGraphQL](https://www.wpgraphql.com/) that provides a comprehensive suite of features that allows developers to interact with [Gravity Forms](https://www.gravityforms.com/) via GraphQL.

This plugin enhances the developer experience by offering a GraphQL schema tailored for Gravity Forms. The schema provides improved type safety, prevents over-fetching, and makes it easier to interact with your forms and entries than (and even provides functionality not available in) Gravity Form's traditional PHP and REST APIs.

WPGraphQL for Gravity Forms is an essential tool for those leveraging decoupled and headless WordPress architectures. Whether you're using WordPress as a data source for your headless application, integrating Gravity Forms data into an external service, or building custom, interactive form experiences, this plugin offers improved developer experience and features to make your frontend code more robust. It's designed to be a versatile tool, capable of handling any decoupled project from simple form submissions to complex, form-driven applications scalable at an enterprise level.

## System Requirements

* PHP: 8.2-8.5+
* WordPress: 6.7+
* WPGraphQL: 2.5.0+
* Gravity Forms: 2.9+
* **Recommended**: [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload) - used for [File Upload and Post Image submissions](docs/submitting-forms.md).

## Quick Install

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/).
2. Install & activate [Gravity Forms](https://www.gravityforms.com/) and any supported addons.
3. Download the `wp-graphql-gravity-forms.zip` file from the [latest release](https://github.com/AxeWP/wp-graphql-gravity-forms/releases/latest) upload it to your WordPress install, and activate the plugin.

> [!IMPORTANT]
>
> Make sure you are downloading the [`wp-graphql-gravity-forms.zip`](https://github.com/axewp/wp-graphql-gravity-forms/releases/latest/download/wp-graphql-gravity-forms.zip) file from the releases page, not the `Source code (zip)` file nor a clone of the repository.
>
> If you wish to use the source code, you will need to run `composer install` inside the plugin folder to install the required dependencies.

### With Composer

```bash
composer require harness-software/wp-graphql-gravity-forms
```

## Updating and Versioning

As we work [towards a 1.0 Release](https://github.com/axewp/wp-graphql-gravity-forms/issues/179), we will need to introduce numerous breaking changes. We will do our best to group multiple breaking changes together in a single release, to make it easier on developers to keep their projects up-to-date.

Until we hit v1.0, we're using *a modified version* of [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

v0.x: "Major" releases. These releases introduce new features, and may contain breaking changes to either the PHP API or the GraphQL schema.
v0.x.y: "Minor" releases. These releases introduce new features and enhancements and address bugs. They do not contain breaking changes.
v0.x.y.z: "Patch" releases. These releases are reserved for addressing issue with the previous release only.

## Development and Support

Development of WPGraphQL for Gravity Forms is provided by [AxePress Development](https://axepress.dev). Community contributions are _welcome_ and **encouraged**.

Basic support is provided for free, both in [this repo](https://github.com/axewp/wp-graphql-gravity-forms/issues) and in [WPGraphQL's official Discord](https://discord.gg/Hp6fQbqvwe).

Priority support and custom development are available to [our Sponsors](https://github.com/sponsors/AxeWP).

<a href="https://github.com/sponsors/AxeWP" alt="GitHub Sponsors"><img src="https://img.shields.io/static/v1?label=Sponsor%20Us%20%40%20AxeWP&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86&style=for-the-badge" /></a>

## Supported Features

* Querying forms and entries.
* Submitting forms.
* Updating entries and draft entries.
* Deleting entries and draft entries.
* Triggering builds with [WPGatsby](https://wordpress.org/plugins/wp-gatsby/) and [Jamstack Deployments](https://wordpress.org/plugins/wp-jamstack-deployments/)

## Future Feature Enhancements

[_View all Feature Requests_](https://github.com/axewp/wp-graphql-gravity-forms/issues?q=is%3Aopen+is%3Aissue+label%3A%22type%3A+enhancement+%E2%9A%A1%22%2C%22type%3A+feature+%F0%9F%A6%8B%22%2C%22type%3A+idea+%F0%9F%92%A1%22)

* Add support for [remaining form fields](https://github.com/axewp/wp-graphql-gravity-forms/issues/195)
* Ability to [query for lists of draft entries](https://github.com/axewp/wp-graphql-gravity-forms/issues/114).
* Ability to [create, update, and delete an individual Gravity Form](https://github.com/axewp/wp-graphql-gravity-forms/issues/115).

## Documentation

* [Supported Gravity Forms form fields](docs/form-field-support.md)
* [Using Global IDs vs Database IDs](docs/using-global-ids.md)
* [Querying forms](docs/querying-forms.md)
* [Querying entries & draft entries](docs/querying-entries.md)
* [Querying `formFields` and their values](docs/querying-formfields.md)
* [Submitting forms](docs/submitting-forms.md)
* [Updating entries & draft entries](docs/updating-entries.md)
* [Deleting entries & draft entries](docs/deleting-entries.md)
* [Internationalization & Localization](docs/i18n.md)
* [Actions & Filters](docs/actions-and-filters.md)

### Recipes

* [Register a Gravity Forms Form to a custom GraphQL field](docs/recipes/register-form-to-custom-field.md)
* [Register a custom GraphQL connection to Gravity Forms entries](docs/recipes/register-custom-entries-connection.md)
* [Register a custom Gravity Forms field to the GraphQL schema](docs/recipes/register-custom-form-field.md)
* [Add GraphQL mutation support for a custom Gravity Forms field](docs/recipes/register-custom-field-value-inputs.md)

## Sponsors
<div class="sponsor-grid">
  <a href="https://mysafetyhq.com/" target="_blank" rel="sponsored" title="SafetyHQ (previously Harness Software)"><img src="https://avatars.githubusercontent.com/u/50597878?s=150&v=4" alt="SafetyHQ (previously Harness Software)"></a>
</div>

<a href="https://github.com/sponsors/AxeWP" alt="GitHub Sponsors"><img src="https://img.shields.io/static/v1?label=Sponsor%20Us%20%40%20AxeWP&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86&style=for-the-badge" /></a>
