# Changelog

## Unreleased
- chore: Fix Composer PHP version constraints and rebuild lockfile. Thanks @szepeviktor!
- fix: Check if entries exist before resolving the connection `count`.
- fix: Improve type checks when calculating the `QuizResults` data.
- ci: Fix GitHub Action workflows by locking MariaDB version to v10.

## v0.12.2

This _minor_ release expands the ability to use database and global IDs interchangeably in more connections. We also now prime the `GFFormsLoader` dataloader, reducing the number of database queries and improving performance.

We've also implemented the new WPGraphQL Coding Standards ruleset for `PHP_CodeSniffer`. While many of the (over 1000+) addressed sniffs are cosmetic, numerous smells regarding performance, type safety, sanitization, and 3rd-party interoperability have been fixed as well.

- dev: Refactor database ID resolution when the GraphQL `ID` type is indeterminate. Note: The following input args now work with both database and global IDs: `GfEntriesConnectionWhereArgs.formIds`, `GfFormsConnectionwhereArgs.formIds`.
- dev: Remove usage of deprecated `WPGraphQL\Data\DataSource::resolve_post_object()` method.
- dev: Prime the GfForm dataloader when querying form connections, to prevent unnecessary database queries.
- chore: Implement `axepress/wp-graphql-cs` PHP_Codesniffer ruleset, and fix all resulting issues.
- docs: Add missing documentation regarding using `productValues` input when submitting forms.

## v0.12.1 - Bug fix

This _minor_ release fixes an issue where certain complex Field Input and Field Choice types would try to implement a non-existent parent inteface, causing GraphQL debug messages to be returned in the response.

- fix: Use local store for `FieldInputRegistry` and `FieldChoiceRegistry` to prevent the registration of duplicate/nonexistent types.
- chore: Update Composer dev deps.
- test: Ensure no `extensions['debug']` messages are returned when querying FormFields.
- ci: Test against WordPress 6.2
- ci: Ignore `graphql-schema-linter` error for `FormFieldConnection.pageInfo` missing a description. This will be reverted once FormFieldConnection is refactored to be a Relay-compatible.

## v0.12.0 - Form Field Interfaces and Pricing Fields

**:warning: This release contains multiple breaking changes.**

This _major_ release refactors the way Gravity Forms fields are registered in GraphQL, by using GraphQL interfaces derived from the Forms Field Settings to register the fields, choices, and inputs tp the type. This allows for more flexibility in how fields are registered and consumed, and for DRYer GraphQL queries and frontend components.

This release also adds explicit support for Pricing Fields. Specifically, we've added support for the `Option`, `Product`, `Quantity`, `Shipping`, and `Total` fields, and the `orderSummary` field on `GfEntry`.

Lastly, we've exposed the `connectedChoice` and `connectedInput` fields on `CheckboxFieldValue` objects, added some additional test coverage, and squashed a few bugs along the way.

**Note**: The minimum version of WPGraphQL has been bumped to v1.9.0.

### What's New

* **🚨 Breaking**: We've refactored the way Gravity Forms fields are registered in GraphQL, by using GraphQL interfaces derived from the Gravity Forms Field Settings. **Note**: This is a breaking change, and will require you to update your GraphQL queries for `formField.choices` and `formField.inputs` to use the new `FormFieldChoice` and `FormFieldInput` interfaces, respectively.
* We've added explicit support for the `Option`, `Product`, `Quantity`, `Shipping`, and `Total` Gravity Forms fields.
* We've added the new `orderSummary` field to `GfEntry` objects, which contains all the order details for the form submission.
* We've exposed the `connectedChoice` and `connectedInput` fields to `CheckboxFieldValue` objects, to make it easier to access specific values of the selected choices and inputs without hacky workarounds.
* We've _deprecated_ the `FormField.id` field in favor of `FormField.databaseId`, which is more consistent with WPGraphQL's naming conventions. **Note**: `FormField.id` will change its type to a global (Relay) ID in an upcoming release.

### **🚨 Breaking** Schema Changes

* Field `AddressField.defaultProvince` changed type from String to `AddressFieldProvinceEnum`.
* Field `AddressField.defaultState` changed type from String to `AddressFieldProvinceEnum`.
* All `{FieldType}.inputs` fields changed type from `[AddressInputProperty]` to `[GfFieldInput]`.
* Field `{FieldType}.choices` changed type from `[ChainedSelectFieldChoice]` to `[GfFieldChoice]`.
* Enum value `SUBMIT` was removed from enum `FormFieldTypeEnum`.
* **🚨 Breaking**: `PostCategoryFieldChoice` kind changed from `ObjectTypeDefinition` to `InterfaceTypeDefinition`.
* Type `PostCategoryInputProperty` was removed.
* Type `PostCustomInputProperty` was removed.
* Type `PostTagsInputProperty` was removed.
* `QuizFieldChoice` kind changed from `ObjectTypeDefinition` to InterfaceTypeDefinition.
* Type `QuizInputProperty` was removed.
* Type `SubmitField` was removed.

### Fixes

- fix: Ensure latest mutation input data is used to prepare the field values on update mutations.
- fix: Check for falsy `personalData` when resolving the form model.

### Misc

- feat: Deprecate `FormsConnectionOrderbyInput.field` in favor of `FormsConnectionOrderbyInput.column`

### Behind the Scenes

- feat!: Update minimum required WPGraphQL version to v1.9.0.
- dev!: Move `TypeRegistry` classes to `WPGraphQL\GF\Registry` namespace.
- dev!: Register each GraphQL type on its own `add_action()` call.
- dev!: Remove nullable `$type_registry` param from `Registrable::register()` interface method.
- dev!: Remove the `$type_registry` param from the `graphql_gf_after_register_types` and `graphql_gf_before_register_types` actions.
- dev!: Remove the `PropertyMapper`, `ChoiceMapper`, `InputMapper`, and `FieldProperties` PHP classes in favor of the `FormFieldRegistry`, `FieldInputRegistry` and `FieldChoiceRegistry` classes.
- dev!: Check if plugin dependences meet the minimum version requirements.
- dev: Add following actions: `graphql_gf_after_register_form_field`, `graphql_gf_after_register_form_field_object`.
- dev: Add the following filters: 
`graphql_gf_form_field_settings_with_inputs`
`graphql_gf_form_field_settings_with_choices`
`graphql_gf_form_field_setting_choice_fields`, `graphql_gf_form_field_setting_input_fields`, `graphql_gf_registered_form_field_setting_classes`, `graphql_gf_registered_form_field_setting_choice_classes`, `graphql_gf_registered_form_field_setting_input_classes`.
- dev: Deprecate the `graphql_gf_form_field_setting_properties` filter in favor of `graphql_gf_form_field_setting_fields`.
- dev: Deprecate the `graphql_gf_form_field_value_properties` filter in favor of `graphql_gf_form_field_value_fields`.
- chore: Refactor `FormsConnectionResolver` to use new `AbstractConnectionResolver` methods.
- chore: Add `automattic/vipcs` Code Standard ruleset.
- ci: Update GitHub Action versions used in workflows to latest.
- ci: Update Node version to 16+.
- ci: Run actions on organization repository.
- ci: Add coverage to Code Climate.
- ci: Set config to skip GF Setup screen.
- tests: Rename FormFieldTestCase test methods for specificity.
- tests: Format and harden.
- tests: Add tests for `submitGfForm` mutation.
- docs: replace `formId` with `id` in `submitGfForm` examples. Props: @mosesintech


## v0.11.11 - Bugfix

This _minor_ release fixes a bug where the `ip` field on `GfEntry` was not being properly stored when submitting a form.

- fix: Properly store provided IP when submitting an entry. Thanks @marcusforsberg !
- chore: Update composer deps.

## v0.11.10 - Gravity Forms v2.6.8+ Compatibility

This _minor_ release adds compatibility for Gravity Forms v2.6.8+ by refactoring the internal logic used for uploading files to use native Gravity Forms methods whenever possible.

- fix: Refactor File Upload logic for compatibility with Gravity Forms 2.6.8+.
- chore: Update composer deps.

## v0.11.9 - WPGraphQL v1.13.x Compatibility

This _minor_ release adds compatibility for WPGraphQL v1.13.x, by removing the new `Connection`, `Edge`, and `OneToOneConnection` interfaces from the `FormField` connections. This is a temporary fix, and will be reverted in a future release.

