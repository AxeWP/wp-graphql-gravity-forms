# Research: Gravity Forms 2.9 Parity

**Date**: 2026-01-23
**Goal**: Audit existing WPGraphQL Gravity Forms field support against Gravity Forms 2.9 properties.

## Methodology

1.  **Inventory**: Use `tests/_data/plugins/gravityforms/includes/fields/` as the source of truth for GF 2.9 field properties.
2.  **Test Audit**: Compare existing `tests/wpunit/*FieldTest.php` query strings against the properties in the GF 2.9 field classes.
3.  **Validation**: Run existing tests as a baseline. Any property present in GF 2.9 but missing from the test query is a potential gap.

## Findings from Audit

### Common Fields (Base GF_Field)
- `visibility`: Found in `src/Type/WPInterface/FormField.php` and queried in `tests/_support/TestCase/FormFieldTestCase.php`. **Status: Supported**.
- `personalData`: Handled via `FieldWithPersonalData` interface. **Status: Supported**.

### Address Field (`GF_Field_Address`)
- `default_input_values_setting` / `input_placeholders_setting`: 
    - **Current Test Coverage**: `AddressFieldTest.php` already queries `inputs { defaultValue, placeholder }`.
    - **Mapping**: These editor settings map to the `inputs` property in the GF field object, which is already exposed.
    - **Status**: Likely supported, but needs verification with non-random values.

### Text Field (`GF_Field_Text`)
- `password_field_setting`: Mapped to `isPasswordInput` in `TextFieldTest.php`. **Status: Supported**.
- `maxLength`: Supported.

### Potential Gaps (To be verified)
- **Repeater Field**: `GF_Field_Repeater` is present in GF 2.9. Need to check if a corresponding `RepeaterFieldTest.php` and schema exists.
- **CAPTCHA v3**: Check if new v3 settings are exposed.

## Plan for Validation Tasks
The task list will focus on "Adding missing properties to existing test queries" rather than creating new tests from scratch. If adding a property to a query causes a GraphQL error (Field not found), then an implementation task is triggered.