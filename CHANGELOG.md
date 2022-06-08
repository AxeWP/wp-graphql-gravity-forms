# Changelog

## v0.9.2.2 - Hotfix
This hotfix release fixes an issue where the `rangeMin` and `rangeMax` fields on `NumberField` would not always return the correct float value.

- fix: correctly return float values for `rangeMin` and `rangeMax` properties.

## v0.9.2.1 - Hotfix
This hotfix release fixes an issue where `EntryUser` would throw an error if the WordPress user who submitted the entry no longer exists. The field now resolves to `null` instead.

- fix: resolve missing `EntryUser` to `[]` instead of throwing UserError

## v0.9.2 - Bugfix and Test
This minor release addresses an issue where `PostImageInput` would be registered to the schema even if WPGraphQL Upload wasn't enabled, breaking gqty and Gatsby schema generation. We also fixed a few other bugs and some overlooked items in the docs, and added some more WPUnit tests.

- chore: define WPGraphQL types with `Int` instead of `Integer` for code consistency.
- docs: Add `TimeField` to list of formFields that take a value input.
- docs: add expected object values for `FileUploadValues`.
- fix: add missing `allowsPrepopulate` property to `PostContentField`.
- fix: `Utils::maybe_decode_json()` support for multidimensional arrays.
- fix: Check for WPGraphQL Upload before registering `PostImageInput` to the schema.
- tests: add WPUnit tests for `ListField`, `MultiSelectField`, `NameField`, `TimeField`,`PostContentField`, `PostTitleField`, `PostExcerptField`, `GFUtils::get_forms()`, `Utils::maybe_decode_json()`, and `WPGraphQLGravityForms::instances()`
- tests: remove tests for `{fieldType}FieldValue` edges since they're deprecated anyway.

