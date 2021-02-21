# Changelog

## v0.2.0 - Add / Deprecate Field Properties

This release focusds on adding in missing properties on the existing form fields, and deprecating any properties that aren't used by Gravity Forms.

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