- fix: remove incompatible interfaces from `FormField` connections.
- fix: remove redundant `There was an error while processing the form.` prologue from submission `UserError`s.
- chore: update Composer deps.
- chore: fix PHPStan issues surfaced by new Composer deps.

## v0.11.8 - Bugfix

This _minor_ release fixes an issue where querying for `NumberField.calculationRounding` would sometimes throw an error when `Rounding` is set to `Do not round`.
- fix: explicitly set `calculationRounding` to `null` when rounding is disabled.
- chore: update Composer deps.

## v0.11.7 - Bugfix

This _minor_ release fixes an issue with the GraphQL dataloader storing the GF form, instead of the 'prerendered' version used by many 3rd-party plugins. It also fixes `FormQuiz` GraphQL fields from resolving if they are not associated with the current `gradingType` (e.g. `passPercent` on a `LETTER` grade ).

- fix: Run `gform_pre_render` on Form objects before they are stored in the DataLoader.
- fix: FormQuiz fields should return null is not associated with current `gradingType`.
- chore: Update Composer deps.

## v0.11.6 - Bugfix

This _minor_ release fixes a bug where the resolver for `FormField.choices` wasn't always correctly parsing and passing the data, causing GraphQL fields on Quiz and Chained Select choices to return incorrect values.

- fix: `FormField.choices` doesn't always resolve values correctly.
- chore: replace abandoned `poolshark/wp-graphql-stubs` Composer dependency with `axewp/wp-graphql-stubs`.

## v0.11.5 - Quiz Setting Bugfixes

This _minor_ release fixes a bug where `gfForm.quiz` data was not resolving, as well as GraphQL types for `FormQuizConfirmation` fields.

**Note**: This release is _technically_ a breaking schema change, however since those fields are entirely unusable with their current type definitions, we don't expect this have any negative impact on users when upgrading.