**Note** As part of the road to v1.0, [the next release will](https://github.com/harness-software/wp-graphql-gravity-forms/issues?q=is%3Aopen+is%3Aissue+milestone%3Av0.10) contain numerous breaking changes to the codebase AND schema, including the removal of deprecated code (such as FieldValue edges). Please prepare accordingly.

## v0.9.1 - Gravity Forms Quiz Support
This minor release adds support for Gravity Forms Quiz fields.

- feat: Add support for GF Quiz fields.
- fix: Fixed the type descriptions for `NoDuplicatesProperty` and `RadioChoiceProperty`.
- dev: Use `GF_Field::get_input_type()` when choosing how to handle input values. This will allow for better support of composite type fields in the future.
- docs: Update language regarding `UpdateDraftEntryFieldValue`'s upcoming deprecation.
- docs: Fix link to Deleting Entries doc.
- tests: Add WPUnit tests for `EmailField`.

## v0.9.0 - Conditional Logic Support on Confirmations

This minor release adds the `conditionalLogic` GraphQL field to `gravityFormsForm.confirmations`. We also squashed a few bugs and implemented some more WPUnit tests.

*Note*: This release technically contains breaking changes for developers making use of `DataManipulator` class methods in their own code.

- fix: consistently apply Gravity Forms filters and WPGraphQL error checking to `GFUtils::get_forms()`.
- feat: add `conditionalLogic` GraphQL field to `gravityFormsForm.confirmations`.
- dev!: makes `DataManipulator` methods static. If you are using any DataManipulator methods in your custom code, please update accordingly.
- tests: Refactor formField tests to extend `FormFieldTestCase`.
- tests: add WPUnit tests for `HtmlField`, `PageField`, `PhoneField`, `SectionField`, `SelectField`, `RadioField`, and `WebsiteField`.
- chore: update Composer deps.

## v0.8.2 - Bugfix

This minor release fixes `hasNextPage` and `hasPreviousPage` checks on Entry connections.

- fix: use `entryIds` in cursor for `hasNextPage` and `havePreviousPage` checks in the Entries resolver.
- tests: add tests for `has{Next|Previous}Page` on Form and Entry Connections.
- tests: add tests for `HiddenField`, and `NumberField`.
- dev: update Composer dependencies.

## v0.8.1 - `gform_pre_render` Support.

This minor release applies the [`gform_pre_render`](https://docs.gravityforms.com/gform_pre_render/) filter to `GFUtils::get_form()`.

- feat: filter `GFUtils::get_form()` by `gform_pre_render` (h/t @travislopes ).
- dev: add `wp-graphql-stubs` to composer `devDependencies`.

## v0.8.0 - Revamped GraphQL Connections

** :warning: This release requires Gravity Forms v2.5.0 or higher. **

This release reworks all GraphQL connections, implementing data loaders, optimizing database queries, and adding support for more where args in more situations.

### New Features
- `gravityFormsForms` can now be filtered by a list of form IDs.
- `FormEntry` connections now have access to the following `where` args: `status`, `dateFilters`, `fieldFilters`, `fieldFiltersMode`.
- `formField` can now be filtered by a list of field IDs, `adminLabels`, and the field type.
- [Breaking] Full pagination support has been added to `Forms` and `Entries`. **Note**: cursor generation has changed, so if you are manually generating form or entry cursors, you will need to update your code.
- [Breaking] `FieldFiltersOperatorInputEnum` now supports all remaining Gravity Forms entry search operators, such as `LIKE`, `IS`, `IS_NOT`. The `GREATER_THAN` and `LESS_THAN` operators have been removed, as they are not supported by Gravity Forms.

### Bugfixes
- Correctly handle `sourceUrl` changes in `submitGravityFormsForm` and `updateGravityFormsDraftEntry` mutations.
- `wp_graphql_gf_can_view_entries` filter now correctly passes `$form_ids` instead of non-existent `$entry_ids`.
- `fieldFilters` now correctly search through `array` entry values.
- `EntriesFieldFiltersInput.key` is now optional.

### Under the Hood
- [Breaking] Bumped minimum GF version to v2.5.x.
- [Breaking] Connections have been completely refactored. The new classes are `EntryConnections`, `FieldConnections` and `FormConnections`.
- [Breaking] `RootQueryEntriesConnectionResolver` and `RootQueryFormsConnectionResolver` classes were renamed to `EntriesConnectionsResolver` and `FormConnectionResolver`. They now properly extend `AbstractConnectionResolver`.
- Form connections now implement a `DataLoader`.
- Added `GFUtils::get_forms()` for speedy requests from $wpdb.
- Fixed various code smells.
- docs: Updated information about queries to reflect pagination and new `where` args.
- tests: WPUnit tests now extend `GFGraphQLTestCase`.
- tests: [Breaking] `WPGraphQLGravityForms\Tests` namespace has been renamed to `Tests\WPGraphQL\GravityForms`.

## v0.7.3 - WPGraphQL v1.6.x Compatibility

This release adds compatibility with WPGraphQL v1.6.x, [and its new lazy/eager type loading](https://github.com/wp-graphql/wp-graphql/releases/tag/v1.6.0).

- fix: Add `eagerlyLoadType` property to WPGraphQL type registration.
- fix: Hook type registration using `get_graphql_register_action()`.
- fix: Fix typo in `addressField.copyValueOptionsLabel` type definition.
- fix: Check entry values before (unnecessarily) updating them in SubmitGravityFormsForm mutation.
- dev: Update composer dependencies.
- tests: Clear WPGraphQL schema before/after each test.

## v0.7.2.1 - Bugfix

- (Re-released, as the last one incorectly contained the old version.)

- Fixes bug where unset `formFields` properties would cause a type error for `Enums`. (h/t @natac13)

## v0.7.1 - Bugfix

- Fixes error when filtering `gravityFormsEntries` by a value in any field (i.e. when `fieldFilters.key` is `"0"` ).

## v0.7.0 - File Uploads 🚀🚀🚀

** :warning: This release contains multiple breaking changes. **

The big highlight in this release is _experimental<sup>[\*\*\*](#uploadWarning)</sup>_ support for File Upload / Post Image submissions. We also added a feature that updates WordPress post meta when the corresponding Gravity Forms entry is updated.

We added some new WordPress filters, and changed the way some of our PHP classes work, to make it easier for people to add support for their own custom Gravity Forms fields.
We even gave the docs some love. We've added info about filters and using specific form field value inputs, and updated many of the example snippets. You can find the new documnetation in the repo's [/docs folder](/docs).

Note: These changes did necessitate refactoring many of the plugin classes, so if you're extending any of them in your own projects, make sure to update your class methods!

<a name="uploadWarning">\*\*\*</a>: File Uploads and Post Image submissions currently require [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload) to be installed and activated. [Once WPGraphQL adds native support for multipart form requests](https://github.com/wp-graphql/wp-graphql/issues/311), it is likely that these GraphQL input values will change.

### New Features

- Added support for `FileUpload` and `PostImage` field value submissions. Ple
- Updating Post field values in a Gravity Forms entry, now also updates the corresponding WordPress post. Not even native GF lets you do that!
- New WordPress filters: `wp_graphql_gf_type_config`, `wp_graphql_gf_connection_config`.

### Bugfixes

- `chainedSelectValues` input is now only visible if the Chained Selects plugin is active.
- The `Entry` GraphQL type now implements the `wp_graphql_gf_can_view_entries` filter.

### Under the Hood

- feat: add `altText` to `PostImage` field values.
- feat: The `pageNumber` field is now available on _all_ `formFields`.
- dev: `AbstractEnum::set_values()` has been deprecated in favor of `AbstractEnum::get_values()`.
- dev: `Button`, `LastPageButton`, `ConditionalLogic`, `ConditionalLogicRule`, `Entry`, `EntryForm`, `EntryUser`, `FieldError`, `Form`, `FormComfirmation`, `FormNotification`, `FormNotificationRouting`, `FormPagination`, `SaveAndContinue`, now extend AbstractObject`. Their functions have changed accordingly.
- dev: Added `GFUtils::get_gravity_forms_upload_dir()` and `GUtils::handle_file_upload()`.
- dev: Added `Utils::maybe_decode_json()` and `Utils::apply_filters`.
- dev: deprecated `wp_graphql_{$enumType}_values` filter, in favor of `wp_graphql_gf_{$enumType}_values`.
- dev: deprecated `wp_graphql_gf_field_types` filter, as it no longer necessary to manually map GraphQL fields to GF `formFields`.
- dev: deprecated `wp_graphql_gf_form_field_instances` and `wp_graphql_gf_field_value_instances` filters in favor of `wp_graphql_gf_instances`.
- dev: Deprecated the `adminOnly`, `allowsPrepopulated` and `inputName` fields on `PostImageField`.
- dev: Updated composer dependencies.
- dev!: `scr/connections` now extend `AbstractConnection`
- dev!: `src\Types\Input` now extend `AbstractInput`. Their functions have changed accordingly.
- dev!: Class `AbstractField` has been deprecated in favor of `AbstractFormField`. Its functions have changed accordingly.
- dev!: Classes `AbstractProperty`, `AbstractFieldValue` have been deprecated in favor of `AbstractObject`. Their functions have changed accordingly.
- dev!: make plugin `$instances` static for easier access and extension.
- dev!: The `Enum`, `InputType`, `ValueProperty` PHP interface has been deprecated. Please update your code.
- dev!: The methods required by the `FieldValue`, `Type` PHP interface have changed. Please update your code.
- tests: Removed `testGetEnabledFieldTypes` now that we are using `static $instances`.

## v0.6.3 - Unit Tests

- Adds support for missing date formats (dmy_dash, dmy_dot, ymd_slash, ymd_dash, ymd_dot).
- Fix: EmailInputProperty description updated.
- Dev: autocompleteAttribute has been deprecated on EmailInputProperty.
- Tests: Added `updateEntry` and `updateDraftEntry` mutations to existing `formField` tests.
- Tests: Add tests for CheckboxField, ConsentField, and DateField.

## v0.6.2.2 - Hotfix

- Fixes `submitGravityFormsForm` not saving signature field value after v0.6.2.1.

## v0.6.2.1 - Hotfix

- Fixes `updateGravityFormsEntry` not saving signature field value.

## v0.6.2 - Bugfixes

- Fixes `updateGravityFormsEntry` mutation not propery saving complex field values (h/t @natac13 )
- Fixes mutations not correctly deleting old `SignatureField` files from the server (h/t @natac13 )
- Fixes `SignatureFieldValue`s failing Gravity Forms `isRequired` validation (h/t/ @natac13).
- Fixes empty `formFields.layoutGridColumnSpan` values not returning as `null`.
- Removes deprecation from `ChainedSelectInput.name`.
- Correctly sets `graphql_connection_max_query_amount` to a minimum of `600`.
- `AbstractMutation::prepare_field_value_by_type()` no longer tries processing unrecognized fields.
- Dev: Added filter `wp_graphql_gf_prepare_field_value` for processing custom field values.
- Dev: Added filter `wp_graphql_gf_field_value_type` for adding custom field value input types.
- Dev: The arguments for AbstractMutation::validate_field_value() have changed to no longer require passing the `$form`. Passing the old set of arguments may stop working in future versions of the plugin.
- Tests: Added unit tests for `ChainedSelect` fields and values.

## v0.6.1 - Bugfix

- Fixes a fatal error when adding support for new fields with the `wp_graphql_gf_field_types` filter.

## v0.6.0 - Gravity Forms v2.5 Support

This release adds support for all the new goodies in Gravity Forms v2.5, squashes a few bugs related to Captcha fields, and refactors the `InputProperty` on various form fields.

### New Features

- Added `customRequiredIndicator`, `markupVersion`, `requiredIndicator`, `validationSummary` and `version` to `GravityFormsForm` object.
- Added `layoutGridColumnSpan` and `layoutSpacerGridColumnSpan` to `formFields` interface.
- Added `enableAutocomplete` and `autocompleteAttribute` to `AddressField`, `EmailField` ,`NameField`, `NumberField`, `PhoneField`, `SelectField`, and `TextField`.
- Added `displayOnly` property to `CaptchaField`.
- Added `allowedExtensions` and `displayAlt` property to `PostImageField`.
- Added `sort` argument for filtering `RootQueryToGravityFormsFormConnection`. _Note_: Attempting to sort on GF < v2.5.0.0 will throw a `UserError`.

### Bugfixes

- [Breaking]: Fixed the `captchaTheme` enum to use the correct possible values: `light` and `dark`.
- `captchaTheme` and `captchaType` now correctly return `null` when not set by the field.
- The `captchaType` enum now has a default value of `RECAPTCHA`.

### Under the hood

- Refactor various `InputProperty` classes. `InputDefaultValueProperty`, `InputLabelProperty`, and `InputplaceholderProperty` have been removed for their `FieldProperty` cousins, and `EmailInputProperty` is now being used for `EmailField`.
- Tests: Clear `GFFormDisplay::$submission` between individual tests.
- Tests: Allow overriding the default field factories.
- Tests: Adds tests for `CaptchaField`.

## v0.5.0 - Add Gatsby Support, Email Confirmation, and more!

** :warning: This release contains multiple breaking changes. **

This release moves `entry.formField` values from `edges` to `nodes`, slimming down the query boilerplate and making the plugin compatible with `gatsby-source-wordpress`. We also added support for submitting an email `confirmationValue` and retrieving `PostImage` values, squashed a few bugs, and made the `wp_graphql_gf_can_view_entries` filter more useful.

We also complete removed the form/entry `fields` property. All usage should be replaced with `formFields`.

### New features

- [**Breaking**] Removed `fields` from `entry` and `form`. Please update your code to use `formFields` instead.
- [**Breaking**] Added support for submitting email confirmation values by using a new input type `FieldValuesInput.emailValues`.

```diff
{
  submitGravityFormsForm(
    input: {
      formId: 1
      clientMutationId: "123abcc"
      fieldValues: [
        {
          id: 1
-         value: "myemail@email.test"
+         emailValues: {
+           value: "myemail@email.test"
+           confirmationValue: "myemail@email.test" # Only necessary if Email confirmation is enabled.
         }
        }
      ]
    }
  )
}
```

- [**Breaking**] The `wp_graphql_gf_can_view_entries` filter now passes `entry_ids` instead of `field_ids`. This lets you do cool things like allowing authenticated users to edit _only their own_ entries:

```php
add_filter(
  'wp_graphql_gf_can_view_entries',
  function( bool $can_view_entries, array $entry_ids ) {
    if ( ! $can_view_entries ) {
      $current_user_id = get_current_user_id();

      // Grab each queried entry and check if the `created_by` id matches the current user id.
      foreach ( $entry_ids as $id ) {
        $entry = GFAPI::get_entry( $id );

        if ( $current_user_id !== (int) $entry['created_by'] ) {
          return false;
        }
      }
    }

    return true;
  },
  10,
  2
);
```

- Deprecated `formFields.edges.fieldValue` in favor of `formFields.nodes.{value|typeValues}`. Not just does this dramatically slim down the boilerplate needed for your queries, but it also works with `gatsby-source-wordpress`.

```diff
{
  gravityFormsEntry(id: 2977, idType: DATABASE_ID) {
    formFields{
-     edges {
-       fieldValue {
-         ... on TextFieldValue {
-           value
-         }
-         ... on CheckboxFieldValue {
-           checkboxValues {
-             inputId
-             value
-           }
-         }
-         ... on AddressFieldValue {
-           addressValues {
-             street
-             lineTwo
-             city
-             state
-             zip
-             country
-           }
-         }
-       }
-     }
      nodes {
        ... on TextField {
          # Other field properties
+         value
        }
        ... on CheckboxField {
          # Other field properties
+         checkboxValues {
+           inputId
+           value
+         }
        }
        ... on AddressField {
          # Other field properties
+         addressValues {
+           street
+           lineTwo
+           city
+           state
+           zip
+           country
+         }
        }
      }
    }
  }
}
```

- Added support for retrieving `PostImage` field values.

### Bugfixes

- Fixed field `id`s missing from `UpdateDraftEntry{Type}FieldValue` `errors`.
- Prevented PHP notices about missing `entry_id` when `submitGravityFormsDraftEntry fails.
- Prevented `UpdateDraftEntry{Type}FieldValue` from deleting the previously stored `ip`.
- Entry queries now correctly check for `gform_full_access` permission when `gravityforms_edit_entries` doesn't exist.
- Undeprecate `InputKeyProperty` on `name` and `address` fields. Although this is not part of the GF api, it is helpful to associate the `inputs` with their entry `values`.

### Under the hood

- Added more unit tests for `TextField`, `TextAreaField`, and `AddressField`.
- Refactored `FieldProperty` classes to use `AbstractProperty`.

## v0.4.1 - Bugfix

- Uses `sanitize_text_field` to sanitize email values, so failing values can be validated by Gravity Forms. ( h/t @PudparK )

## v0.4.0 - A Simpler Form Submission Flow!

** :warning: This release contains multiple breaking changes. **

This release adds the `submitGravityFormsForm` mutation that submit forms without needing to use the existing `createGravityFormsDraftEntry` -> `updateDraftEntry{fieldType}Value` -> `submitGravityFormsDraftEntry` flow.

Similarly, we've added support for updating entries and draft entries with a single mutation each, and added support for using form and entry IDs in queries - without needing to convert them to a global ID first. We also deprecated `fields` in favor of `formFields`, so there's less confusion between GF fields and GraphQL fields.

Also, we made several (breaking) changes to streamline queries and mutations: many GraphQL properties have been changed to `Enum` types, and `formFields` (and the now-deprecated `fields`) are now an interface. This should make your queries and mutations less error-prone and (a bit) more concise. We're also now using native Gravity Forms functions wherever possible, which should help ensure consistency going forward.

Beyond that, we've squashed some bugs, deprecated some confusing and unnecessary fields, and refactored a huge portion of the codebase that should speed up development and improve code quality in the long term.

### New features

- Added `submitGravityFormsForm` mutation to bypass the existing draft entry flow. See [README.MD](https://github.com/harness-software/wp-graphql-gravity-forms/README.md#documentation-submit-form-mutation) for usage.
- Added `updateGravityFormsEntry` and `updateGravityFormsDraftEntry` mutations that follow the same pattern.
- Added `idType` to `GravityFormsForm` and `GravityFormsEntry`, so you can now query them using the database ID, instead of generating a global id first.
- Added `id` property to `FieldErrors`, so you know which failed validation.
- Deprecated the `fields` property on `GravityFormsForm` and `GravityFormsEntry` in favor of `formFields`.
- Support cloning an existing entry when using `createGravityFormsDraftEntry` using the `fromEntryId` input property.
- Converted all Gravity Forms `formFields` (and the now-deprecated `fields`) to a GraphQL Interface type. That means your queries can now look like this:

```graphql
query {
  gravityFormsForms {
    nodes {
      formFields {
        nodes {
          formId
          type
          id
          ... on AddressField {
            inputs {
              defaultValue
            }
          }
          ... on TextField {
            defaultValue
          }
        }
      }
    }
  }
}
```

- Switched many field types from `String` to `Enum`:
- `AddressField.addressType`
- `Button.type`
- `CaptchaField.captchaTheme`
- `CaptchaField.captchaType`
- `CaptchaField.simpleCaptchaSize`
- `ChainedSelectField.chainedSelectsAlignment`
- `ConditionalLogic.actionType`
- `ConditionalLogic.logicType`
- `ConditionalLogicRule.operator`
- `DateField.calendarIconType`
- `DateField.dateFormat`
- `DateField.dateType`
- `EntriesFieldFilterInput.operator`
- `EntriesSortingInput.direction`
- `Form.descriptionPlacement`
- `Form.labelPlacement`
- `Form.limitEntriesPeriod`
- `Form.subLabelPlacement`
- `FormConfirmation.type`
- `FormNotification.toType`
- `FormNotificationRouting.operator`
- `FormPagination.style`
- `FormPagination.type`
- `GravityFormsEntry.fieldFiltersNode`
- `GravityFormsEntry.status`
- `NumberField.numberFormat`
- `PasswordField.minPasswordStrength`
- `PhoneField.phoneFormat`
- `RootQueryToGravityFormsFormConnection.status`
- `SignatureField.borderStyle`
- `SignatureField.borderWidth`
- `TimeField.timeFormat`
- `visibilityProperty`
- FieldProperty: `descriptionPlacement`
- FieldProperty: `labelPlacement`
- FieldProperty: `sizeProperty`

### Bugfixes

- `SaveAndContinue` now uses `buttonText` instead of the `Button` type.
- `lastPageButton` now uses its own GraphQL type with the relevant fields.
- The `resumeToken` input field on the `deleteGravityFormsDraftEntry`, `SubmitGravityFormsDraftEntry`, and all the `updateDraftEntry{fieldType}Value` mutations is now a non-nullable `String!`.
- When querying entries, we check that `createdByID` is set before trying to fetch the uerdata.
- Where possible, mutations and queries now try to return an `errors` object instead of throwing an Exception.
- We've added more descriptive `Exception` messages across the plugin, to help you figure out what went wrong.
- We fixed a type conflict with `ConsentFieldValue`. `value` now returns a `String` with the consent message, or `null` if false.
- Deprecated `url` in favor of `value` on `FileUploadFieldValue` and `SignatureFieldValue`.
- Deprecated `cssClassList` in favor of `cssClass`.
- The `resumeToken` input field on the `deleteGravityFormsDraftEntry`, `SubmitGravityFormsDraftEntry`, and all the `updateDraftEntry{fieldType}Value` mutations is now a non-nullable `String!`.

### Under the hood

- Refactored Fields, FieldValues, and Mutations, removing over 500 lines of code and improving consistency across classes.
- Switch to using `GFAPI::submit_form()` instead of local implementations for submitting forms and draft entries.
- Implemented phpstan linting.
- Migrated unit tests to Codeception, and started backfilling missing tests.
- Updated composer dependencies.

## v0.3.1 - Bugfixes

- Removes `abstract` class definition from FieldProperty classes. (#79)
- `ConsentFieldValue`: The `value` field was a conflicting type `Boolean`. Now it correctly returns a `String` with the consent message. ( #80 )
- `FormNotificationRouting`: The `fieldId` now correctly returns an `Int` instead of a `String`. (#81)
- When checking for missing `GravityFormsForm` values, `limitEntriesCount`, `scheduleEndHour` and `scheduleEndMinute` now correctly return as type `Int` (#83)

## v0.3.0 - Add Missing Mutations, the Consent Field, and more!

This release focuses on adding in missing mutations for existing form field - including those needed for Post Creation. We also added support for the Consent field, and squashed some bugs.

### New Field Support: Consent

- Added `consentField` and `updateDraftEntryConsentFieldValue`.

### New Field Mutations

- Added `updateDraftEntryChainedSelectFieldValue` mutation.
- Added `updateDraftEntryHiddenFieldValue` mutation.
- Added `updateDraftEntryPostCategoryFieldValue` mutation.
- Added `updateDraftEntryPostContentFieldValue` mutation.
- Added `updateDraftEntryPostCustomFieldValue` mutation.
- Added `updateDraftEntryPostTagsFieldValue` mutation.
- Added `updateDraftEntryPostTitleFieldValue` mutation.

### Added Field Properties

- Added the `isHidden` property to `PasswordInput`.

### Bugfixes

- Changed the way we were saving the `listField` values for both single- and multi-column setups, so GF can read them correctly.
- Fix a bug where a PHP Notice would be thrown when no `listField` value was submitted - even if the field was not required.
- Fixed a bug that was preventing unused draft signature images from getting deleted.
- Updated how we retrieve the signature url so we're no longer using deprecated functions.

### Misc.

- Renamed `ListInput` and `ListFieldValue` properties to something more sensical. `input.value.values` is now `input.value.rowValues`, and `fieldValue.listValues.value` is now `fieldValue.listValues.values`. The old property names will continue to work until further notice.
- Updated composer dependencies.

## v0.2.0 - Add / Deprecate Field Properties

This release focuses on adding in missing properties on the existing form fields, and deprecating any properties that aren't used by Gravity Forms.

### Added field properties:

- Adds following properties to the Address field: `descriptionPlacement`, `subLabelPlacement`, `copyValuesOptionDefault`, `copyValuesOptionField`, `copyValuesOptionLabel`, `enableCopyValuesOption`.
- Adds following subproperties to the Address `inputs` property: `customLabel`, `defaultValue`, `placeholder`.
- Adds following properties to the Captcha field: `descriptionPlacement`, `description`, `size`, `captchaType`.
- Adds following properties to the ChainedSelect field: `descriptionPlacement`, `description`, `noDuplicates`, `subLabel`.
- Adds following properties to the Checkbox field: `descriptionPlacement`, `enablePrice`.
- Adds following properties to the Date field: `descriptionPlacement`, `inputs`, `subLabel`, `dateType`.
- Adds following properties to the Email field: `descriptionPlacement`, `inputs`, `subLabel`, `emailConfirmEnabled`.
- Adds following properties to the File Upload field: `descriptionPlacement`.
- Adds following properties to the Hidden field: `allowsPrepopulate`.
- Adds following properties to the HTML field: `displayOnly`, `disableMargins`.
- Adds following properties to the Name field: `descriptionPlacement`, `subLabelPlacement`.
- Adds following subproperties to the Name field `inputs` property: `customLabel`, `defaultValue`, `placeholder`, `choices`, `enableChoiceValue`.
- Adds following properties to the Number field: `descriptionPlacement`, `calculationFormula`, `calculationRounding`, `enableCalculation`.
- Adds following properties to the Page field: `displayOnly`, `size`.
- Adds following properties to the Password field: `subLabelPlacement`.
- Adds following properties to the Phone field: `descriptionPlacement`.
- Adds following properties to the Post Category field: `descriptionPlacement`, `noDuplicates`, `placeholder`.
- Adds following properties to the Post Content: `descriptionPlacement`, `maxLength`.
- Adds following properties to the Post Custom field: `descriptionPlacement`, `maxLength`.
- Adds following properties to the Post Excerpt field: `descriptionPlacement`, `maxLength`.
- Adds following properties to the Post Image field: `descriptionPlacement`.
- Adds following properties to the Post Tags field: `descriptionPlacement`, `enableSelectAll`, `maxLength`.
- Adds following properties to the Post Title field: `descriptionPlacement`.
- Adds following properties to the Radio field: `descriptionPlacement`, `enablePrice`.
- Adds following properties to the Section field: `descriptionPlacement`, `displayOnly`.
- Adds following properties to the Select field: `defaultValue`, `descriptionPlacement`, `enablePrice`.
- Adds following properties to the Signature field: `descriptionPlacement`.
- Adds following properties to the TextArea field: `descriptionPlacement`, `useRichTextEditor`.
- Adds following properties to the Text field: `descriptionPlacement`.
- Adds following properties to the Time field: `descriptionPlacement`, `inputs`, `subLabelPlacement`.
- Adds following properties to the Time field: `descriptionPlacement`.

### Deprecated Field Properties

These properties will be removed in v1.0.

- Deprecate the following properties from the Address field: `inputName`.
- Deprecate the following properties from the Captcha field: `adminLabel`, `adminOnly`, `allowsPrepopulate`, `visibility`.
- Deprecate the following properties from the File Upload field: `allowsPrepopulate`, `inputName`.
- Deprecate the following properties from the Hidden field: `adminLabel`, `adminOnly`, `isRequired`, `noDuplicates`, `visibility`.
- Deprecate the following properties from the HTML field: `adminLabel`, `adminOnly`, `allowsPrepopulate`, `inputName`, `visibility`.
- Deprecate the following properties from the Name field: `inputName`.
- Deprecate the following properties from the Page field: `adminLabel`, `adminOnly`, `allowsPrepopulate`, `label`, `visibility`.
- Deprecate the following properties from the Password field: `allowsPrepopulate`, `visibility`.
- Deprecate the following properties from the Section field: `adminLabel`, `adminOnly`, `allowsPrepopulate`.
- Deprecate the `key` property on all field `inputs` properties.
- Deprecate the following properties from the Chained Select field `inputs` property: `isHidden`, `name`.

### Misc.

- [Bugfix] Fix saving draft submission with wrong `gform_unique_id` when none is set.
- [PHPCS] Various docblock and comment fixes.

## v0.1.0 - Initial Public Release

This release takes the last year and a half of work on this plugin, and makes it ready for public consumption and contribution, by adopting SemVer, WordPress Coding Standards, etc.

Please see [README.md](https://github.com/harness-software/wp-graphql-gravity-forms/blob/main/README.md) for a list of all functionality.
