# Changelog

## v0.4.0 - DOGFOOD @TODO

- Adds `submitGravityFormsForm` mutation to bypass the existing draft entry flow. E.g. :
```graphql
  submitGravityFormsForm(
		input: {
			formId: 50, 
			clientMutationId: "uniqueMutationId", 
			fieldValues: [
				{	# simple values
					id: 1,
					value: "a"
				},
				{ # NameField value
					id: 9,
					nameValues: {
						first: "David",
						last: "Levine"
					}
				},
				{ # ListField value
					id: 20,
					listValues: [
						{ rowValues: ["a", "b", "c"] }
					]
				}
			],
			saveAsDraft: true
		}
	) {
		entryId
		errors {
			id
			message
		}
		resumeToken
		resumeUrl
	}

```
- Adds `idType` to `GravityFormsForm` and `GravityFormsEntry`, so you can now query them using the database ID, instead of generating a global id first.
- Adds support for updating existing entries. Specifically:
 - `createGravityFormsDraftEntry` now has an optional `fromEntryId` input field. If set, the draft entry will use the id to clone a new draft entry.
 - `submitGravityFormsDraftEntry` now has an optional `forceCreate` input field. If `true`, a new entry will be created. If `false` or unset, the mutation will check if the draft entry was created from an existing entry and replace it. Draft entries created without `fromEntryId` will continue to generate new Gravity Forms Entries.
- Converts all Gravity Forms `fields` to an interface. That means your queries can now look like this:
```graphql
query{
	gravityFormsForms {
			nodes {
				fields {
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
@TODO: consider making certain properties global to the interface, even if they return null for most fields.

- Switch many field types from `String` to `Enum`: 
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
- Fix ConsentFieldValue conflict. `value` now returns a `String` with the consent message, or `null` if false.
- Deprecated `url` in favor of `value` on `FileUploadFieldValue` and `SignatureFieldValue`.
- Add basic codeception tests.

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