- (#314) fix!: Change GraphQL field `FormQuizConfirmation.isAutoformatted` from type `String` to type `Boolean`.
- (#314) fix!: Change GraphQL field `FormQuizConfirmation.message` from type `Int` to type `String`.
- (#314) fix: Fix resolver for `GfForm.quiz` returning empty data.
- (#314) test: Add basic WPUnit tests for `GfForm.quiz` data.
- (#315) test: Fix `FormConnectionQueriesTest` classname corrupted after backporting from v0.11.4

## v0.11.4

This _minor_ release fixes a bug where `gfForm.entries` would return entries from _all_ forms, among other things.

- (#291) feat: Update `plugin-update-checker` to `v4.13` and enable use of local assets on Dashboard screen.
- (#307) fix: ensure form->entry connections only return entries on that form.
- (#307) fix: change `formIds` input description to clarify that it (currently) only accepts database IDs.
- (#304) chore: update Composer deps.
- (#307) test: ensure `$_gf_state` is reset between tests.
- (#307) test: add some extra WPUnit tests for form/entry connection where args.

## v0.11.3 - WPGraphQL v1.9.0 Compatibility

This _minor_ release fixes a bug where `gfEntries.pageInfo` fields would have incorrect data after upgrading to WPGraphQL v1.9.0.

**Note:** As a result of [WPGraphQL v1.9.0](https://github.com/wp-graphql/wp-graphql/releases/tag/v1.9.0), the *order* of items returned when using backwards pagination (e.g. `last:5`) is now reversed and identical to the order of items returned when using forward pagination, as per the [GraphQL Relay spec](https://relay.dev/graphql/connections.htm#sec-Edge-order).

- fix: Refactor `EntriesConnectionResolver` to support WPGraphQL v1.9.0.
- test: refactor `gfForms` and `gfEntries` pagination tests.

## v0.11.2 - Build Scripts, Confirmations, & DataLoaders

This _minor_ release fixes a bug where querying for a non-existent Form/Entry id would throw an error instead of returning `null`.

We also added some new GraphQL fields and connections to the `FormConfirmation` and `SubmissionConfirmation` types, and made some under-the-hood improvements to both the code and our build scripts.

- feat: Add `isAutoformatted` to the `FormConfirmation` object.
- feat: Add `Page` connection to the `FormConfirmation` object. (h/t @robmarshall )
- feat: Add `pageId` and `queryString` fields and `Page` connection to the `SubmissionConfirmation` object.
- fix: Ensure DataLoader keys return null on non-existent objects, instead of throwing errors. (h/t 6x x6 and Jonathan Ng )
- dev: Fix return type for `Utils::get_possible_form_field_child_types()`.
- dev: Refactor ignored Gravity Forms settings to `FormFields::ignored_gf_settings()`.
- dev: Ensure composer deps are built for PHP 7.4.
- chore: Update Composer deps.
- docs: Add usage example for file uploads.
- ci: Various improvements to workflows.

## v0.11.1.1 - Hotfix
This _hotfix_ release fixes issues with the Github Updater attempting to install plugin updates from the wrong release asset.

- fix: ensure results of graphql_gf_update_repo_url are always trailing-slashed.
- chore: add Composer command for generating plugin .zip
- chore: add Github Action for adding plugin .zip to release.

## v0.11.1 - reCAPTCHA Settings & Submission Confirmations

This _minor_ release adds the reCaptcha V2 `type` and `publicKey` to `gfSettings.recaptcha`, the validated `confirmation` response to form submission mutation payloads, and fixes a handful of bugs.

- feat: Add `recaptcha` settings to `GFSettings` GraphQL object.
- feat: Add `SubmissionConfirmation` object to `submitGfForm` and `submitGfDraftEntry` mutation responses. Props: @KoduVaal
- fix: `isActive` should default to true for new Confirmations / Notifications.
- fix: Correctly resolve the `rangeMin` and `rangeMax` GraphQL fields on `NumberField`. (h/t @natac13 )
- fix: Ensure GF Action Monitor setting keys are populated.
- fix: Ensure `checkboxValues` load the Post Category choices before attempting to process.
- fix: Prevent reprocessing the `imageValueInput.url` when updating `PostImage` field values.
- tests: Fix broken test asserts exposed by PHPUnit v9 + WPGraphQL Test Case v2.3
- tests: Use `gravityformscli` for installing GF plugins in test envs.
- chore: Upgrade composer deps


## v0.11.0 - reCAPTCHA Validation, Plugin Updates, and GF 2.6 Support

**:warning: This release contains breaking changes.**

This _major_ release adds support for server-side captcha validation, plugin updates from the WordPress backend, and new features from Gravity Forms v2.6. We've also refactored the internal file upload mechanism to better integrate with GF's form submission lifecycle, leading to more reliable (and in some cases, more performant) results.

Lastly, we fixed the GraphQL type names on some of the Product and Shipping fields, so we can hopefully add mutation support in future minor releases (and not break back-compat).

**Note**: The minimum version of WPGraphQL has been bumped to v1.7.0.

### What's new
* **🚨 Breaking**: We've added support for server-side captcha validation with reCAPTCHA. **Note**: If you are already using captcha fields in your form, you will need to modify your code to to pass the validation token to `fieldValues`.
* **🚨 Breaking**: The `button` field on `GfForm` has been _deprecated_ in favor of `form.submitButon`. Both now use the new `FormSubmitButton` GraphQL type (instead of the old `FormButton`), which adds support for `layoutGridColumnSpan`, `location` and `width` properties added in GF v2.6.
* We've added support for plugin updates on the WordPress backend. A warning is displayed along with the update notice when upgrading to a version with possible breaking changes (e.g. v0.**X**.y ).
* We've added a new `FileUploadValues` GraphQL type to the `FileUploadField` which includes the `basePath`, `baseUrl`, and `filename` fields in addition to the existing `url`. These fields have also been added to `ImageFieldValues`.

### Behind the scenes
* Added the `graphql_gf_update_repo_url` filter to control the source of the Update Checker.
* Reworked the logic handling file uploads to use the native GF form submission lifecycle whenever possible.

### Misc
* chore!: The minimum version of WPGraphQL is now v1.7.0.
* fix!: The `ProductHiddenProductField`, `ProductSingleProductField`, and `ShippingSingleShippingField` have been renamed to `ProductHiddenField`, `ProductSingleField`, and `ShippingSingleField`, respectively, in line with other child fields.
* fix: Don't hide the `CALCULATION`, `HIDDENPRODUCT`, `SINGLEPRODUCT` and `SINGLESHIPPING` values from the `FormFieldTypeEnum`.
* fix: Resolve `FormQuiz` data using the unmodeled `form` data instead of the `FormObject` model.
* dev: The `lastPageButton` field on `GFForm` has been _deprecated_ in favor of `form.pagination.lastPageButton`, where the other pagination fields live.
* dev: The `values` field on `FileUploadField` has been _deprecated_ in favor of `fileUploadValues`.
* dev: The `GFUtils::handle_file_upload()` method has been _deprecated_.
* dev: The `graphql_gf_form_modeled_data_experimental` filter has been _deprecated_ in favor of WPGraphQL's native `graphql_model_prepare_fields`.

## v0.10.5 - Bugfix

This _minor_ release fixes a few bugs in the `FormsConnectionResolver`. We've also added GitHub Actions for PHPStan and GraphQL schema linting.

- fix: Prevent `gfForms` queries with the `last` argument from truncating the final node.
- fix: Correctly return `hasNextPage` and `hasPreviousPage` values on `gfForms` connections.
- dev: Refactor `bin/install-test-env` into resuable functions.
- chore: Update Composer dependencies.
- chore: Add GH action to save GraphQL artifact to release.
- tests: Add GH Action for GraphQL schema linting.
- tests: Add GH Action for PHPStan

## v0.10.4 - Entry Counts and Quiz Results

This _minor_ release adds the a form's total entries `count` and its `quizResults` to the `GfFormToGfEntryConnection` connection.

E.g.: 
```graphql
query {
  gfForm(id: $id, idType: $idType) {
    entries {
      count # the number of entries submitted
      quizResults { # The quiz results summary
        averagePercentage
          passRate
          gradeCounts { # the frequency of each grade
            count
            grade
          }
          fieldCounts { ## the individual field breakdown
            correctCount
            formField {
              node {
                label
              }
            }
            choiceCounts { ## the frequency of each answer
              count
              text
            }
          }
      }
    }
  }
}
```

- feat: Add `count` to `GfFormToGfEntryConnection`.
- feat: Add `quizResults` to `GfFormToGfEntryConnection`.
- dev: Make original form data available via the Form model.
- chore: Update Composer deps.
- chore: Fix a few GraphQL descriptions that were missing a closing `.`.

## v0.10.3 - WP Jamstack Deployments Support

This _minor_ release adds support for [WP Jamstack Deployments](https://github.com/crgeary/wp-jamstack-deployments). We also fixed a bug where non-authenticated users could not access their own `entry` on the `submitGfForm` payload.

- feat: Add support for WP Jamstack Deployments. 
- fix: Use `graphql_gf_can_view_entries` filter to expose `submitGfFormMutation.entry` to non-authenticated users. (h/t @robmarshall and @IlirBajrami )
- fix: Change WPGatsby Trigger from deprecated `gform_after_duplicate_form` action to `gform_post_form_duplicated`.
- dev: add `$resume_token` and `$draft_entry arguments` to `graphql_gf_can_view_draft_entries`.
- dev: add `$entry_id` and `$entry arguments` to `graphql_gf_can_view_entries`.
- tests: use `databaseId` instead of deprecated `formId` when testing `FormQueriesTest`
- chore: Add new plugin logo and banner ✨

## v0.10.2 - PHP 8 Support

This _minor_ release adds official support for PHP v8.0. We also added Gravity Forms Settings to the schema.

- feat: Add support for PHP v8.0.
- feat: Add `gfSettings` to GraphQL schema.
- dev: Restore `env.dist` to the repo, and add `.devcontainer` to `.gitignore`.
- chore: Update composer deps.

## v0.10.1 - Personal Data Settings

This _minor_ release adds query support for Gravity Forms Personal Data settings and the `isActive` field to `FormConfirmation` objects, and fixes a couple of bugs.

- feat: Add `personalData` settings to `GfForm` and relevant `FormField` objects.
- feat: Add field `isActive` to the `FormConfirmation` object (h/t @natac13).
- fix: Prevent PHP notice caused by the `PostFormatTypeEnum` enum on sites without `post-formats` support (h/t @noshoesplease ).
- fix: Ensure `timeValues.minutes` returns the 2-digit (`mm`) string.
- tests: Fix tests incorrectly passing when a node index isn't explicitly set.
- dev: Update composer dependencies.
- docs: Fix missing/broken links and wording regarding custom mutation support.

## v0.10.0.1 - Hotfix

This _hotfix_ release fixes compatibility issues with `gatsby-source-wordpress`  introduced in the previous release and `WPGraphQL v1.6.11`.

- fix: don't reregister duplicate generated `{type}FieldChoice` and `{type}InputProperty` object types.
- dev: Field `type` on interface `FormField` was changed from `FormFieldTypeEnum!` to `FormFieldTypeEnum`, since `gatsby-source-wordpress` doesn't support non-nullable Enums (h/t @sarah-wfaa).

## v0.10.0 - Major Plugin & Schema Refactor

**:warning: This release contains multiple breaking changes.**

This _major_ release is a refactor the entire plugin in preparation for v1.0. GraphQL fields and types have been renamed and reorganized, the codebase is following ecosystem best practices, WP Actions and Filters have been changed to make it easier than ever, and dozens of performance enhancements have been made under the hood.

We expect this release to be the **last major breaking release** before v1.0. While we can't make any promises, we don't expect to make any more breaking changes to the GraphQL schema beyond those necessary for bug fixes.

### What's new

* **🚨 Breaking**: Gravity Forms form fields are now autoregistered to the GraphQL schema using their registered GF field settings. That means all form fields (including custom fields) are implictly supported. For development purposes, certain core fields are hidden behind the `WPGRAPHQL_GF_EXPERIMENTAL_FIELDS` PHP constant. [Learn more](/docs/form-field-support.md).
**Note**: As a result of this change, the available fields on by the `FormField` interface and on individual Form field objects have changed.
* **🚨 Breaking**: Complex Gravity Forms form fields now inherit the properties of their parent `$inputType`s. Form fields that can resolve to multiple types are now registed as GraphQL Interfaces (e.g. `PostCategoryField`), with their child types as GraphQL objects ( e.g. `PostCategoryCheckboxField` ).
* **🚨 Breaking**: GraphQL objects and fields have been renamed to be self documenting and prevent naming conflicts. Many fields have also been grouped into new GraphQL objects to improve DX and harden against future breaking schema changes. This is equally true for mutation inputs and payloads. 
* **🚨 Breaking**: We've replaced the use of `gravityForms` in the schema with the `gf` shorthand for improved dx. `gravityFormsForms` are now `gfForms`,  `updateGravityFormsEntry` is now `updateGfEntry`, etc. 
* **🚨 Breaking**: Gravity Forms entries and draft entries now inherit the `gfEntry` interface, and use the `GfSubmittedEntry` and `GfDraftEntry` object types.
* **🚨 Breaking**: We've renamed and audited the use of [all WordPress filter hooks](/docs/actions-and-filters.md) to ensure they're actually helpful. We're using them internally to support plugin extensions, and have provided several docs on how to use them.
* We've added support for WPGatsby Action Monitors.

### Behind the scenes

 - **🚨 Breaking**: The entire PHP codebase has been refactored to follow WPGraphQL ecosystem best practices. The namespaces, folder structure, and many file names have changed.
 - **🚨 Breaking**: We've removed all previously deprecated code. This includes the DraftEntryUpdater mutations, numerous GraphQL fields, and several PHP classes and interfaces.
 - **🚨 Breaking**: We're now properly using GraphQL data loaders, models, and connection loaders, bringing with them significant performance boosts. As a result Global Ids are now prefixed with the data loader name, instead of the GraphQL object type.
 - We've stopped unnecessarily double-sanitizing and validating input values that are sanitized/validated by Gravity Forms.

### Misc

* feat: add connection from Entries to their generated `Post` object.
* feat: FormField connections can now be filtered by the form `pageNumber`.
* feat!: change `dateCreated` and `dateUpdated` to be in the site's timezone, and added the `dateCreatedGmt` and `dateUpdatedGmt` for GMT time.
* fix: correctly fallback to default upload directory wen using `GFUtils::handle_file_upload()`.
* fix: don't double sanitize/validate input values that are handled natively by GF.
* fix: prevent existing draft entry properties from being overwritten unnecessarily on update mutations.
* fix!: The default `orderby` (formerly `sort`) direction for Forms is now `DESC` to match expected behavior.
* dev!: change arguments for `GFUtils::get_resume_url()` to allow for empty sourceUrls.
* chore: move functionality for GF Signature, Quiz, and Chained Selects to the `WPGraphQL/GF/Extensions` namespace.
* chore: Update Composer deps.
* chore: Update PHPStan to v1.x and lint.
* docs: Updated existing docs to reflect schema changes, and added `Recipes` that explain in detail how to extend the plugin.
* tests: Refactored FormField tests to use GF field settings to derive the expected GraphQL response.
* tests: Add tests for `FileUpload`,   `PostCategory`,   `PostImage`,   `PostTags`, and `Signature` fields, as well as for `updateGfEntry` and `updateGfDraftEntry` mutations. 

### Important Schema Changes:

#### Renamed

* Field `allowsPrepopulate` was renamed to `canPrepopulate`.
* Field `chainedSelectsHideInactive` was renamed to `shouldHideInactiveChoices`.
* Field `copyValuesOptionField` was renamed to `copyValuesOptionFieldId`.
* Field `disableAutoformat` was renamed to `isAutoformatted`.
* Field `disableMargins` was renamed to `hasMargins`.
* Field `displayAlt` was renamed to `hasAlt`.
* Field `displayCaption` was renamed to `hasCaption`.
* Field `displayDescription` was renamed to `hasDescription`.
* Field `displayProgressbarOnConfirmation` was renamed to `hasProgressbarOnConfirmation`.
* Field `displayTitle` was renamed to `hasTitle`.
* Field `emailConfirmEnabled` was renamed to `hasEmailConfirmation`.
* Field `enableAttachments` was renamed to `shouldSendAttachments`.
* Field `enableAutocomplete` was renamed to `hasAutocomplete`.
* Field `enableCalculation` was renamed to `isCalculation`.
* Field `enableChoiceValue` was renamed to `hasChoiceValue`.
* Field `enableColumns` was renamed to `hasColumns`.
* Field `enableCopyValuesOption` was renamed to `shouldCopyValuesOption`.
* Field `enableEnhancedUI` was renamed to `hasEnhancedUI`.
* Field `enableOtherChoice` was renamed to `hasOtherChoice`.
* Field `enablePasswordInput` was renamed to `isPasswordInput`.
* Field `enablePrice` was renamed to `hasPrice`.
* Field `enableSelectAll` was renamed to `hasSelectAll`.
* Field `FormPagination.pages` was renamed to `pageNames`.
* Field `gravityFormsEntries` was renamed to `gfEntries`. It now returns the `gfEntry` interface.
* Field `gravityFormsEntry` was removed in favor of the `gfEntry` Interface.
* Field `isPass` was renamed to `isPassingScore`.
* Field `multipleFiles` was renamed to `canAcceptMultipleFiles`.
* Field `noDuplicates` was renamed to `shouldAllowDuplicates`.
* Field `passwordStrengthEnabled` was renamed to `hasPasswordStrengthIndicator`.
* Field `postFeaturedImage` was renamed to `isFeaturedImage`.
* Field `useRichTextEditor` was renamed to `hasRichTextEditor`.
* Fields `gravityFormsForm` was renamed to `gfForm`.
* Fields `gravityFormsForms` was renamed to `gfForms`.
* Mutation `deleteGravityFormsDraftEntry` and its associated `Input` and `Payload` objects were renamed to `deleteGfDraftEntry`.
* Mutation `deleteGravityFormsEntry` and its associated `Input` and `Payload` objects were renamed to `deleteGfEntry` and their fields changed.
* Mutation `submitGravityFormsDraftEntry` and its associated `Input` and `Payload` objects were renamed to `submitGfDraftEntry`.
* Mutation `submitGravityFormsForm` and its associated `Input` and `Payload` objects were renamed to `submitGfForm` and their fields changed.
* Mutation `updateGravityFormsDraftEntry` and its associated `Input` and `Payload` objects were renamed to `updateGfDraftEntry`.
* Mutation `updateGravityFormsEntry` and its associated `Input` and `Payload` objects were renamed to `updateGfEntry`.
* Object `AddressInput` was renamed to `AddressFieldInput`.
* Object `AddressTypeEnum` was renamed to `AddressFieldTypeEnum`.
* Object `Button` was renamed to `FormButton`.
* Object `ButtonType` was renamed to `Enum`.
* Object `CalendarIconTypeEnum` was renamed to `FormFieldCalendarIconTypeEnum`.
* Object `CalendarIconTypeEnum` was renamed to `FormFieldCalendarIconTypeEnum`.
* Object `CaptchaThemeEnum` was renamed to `CaptchaFieldThemeEnum`.
* Object `CaptchaTypeEnum` was renamed to `CaptchaFieldTypeEnum`.
* Object `ChainedSelectInput` was renamed to `ChainedSelectFieldInput`.
* Object `ChainedSelectsAlignmentEnum` was renamed to `ChainedSelectFieldAlignmentEnum`.
* Object `CheckboxInput` was renamed to `CheckboxFieldInput`.
* Object `ChoiceProperty` was replaced with form-field specific `{FieldType}FieldChoice` objects.
* Object `ConfirmationTypeEnum` was renamed to `FormConfirmationTypeEnum`.
* Object `ConfirmationTypeEnum` was renamed to `FormConfirmationTypeEnum`.
* Object `DateTypeEnum` was renamed to `DateFieldTypeEnum`.
* Object `DescriptionPlacementPropertyEnum` was renamed to `FormFieldDescriptionPlacementEnum`.
* Object `EmailInput` was renamed to `EmailFieldInput`.
* Object `FieldFiltersOperatorInputEnum` was renamed to `FieldFiltersOperatorEnum`.
* Object `FieldValuesInput` was renamed to `FormFieldValuesInput`.
* Object `FormFieldsEnum` was renamed to `FormFieldTypeEnum`.
* Object `GravityFormsForm` and its associated connection object Types were renamed to `GfForm`.
* Object `LabelPlacementPropertyEnum` was renamed to `FormFieldLabelPlacementEnum` and `FormLabelPlacementEnum`, depending on the context.
* Object `LastPageButton` was renamed to `FormLastPageButton`.
* Object `MinPasswordStrengthEnum` was renamed to `PasswordFieldMinStrengthEnum`.
* Object `NameInput` was renamed to `NameFieldInput`.
* Object `NotificationToTypeEnum` was renamed to `FormNotificationToTypeEnum`.
* Object `PageProgressStyleEnum` was renamed to `FormPageProgressStyleEnum`.
* Object `PageProgressTypeEnum` was renamed to `FormPageProgressTypeEnum`.
* Object `PostImageValueProperty` was renamed to `ImageFieldValue`.
* Object `QuizGrades` was renamed to `FormQuizGrades`.
* Object `QuizGradingTypeEnum` was renamed to `QuizFieldGradingTypeEnum`.
* Object `QuizSettings` was renamed to `FormQuiz`.
* Object `RequiredIndicatorEnum` was renamed to `FormFieldRequiredIndicatorEnum`.
* Object `RuleOperatorEnum` was renamed to `FormRuleOperatorEnum`.
* Object `SaveAndContinue` was renamed to `FormSaveAndContinue`.
* Object `SignatureBorderStyleEnum` was renamed to `SignatureFieldBorderStyleEnum`.
* Object `SignatureBorderWidthEnum` was renamed to `SignatureFieldBorderWidthEnum`.
* Object `SizePropertyEnum` was renamed to `FormFieldSizeEnum`.
* Object `VisibilityPropertyEnum` was renamed to `FormFieldVisibilityEnum`.

#### Removed

* Field `adminLabel` was removed from object types: `CaptchaField`,     `HiddenField`,     `HtmlField`,     `PageField`,     `sectionField`.
* Field `adminOnly` was removed from all FormFields in favor of `visibility`.
* Field `autocompleteAttribute` was removed from object type `EmailField`
* Field `conditionalLogic` was removed from object type `HiddenField`
* Field `copyValuesOptionDefault` was removed from object type AddressField, in favor of `shoudCopyValuesOption`
* Field `cssClass` was removed from object type `HiddenField`
* Field `cssClassList` (deprecated) was removed from all `FormField` objects.
* Field `defaultValue` was removed from object type `EmailField`
* Field `formId` was removed from individual `FormField` objects.
* Field `inputName` was removed from object types: `ConsentField`,  `EmailField`,     `TimeField`,     `FileUploadField`,     `HtmlField`,    `PostImageField`.
* Field `isHidden` (deprecated) was removed from object type `ChainedSelectInputProperty`,     `DateInputProperty`,     `EmailInputProperty`, 
* Field `isRequired` was removed from object type `HiddenField`
* Field `key` (deprecated) was removed from object type `DateInputProperty`,     `EmailInputProperty`
* Field `label` (deprecated) was removed from object type `PageField`
* Field `name` (deprecated) was removed from object type `DateInputProperty`
* Field `nameFormat` was removed from object type `NameField`
* Field `placeholder` was removed from object type `PasswordField`
* Field `quizType` was removed from interface `QuizField`, in favor of `inputType`.
* Field `size` was removed from object types: `AddressField`,  `CaptchaField`,     `ChainedSelectField`,     `CheckboxField`,     `DateField`,     `FileUploadField`,     `HiddenField`,     `HtmlField`,     `ListField`,  `NameField`,     `PageField`,     `RadioField`,     `SectionField`,     `SignatureField`,     `TimeField`, PostImageField
* Field `value` (deprecated) was removed from object type `ListFieldValue`
* Object `EntriesSortingInput` was removed, in favor of `EntriesConnectionOrderbyInput`.
* Object `FormsSortingInput` was removed, in favor of `FormsConnectionOrderbyInput`.
* The following items associated with the deprecated method of form submissions have been removed, including `RootQuery.createGravityFormsDraftEntry`,   `RootQuery.updateDraftEntry{FieldType}FieldValue`, and their related `Input` and `Payload` objects. Most `{FieldType}FieldValue` types were removed, but `AddressFieldValue`,    `CheckboxFieldValue`,  `ListFieldValue`,    `NameFieldValue`,  `TimeFieldValue` have been repurposed.
* Type `CheckboxInputValue`  was removed, in favor of `CheckboxFieldInput`
* Type `EntryForm`  was removed, in favor of returning the `GfForm` directly.
* Type `EntryUser`  was removed, in favor of returning the `User` directly.
* Type `GravityFormsEntry` was removed, in favor of the `GfEntry` interface and `GfSubmittedEntry` object type. The associated Connection object types have been renamed as well.
* Type `ListInputValue`  was removed, in favor of `ListFieldValue`
* Type `SortingInputEnum` was removed, in favor of the `OrderEnum`.

#### Changed Type

* Field `AddressField.country` changed type from `String` to `AddressFieldCountryEnum`
* Field `AddressField.defaultCountry` changed type from `String` to `AddressFieldCountryEnum`
* Field `allowedExtensions` changed type from `String` to `[String]`
* Field `subLabelPlacement` changed type from `String` to `FormFieldSubLabelPlacement` on all `FormField` objects.
* Field `TimeFieldValue.amPm` changed type from `String` to `AmPmEnum`
* Field `type` changed type from `String!` to `FormFieldTypeEnum!` on all `FormField` objects.
* Input field `idType` changed type from `IdTypeEnum` to the Enum relevant to the object type (e.g. `FormIdTypeEnum` ).
* Objects `PostCategoryField`,     `PostCustomField`,     `PostTagsField`,  `QuizField` were changed to a GraphQL `Interface`, and their possible Form field types added as objects.
* The generic `ChoiceProperty` object was replaced with form-field-specific objects `{FieldType}ChoiceProperty`
* The generic `InputProperty` object was replaced with form-field-specific objects `{FieldType}InputProperty`

#### Additions

* Field `captchaBadgePosition` was added to object type `CaptchaField`
* Field `consentValue` was added to object type `ConsentField`
* Field `displayOnly` was added to interface `FormField`.
* Field `hasInputMask` was added to object type `TextField`.
* Field `hasPasswordVisibilityToggle` was added to object type PasswordField
* Field `inputMaskValue` was added to object type TextField
* Field `isOtherChoice` was added to object type QuizChoiceProperty
* Field `isSelected` was added to object type QuizChoiceProperty
* Field `labelPlacement` was added to the relevant `FormField` object types.
* Field `text` was added to object type `CheckboxFieldValue`
* Field `value` was added to every relevant `FormField` object type, in addition to their special `{FieldType}Value`.
* Field `visibility` was moved to interface `FormField`
* The following fields are no longer deprecated: `AddressField.inputName`,     `CaptchaField.visibility`,     `DateInputProperty.autocompleteAttribute`,     `HiddenField.visibility`,     `HtmlField.visibility`,     `NameField.inputName`,     `PageField.visibility`,     `PasswordField.visibility`.
* Type `ListFieldInput` was added
* Type `NodeWithForm` was added
* Type `PostFormatTypeEnum`.
* Types `FormEntryLimits`,     `FormLogin`,     `FormPostCreation`,  `FormSchedule` were added to `GfForm`. Relevant fields from `gfForm` have been moved.

## v0.9.2 - Bugfix and Test

This minor release addresses an issue where `PostImageInput` would be registered to the schema even if WPGraphQL Upload wasn't enabled, breaking gqty and Gatsby schema generation. We also fixed a few other bugs and some overlooked items in the docs, and added some more WPUnit tests.

* chore: define WPGraphQL types with `Int` instead of `Integer` for code consistency.
* docs: Add `TimeField` to list of formFields that take a value input.
* docs: add expected object values for `FileUploadValues`.
* fix: add missing `allowsPrepopulate` property to `PostContentField`.
* fix: `Utils::maybe_decode_json()` support for multidimensional arrays.
* fix: Check for WPGraphQL Upload before registering `PostImageInput` to the schema.
* tests: add WPUnit tests for `ListField`,      `MultiSelectField`,  `NameField`,     `TimeField`,  `PostContentField`,      `PostTitleField`,      `PostExcerptField`,      `GFUtils::get_forms()`,      `Utils::maybe_decode_json()`, and `WPGraphQLGravityForms::instances()`
* tests: remove tests for `{fieldType}FieldValue` edges since they're deprecated anyway.

**Note** As part of the road to v1.0, [the next release will](https://github.com/harness-software/wp-graphql-gravity-forms/issues?q=is%3Aopen+is%3Aissue+milestone%3Av0.10) contain numerous breaking changes to the codebase AND schema, including the removal of deprecated code (such as FieldValue edges). Please prepare accordingly.

## v0.9.1 - Gravity Forms Quiz Support

This minor release adds support for Gravity Forms Quiz fields.

* feat: Add support for GF Quiz fields.
* fix: Fixed the type descriptions for `NoDuplicatesProperty` and `RadioChoiceProperty`.
* dev: Use `GF_Field::get_input_type()` when choosing how to handle input values. This will allow for better support of composite type fields in the future.
* docs: Update language regarding `UpdateDraftEntryFieldValue`'s upcoming deprecation.
* docs: Fix link to Deleting Entries doc.
* tests: Add WPUnit tests for `EmailField`.

## v0.9.0 - Conditional Logic Support on Confirmations

This minor release adds the `conditionalLogic` GraphQL field to `gravityFormsForm.confirmations` . We also squashed a few bugs and implemented some more WPUnit tests.

*Note*: This release technically contains breaking changes for developers making use of `DataManipulator` class methods in their own code.

* fix: consistently apply Gravity Forms filters and WPGraphQL error checking to `GFUtils::get_forms()`.
* feat: add `conditionalLogic` GraphQL field to `gravityFormsForm.confirmations`.
* dev!: makes `DataManipulator` methods static. If you are using any DataManipulator methods in your custom code, please update accordingly.
* tests: Refactor formField tests to extend `FormFieldTestCase`.
* tests: add WPUnit tests for `HtmlField`,     `PageField`,      `PhoneField`,     `SectionField`,      `SelectField`,     `RadioField`, and `WebsiteField`.
* chore: update Composer deps.

## v0.8.2 - Bugfix

This minor release fixes `hasNextPage` and `hasPreviousPage` checks on Entry connections.

* fix: use `entryIds` in cursor for `hasNextPage` and `havePreviousPage` checks in the Entries resolver.
* tests: add tests for `has{Next|Previous}Page` on Form and Entry Connections.
* tests: add tests for `HiddenField`, and `NumberField`.
* dev: update Composer dependencies.

## v0.8.1 - `gform_pre_render` Support.

This minor release applies the [ `gform_pre_render` ](https://docs.gravityforms.com/gform_pre_render/) filter to `GFUtils::get_form()` .

* feat: filter `GFUtils::get_form()` by `gform_pre_render` (h/t @travislopes ).
* dev: add `wp-graphql-stubs` to composer `devDependencies`.

## v0.8.0 - Revamped GraphQL Connections

** :warning: This release requires Gravity Forms v2.5.0 or higher. **

This release reworks all GraphQL connections, implementing data loaders, optimizing database queries, and adding support for more where args in more situations.

### New Features

* `gravityFormsForms` can now be filtered by a list of form IDs.
* `FormEntry` connections now have access to the following `where` args: `status`,      `dateFilters`,      `fieldFilters`,      `fieldFiltersMode`.
* `formField` can now be filtered by a list of field IDs,      `adminLabels`, and the field type.
* [Breaking] Full pagination support has been added to `Forms` and `Entries`. **Note**: cursor generation has changed, so if you are manually generating form or entry cursors, you will need to update your code.
* [Breaking] `FieldFiltersOperatorInputEnum` now supports all remaining Gravity Forms entry search operators, such as `LIKE`,      `IS`,      `IS_NOT`. The `GREATER_THAN` and `LESS_THAN` operators have been removed, as they are not supported by Gravity Forms.

### Bugfixes

* Correctly handle `sourceUrl` changes in `submitGravityFormsForm` and `updateGravityFormsDraftEntry` mutations.
* `wp_graphql_gf_can_view_entries` filter now correctly passes `$form_ids` instead of non-existent `$entry_ids`.
* `fieldFilters` now correctly search through `array` entry values.
* `EntriesFieldFiltersInput.key` is now optional.

### Under the Hood

* [Breaking] Bumped minimum GF version to v2.5.x.
* [Breaking] Connections have been completely refactored. The new classes are `EntryConnections`,  `FieldConnections` and `FormConnections`.
* [Breaking] `RootQueryEntriesConnectionResolver` and `RootQueryFormsConnectionResolver` classes were renamed to `EntriesConnectionsResolver` and `FormConnectionResolver`. They now properly extend `AbstractConnectionResolver`.
* Form connections now implement a `DataLoader`.
* Added `GFUtils::get_forms()` for speedy requests from $wpdb.
* Fixed various code smells.
* docs: Updated information about queries to reflect pagination and new `where` args.
* tests: WPUnit tests now extend `GFGraphQLTestCase`.
* tests: [Breaking] `WPGraphQLGravityForms\Tests` namespace has been renamed to `Tests\WPGraphQL\GravityForms`.

## v0.7.3 - WPGraphQL v1.6.x Compatibility

This release adds compatibility with WPGraphQL v1.6.x, [and its new lazy/eager type loading](https://github.com/wp-graphql/wp-graphql/releases/tag/v1.6.0).

* fix: Add `eagerlyLoadType` property to WPGraphQL type registration.
* fix: Hook type registration using `get_graphql_register_action()`.
* fix: Fix typo in `addressField.copyValueOptionsLabel` type definition.
* fix: Check entry values before (unnecessarily) updating them in SubmitGravityFormsForm mutation.
* dev: Update composer dependencies.
* tests: Clear WPGraphQL schema before/after each test.

## v0.7.2.1 - Bugfix

* (Re-released, as the last one incorectly contained the old version.)

* Fixes bug where unset `formFields` properties would cause a type error for `Enums`. (h/t @natac13)

## v0.7.1 - Bugfix

* Fixes error when filtering `gravityFormsEntries` by a value in any field (i.e. when `fieldFilters.key` is `"0"` ).

## v0.7.0 - File Uploads 🚀🚀🚀

** :warning: This release contains multiple breaking changes. **

The big highlight in this release is _experimental<sup>[\*\*\*](#uploadWarning)</sup>_ support for File Upload / Post Image submissions. We also added a feature that updates WordPress post meta when the corresponding Gravity Forms entry is updated.

We added some new WordPress filters, and changed the way some of our PHP classes work, to make it easier for people to add support for their own custom Gravity Forms fields.
We even gave the docs some love. We've added info about filters and using specific form field value inputs, and updated many of the example snippets. You can find the new documnetation in the repo's [/docs folder](/docs).

Note: These changes did necessitate refactoring many of the plugin classes, so if you're extending any of them in your own projects, make sure to update your class methods!

<a name="uploadWarning">\*\*\*</a>: File Uploads and Post Image submissions currently require [WPGraphQL Upload](https://github.com/dre1080/wp-graphql-upload) to be installed and activated. [Once WPGraphQL adds native support for multipart form requests](https://github.com/wp-graphql/wp-graphql/issues/311), it is likely that these GraphQL input values will change.

### New Features

* Added support for `FileUpload` and `PostImage` field value submissions. Ple
* Updating Post field values in a Gravity Forms entry, now also updates the corresponding WordPress post. Not even native GF lets you do that!
* New WordPress filters: `wp_graphql_gf_type_config`,      `wp_graphql_gf_connection_config`.

### Bugfixes

* `chainedSelectValues` input is now only visible if the Chained Selects plugin is active.
* The `Entry` GraphQL type now implements the `wp_graphql_gf_can_view_entries` filter.

### Under the Hood

* feat: add `altText` to `PostImage` field values.
* feat: The `pageNumber` field is now available on _all_ `formFields`.
* dev: `AbstractEnum::set_values()` has been deprecated in favor of `AbstractEnum::get_values()`.
* dev: `Button`,      `LastPageButton`,      `ConditionalLogic`,      `ConditionalLogicRule`,      `Entry`,      `EntryForm`,      `EntryUser`,      `FieldError`,      `Form`,      `FormComfirmation`,      `FormNotification`,      `FormNotificationRouting`,      `FormPagination`,      `SaveAndContinue`, now extend AbstractObject`. Their functions have changed accordingly.
* dev: Added `GFUtils::get_gravity_forms_upload_dir()` and `GUtils::handle_file_upload()`.
* dev: Added `Utils::maybe_decode_json()` and `Utils::apply_filters`.
* dev: deprecated `wp_graphql_{$enumType}_values` filter, in favor of `wp_graphql_gf_{$enumType}_values`.
* dev: deprecated `wp_graphql_gf_field_types` filter, as it no longer necessary to manually map GraphQL fields to GF `formFields`.
* dev: deprecated `wp_graphql_gf_form_field_instances` and `wp_graphql_gf_field_value_instances` filters in favor of `wp_graphql_gf_instances`.
* dev: Deprecated the `adminOnly`,  `allowsPrepopulated` and `inputName` fields on `PostImageField`.
* dev: Updated composer dependencies.
* dev!: `scr/connections` now extend `AbstractConnection`
* dev!: `src\Types\Input` now extend `AbstractInput`. Their functions have changed accordingly.
* dev!: Class `AbstractField` has been deprecated in favor of `AbstractFormField`. Its functions have changed accordingly.
* dev!: Classes `AbstractProperty`,  `AbstractFieldValue` have been deprecated in favor of `AbstractObject`. Their functions have changed accordingly.
* dev!: make plugin `$instances` static for easier access and extension.
* dev!: The `Enum`,    `InputType`,  `ValueProperty` PHP interface has been deprecated. Please update your code.
* dev!: The methods required by the `FieldValue`,  `Type` PHP interface have changed. Please update your code.
* tests: Removed `testGetEnabledFieldTypes` now that we are using `static $instances`.

## v0.6.3 - Unit Tests

* Adds support for missing date formats (dmy_dash, dmy_dot, ymd_slash, ymd_dash, ymd_dot).
* Fix: EmailInputProperty description updated.
* Dev: autocompleteAttribute has been deprecated on EmailInputProperty.
* Tests: Added `updateEntry` and `updateDraftEntry` mutations to existing `formField` tests.
* Tests: Add tests for CheckboxField, ConsentField, and DateField.

## v0.6.2.2 - Hotfix

* Fixes `submitGravityFormsForm` not saving signature field value after v0.6.2.1.

## v0.6.2.1 - Hotfix

* Fixes `updateGravityFormsEntry` not saving signature field value.

## v0.6.2 - Bugfixes

* Fixes `updateGravityFormsEntry` mutation not propery saving complex field values (h/t @natac13 )
* Fixes mutations not correctly deleting old `SignatureField` files from the server (h/t @natac13 )
* Fixes `SignatureFieldValue`s failing Gravity Forms `isRequired` validation (h/t/ @natac13).
* Fixes empty `formFields.layoutGridColumnSpan` values not returning as `null`.
* Removes deprecation from `ChainedSelectInput.name`.
* Correctly sets `graphql_connection_max_query_amount` to a minimum of `600`.
* `AbstractMutation::prepare_field_value_by_type()` no longer tries processing unrecognized fields.
* Dev: Added filter `wp_graphql_gf_prepare_field_value` for processing custom field values.
* Dev: Added filter `wp_graphql_gf_field_value_type` for adding custom field value input types.
* Dev: The arguments for AbstractMutation::validate_field_value() have changed to no longer require passing the `$form`. Passing the old set of arguments may stop working in future versions of the plugin.
* Tests: Added unit tests for `ChainedSelect` fields and values.

## v0.6.1 - Bugfix

* Fixes a fatal error when adding support for new fields with the `wp_graphql_gf_field_types` filter.

## v0.6.0 - Gravity Forms v2.5 Support

This release adds support for all the new goodies in Gravity Forms v2.5, squashes a few bugs related to Captcha fields, and refactors the `InputProperty` on various form fields.

### New Features

* Added `customRequiredIndicator`,      `markupVersion`,      `requiredIndicator`,  `validationSummary` and `version` to `GravityFormsForm` object.
* Added `layoutGridColumnSpan` and `layoutSpacerGridColumnSpan` to `formFields` interface.
* Added `enableAutocomplete` and `autocompleteAttribute` to `AddressField`,  `EmailField` ,  `NameField`,  `NumberField`,      `PhoneField`,      `SelectField`, and `TextField`.
* Added `displayOnly` property to `CaptchaField`.
* Added `allowedExtensions` and `displayAlt` property to `PostImageField`.
* Added `sort` argument for filtering `RootQueryToGravityFormsFormConnection`. _Note_: Attempting to sort on GF < v2.5.0.0 will throw a `UserError`.

### Bugfixes

* [Breaking]: Fixed the `captchaTheme` enum to use the correct possible values: `light` and `dark`.
* `captchaTheme` and `captchaType` now correctly return `null` when not set by the field.
* The `captchaType` enum now has a default value of `RECAPTCHA`.

### Under the hood

* Refactor various `InputProperty` classes. `InputDefaultValueProperty`,      `InputLabelProperty`, and `InputplaceholderProperty` have been removed for their `FieldProperty` cousins, and `EmailInputProperty` is now being used for `EmailField`.
* Tests: Clear `GFFormDisplay::$submission` between individual tests.
* Tests: Allow overriding the default field factories.
* Tests: Adds tests for `CaptchaField`.

## v0.5.0 - Add Gatsby Support, Email Confirmation, and more!

** :warning: This release contains multiple breaking changes. **

This release moves `entry.formField` values from `edges` to `nodes` , slimming down the query boilerplate and making the plugin compatible with `gatsby-source-wordpress` . We also added support for submitting an email `confirmationValue` and retrieving `PostImage` values, squashed a few bugs, and made the `wp_graphql_gf_can_view_entries` filter more useful.

We also complete removed the form/entry `fields` property. All usage should be replaced with `formFields` .

### New features

* [**Breaking**] Removed `fields` from `entry` and `form`. Please update your code to use `formFields` instead.
* [**Breaking**] Added support for submitting email confirmation values by using a new input type `FieldValuesInput.emailValues`.

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

* [**Breaking**] The `wp_graphql_gf_can_view_entries` filter now passes `entry_ids` instead of `field_ids`. This lets you do cool things like allowing authenticated users to edit _only their own_ entries:

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

* Deprecated `formFields.edges.fieldValue` in favor of `formFields.nodes.{value|typeValues}`. Not just does this dramatically slim down the boilerplate needed for your queries, but it also works with `gatsby-source-wordpress`.

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

* Added support for retrieving `PostImage` field values.

### Bugfixes

* Fixed field `id`s missing from `UpdateDraftEntry{Type}FieldValue` `errors`.
* Prevented PHP notices about missing `entry_id` when `submitGravityFormsDraftEntry fails.
* Prevented `UpdateDraftEntry{Type}FieldValue` from deleting the previously stored `ip`.
* Entry queries now correctly check for `gform_full_access` permission when `gravityforms_edit_entries` doesn't exist.
* Undeprecate `InputKeyProperty` on `name` and `address` fields. Although this is not part of the GF api, it is helpful to associate the `inputs` with their entry `values`.

### Under the hood

* Added more unit tests for `TextField`,      `TextAreaField`, and `AddressField`.
* Refactored `FieldProperty` classes to use `AbstractProperty`.

## v0.4.1 - Bugfix

* Uses `sanitize_text_field` to sanitize email values, so failing values can be validated by Gravity Forms. ( h/t @PudparK )

## v0.4.0 - A Simpler Form Submission Flow!

** :warning: This release contains multiple breaking changes. **

This release adds the `submitGravityFormsForm` mutation that submit forms without needing to use the existing `createGravityFormsDraftEntry` -> `updateDraftEntry{fieldType}Value` -> `submitGravityFormsDraftEntry` flow.

Similarly, we've added support for updating entries and draft entries with a single mutation each, and added support for using form and entry IDs in queries - without needing to convert them to a global ID first. We also deprecated `fields` in favor of `formFields` , so there's less confusion between GF fields and GraphQL fields.

Also, we made several (breaking) changes to streamline queries and mutations: many GraphQL properties have been changed to `Enum` types, and `formFields` (and the now-deprecated `fields` ) are now an interface. This should make your queries and mutations less error-prone and (a bit) more concise. We're also now using native Gravity Forms functions wherever possible, which should help ensure consistency going forward.

Beyond that, we've squashed some bugs, deprecated some confusing and unnecessary fields, and refactored a huge portion of the codebase that should speed up development and improve code quality in the long term.

### New features

* Added `submitGravityFormsForm` mutation to bypass the existing draft entry flow. See [README. MD](https://github.com/harness-software/wp-graphql-gravity-forms/README.md#documentation-submit-form-mutation) for usage.
* Added `updateGravityFormsEntry` and `updateGravityFormsDraftEntry` mutations that follow the same pattern.
* Added `idType` to `GravityFormsForm` and `GravityFormsEntry`, so you can now query them using the database ID, instead of generating a global id first.
* Added `id` property to `FieldErrors`, so you know which failed validation.
* Deprecated the `fields` property on `GravityFormsForm` and `GravityFormsEntry` in favor of `formFields`.
* Support cloning an existing entry when using `createGravityFormsDraftEntry` using the `fromEntryId` input property.
* Converted all Gravity Forms `formFields` (and the now-deprecated `fields`) to a GraphQL Interface type. That means your queries can now look like this:

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

* Switched many field types from `String` to `Enum`:
* `AddressField.addressType`
* `Button.type`
* `CaptchaField.captchaTheme`
* `CaptchaField.captchaType`
* `CaptchaField.simpleCaptchaSize`
* `ChainedSelectField.chainedSelectsAlignment`
* `ConditionalLogic.actionType`
* `ConditionalLogic.logicType`
* `ConditionalLogicRule.operator`
* `DateField.calendarIconType`
* `DateField.dateFormat`
* `DateField.dateType`
* `EntriesFieldFilterInput.operator`
* `EntriesSortingInput.direction`
* `Form.descriptionPlacement`
* `Form.labelPlacement`
* `Form.limitEntriesPeriod`
* `Form.subLabelPlacement`
* `FormConfirmation.type`
* `FormNotification.toType`
* `FormNotificationRouting.operator`
* `FormPagination.style`
* `FormPagination.type`
* `GravityFormsEntry.fieldFiltersNode`
* `GravityFormsEntry.status`
* `NumberField.numberFormat`
* `PasswordField.minPasswordStrength`
* `PhoneField.phoneFormat`
* `RootQueryToGravityFormsFormConnection.status`
* `SignatureField.borderStyle`
* `SignatureField.borderWidth`
* `TimeField.timeFormat`
* `visibilityProperty`
* FieldProperty: `descriptionPlacement`
* FieldProperty: `labelPlacement`
* FieldProperty: `sizeProperty`

### Bugfixes

* `SaveAndContinue` now uses `buttonText` instead of the `Button` type.
* `lastPageButton` now uses its own GraphQL type with the relevant fields.
* The `resumeToken` input field on the `deleteGravityFormsDraftEntry`,      `SubmitGravityFormsDraftEntry`, and all the `updateDraftEntry{fieldType}Value` mutations is now a non-nullable `String!`.
* When querying entries, we check that `createdByID` is set before trying to fetch the uerdata.
* Where possible, mutations and queries now try to return an `errors` object instead of throwing an Exception.
* We've added more descriptive `Exception` messages across the plugin, to help you figure out what went wrong.
* We fixed a type conflict with `ConsentFieldValue`. `value` now returns a `String` with the consent message, or `null` if false.
* Deprecated `url` in favor of `value` on `FileUploadFieldValue` and `SignatureFieldValue`.
* Deprecated `cssClassList` in favor of `cssClass`.
* The `resumeToken` input field on the `deleteGravityFormsDraftEntry`,      `SubmitGravityFormsDraftEntry`, and all the `updateDraftEntry{fieldType}Value` mutations is now a non-nullable `String!`.

### Under the hood

* Refactored Fields, FieldValues, and Mutations, removing over 500 lines of code and improving consistency across classes.
* Switch to using `GFAPI::submit_form()` instead of local implementations for submitting forms and draft entries.
* Implemented phpstan linting.
* Migrated unit tests to Codeception, and started backfilling missing tests.
* Updated composer dependencies.

## v0.3.1 - Bugfixes

* Removes `abstract` class definition from FieldProperty classes. (#79)
* `ConsentFieldValue`: The `value` field was a conflicting type `Boolean`. Now it correctly returns a `String` with the consent message. ( #80 )
* `FormNotificationRouting`: The `fieldId` now correctly returns an `Int` instead of a `String`. (#81)
* When checking for missing `GravityFormsForm` values,      `limitEntriesCount`,  `scheduleEndHour` and `scheduleEndMinute` now correctly return as type `Int` (#83)

## v0.3.0 - Add Missing Mutations, the Consent Field, and more!

This release focuses on adding in missing mutations for existing form field - including those needed for Post Creation. We also added support for the Consent field, and squashed some bugs.

### New Field Support: Consent

* Added `consentField` and `updateDraftEntryConsentFieldValue`.

### New Field Mutations

* Added `updateDraftEntryChainedSelectFieldValue` mutation.
* Added `updateDraftEntryHiddenFieldValue` mutation.
* Added `updateDraftEntryPostCategoryFieldValue` mutation.
* Added `updateDraftEntryPostContentFieldValue` mutation.
* Added `updateDraftEntryPostCustomFieldValue` mutation.
* Added `updateDraftEntryPostTagsFieldValue` mutation.
* Added `updateDraftEntryPostTitleFieldValue` mutation.

### Added Field Properties

* Added the `isHidden` property to `PasswordInput`.

### Bugfixes

* Changed the way we were saving the `listField` values for both single- and multi-column setups, so GF can read them correctly.
* Fix a bug where a PHP Notice would be thrown when no `listField` value was submitted - even if the field was not required.
* Fixed a bug that was preventing unused draft signature images from getting deleted.
* Updated how we retrieve the signature url so we're no longer using deprecated functions.

### Misc.

* Renamed `ListInput` and `ListFieldValue` properties to something more sensical. `input.value.values` is now `input.value.rowValues`, and `fieldValue.listValues.value` is now `fieldValue.listValues.values`. The old property names will continue to work until further notice.
* Updated composer dependencies.

## v0.2.0 - Add / Deprecate Field Properties

This release focuses on adding in missing properties on the existing form fields, and deprecating any properties that aren't used by Gravity Forms.

### Added field properties:

* Adds following properties to the Address field: `descriptionPlacement`,      `subLabelPlacement`,      `copyValuesOptionDefault`,      `copyValuesOptionField`,      `copyValuesOptionLabel`,      `enableCopyValuesOption`.
* Adds following subproperties to the Address `inputs` property: `customLabel`,      `defaultValue`,      `placeholder`.
* Adds following properties to the Captcha field: `descriptionPlacement`,      `description`,      `size`,      `captchaType`.
* Adds following properties to the ChainedSelect field: `descriptionPlacement`,      `description`,      `noDuplicates`,      `subLabel`.
* Adds following properties to the Checkbox field: `descriptionPlacement`,      `enablePrice`.
* Adds following properties to the Date field: `descriptionPlacement`,      `inputs`,      `subLabel`,      `dateType`.
* Adds following properties to the Email field: `descriptionPlacement`,      `inputs`,      `subLabel`,      `emailConfirmEnabled`.
* Adds following properties to the File Upload field: `descriptionPlacement`.
* Adds following properties to the Hidden field: `allowsPrepopulate`.
* Adds following properties to the HTML field: `displayOnly`,      `disableMargins`.
* Adds following properties to the Name field: `descriptionPlacement`,      `subLabelPlacement`.
* Adds following subproperties to the Name field `inputs` property: `customLabel`,      `defaultValue`,      `placeholder`,      `choices`,      `enableChoiceValue`.
* Adds following properties to the Number field: `descriptionPlacement`,      `calculationFormula`,      `calculationRounding`,      `enableCalculation`.
* Adds following properties to the Page field: `displayOnly`,      `size`.
* Adds following properties to the Password field: `subLabelPlacement`.
* Adds following properties to the Phone field: `descriptionPlacement`.
* Adds following properties to the Post Category field: `descriptionPlacement`,      `noDuplicates`,      `placeholder`.
* Adds following properties to the Post Content: `descriptionPlacement`,      `maxLength`.
* Adds following properties to the Post Custom field: `descriptionPlacement`,      `maxLength`.
* Adds following properties to the Post Excerpt field: `descriptionPlacement`,      `maxLength`.
* Adds following properties to the Post Image field: `descriptionPlacement`.
* Adds following properties to the Post Tags field: `descriptionPlacement`,      `enableSelectAll`,      `maxLength`.
* Adds following properties to the Post Title field: `descriptionPlacement`.
* Adds following properties to the Radio field: `descriptionPlacement`,      `enablePrice`.
* Adds following properties to the Section field: `descriptionPlacement`,  `displayOnly`.
* Adds following properties to the Select field: `defaultValue`,  `descriptionPlacement`,      `enablePrice`.
* Adds following properties to the Signature field: `descriptionPlacement`.
* Adds following properties to the TextArea field: `descriptionPlacement`,      `useRichTextEditor`.
* Adds following properties to the Text field: `descriptionPlacement`.
* Adds following properties to the Time field: `descriptionPlacement`,      `inputs`,      `subLabelPlacement`.
* Adds following properties to the Time field: `descriptionPlacement`.

### Deprecated Field Properties

These properties will be removed in v1.0.

* Deprecate the following properties from the Address field: `inputName`.
* Deprecate the following properties from the Captcha field: `adminLabel`,  `adminOnly`,  `allowsPrepopulate`,  `visibility`.
* Deprecate the following properties from the File Upload field: `allowsPrepopulate`,  `inputName`.
* Deprecate the following properties from the Hidden field: `adminLabel`,  `adminOnly`,      `isRequired`,      `noDuplicates`,  `visibility`.
* Deprecate the following properties from the HTML field: `adminLabel`,  `adminOnly`,  `allowsPrepopulate`,  `inputName`,  `visibility`.
* Deprecate the following properties from the Name field: `inputName`.
* Deprecate the following properties from the Page field: `adminLabel`,  `adminOnly`,  `allowsPrepopulate`,      `label`,  `visibility`.
* Deprecate the following properties from the Password field: `allowsPrepopulate`,  `visibility`.
* Deprecate the following properties from the Section field: `adminLabel`,  `adminOnly`,  `allowsPrepopulate`.
* Deprecate the `key` property on all field `inputs` properties.
* Deprecate the following properties from the Chained Select field `inputs` property: `isHidden`,      `name`.

### Misc.

* [Bugfix] Fix saving draft submission with wrong `gform_unique_id` when none is set.
* [PHPCS] Various docblock and comment fixes.

## v0.1.0 - Initial Public Release

This release takes the last year and a half of work on this plugin, and makes it ready for public consumption and contribution, by adopting SemVer, WordPress Coding Standards, etc.

Please see [README.md](https://github.com/harness-software/wp-graphql-gravity-forms/blob/main/README.md) for a list of all functionality.
