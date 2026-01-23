# Data Model & Schema Mapping

## Core Entity: FormField

All fields implement the `FormField` Interface.

```graphql
interface FormField {
  id: ID!
  type: String!
  label: String
  description: String
  cssClass: String
  visibility: FormFieldVisibilityEnum
  isRequired: Boolean
  # ... other common properties
}
```

## Field: Text (Single Line Text)

**Class**: `GF_Field_Text`
**GraphQL Type**: `TextField`

### Settings Mapping

| GF Setting | GraphQL Interface | Description |
|------------|-------------------|-------------|
| `maxlen_setting` | `FieldWithMaxLength` | `maxLength: Int` |
| `input_mask_setting` | `FieldWithInputMask` | `inputMask: String` |
| `password_field_setting` | `FieldWithPasswordField` | `isPasswordInput: Boolean` |
| `visibility_setting` | `FieldWithVisibility` | **New**: `visibility: FormFieldVisibilityEnum` (if not on base) |

### Validation Rules

- `maxLength`: Enforced on submission.
- `isRequired`: Enforced on submission.

## Field: Address

**Class**: `GF_Field_Address`
**GraphQL Type**: `AddressField`

### Settings Mapping

| GF Setting | GraphQL Interface | Description |
|------------|-------------------|-------------|
| `address_setting` | `FieldWithAddress` | `addressType: AddressFieldTypeEnum`, `defaultState: String`, etc. |
| `default_input_values_setting` | `FieldWithDefaultInputValues` | **New**: `defaultInputValues: [FieldValueInput]` |
| `input_placeholders_setting` | `FieldWithInputPlaceholders` | **New**: `inputPlaceholders: [FieldValueInput]` |

### Inputs

- `AddressFieldInput`:
    - `street: String`
    - `lineTwo: String`
    - `city: String`
    - `state: String`
    - `zip: String`
    - `country: AddressFieldCountryEnum`
