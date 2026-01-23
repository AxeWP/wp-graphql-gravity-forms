# Feature Specification: Update WPGraphQL Gravity Forms for GF 2.9 Parity

**Feature Branch**: `001-gravity-forms-parity`
**Created**: Friday, January 23, 2026
**Status**: Draft
**Input**: User description: "We want to make sure our WPGraphQL for Gravity Form Field have full parity and support for all the lastest Gravity Forms field properties, submission and validation shapes, and types. Even missing fields if we dont support them yet. The plugin was created for Gravity Forms 2.6, but with the latest GF 2.9.x there's a lot of new features to look at in @tests/_data/plugins/gravityforms/includes/fields/"

## Clarifications

### Session 2026-01-23
- Q: Which deprecated fields should be supported (Legacy Support)? → A: Support all fields currently in `wp-graphql-gravity-forms` OR present in Gravity Forms 2.6+, ensuring backwards compatibility.
- Q: How should validation errors be reported in mutations? → A: Follow existing `wp-graphql-gravity-forms` behavior by returning structured errors within the mutation payload (e.g., `errors` field).
- Q: How should field properties (appearance, rules) be mapped to the schema? → A: Use standardized, reusable GraphQL types for shared properties across field types; always check if a suitable type already exists before creating a new one.
- Q: How should new Gravity Forms 2.9 settings be integrated? → A: Identify new settings in the GF 2.9 codebase and register corresponding new GraphQL interfaces/types in `GFTypeRegistry`.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Querying New Gravity Forms 2.9 Fields (Priority: P1)

Developers need to be able to query the schema of forms created with Gravity Forms 2.9.x to build headless applications that render these forms correctly. This includes receiving the correct field types and all associated properties (label, description, rules, appearance settings) for fields introduced or updated since GF 2.6.

**Why this priority**: Without this, headless applications cannot render forms using the latest Gravity Forms features, breaking the core promise of the plugin.

**Independent Test**: Can be tested by creating a form in WP Admin with new GF 2.9 field types (and existing fields with new settings) and verifying the GraphQL schema query returns all expected data.

**Acceptance Scenarios**:

1. **Given** a Gravity Form containing a field type introduced in GF 2.9 (e.g., a new advanced field), **When** a developer queries the form's `formFields` via GraphQL, **Then** the response includes a specific GraphQL Type for that field (not a generic fallback) with all its configuration data.
2. **Given** a standard field that has new setting properties in GF 2.9, **When** a developer queries that field via GraphQL, **Then** the response includes fields for those new properties reflecting the values set in the WP Admin.
3. **Given** a form with fields using new GF 2.9 validation rules, **When** the form schema is queried, **Then** the validation rules are exposed in the schema so the frontend can implement client-side validation parity.

---

### User Story 2 - Submitting Forms with New Fields (Priority: P1)

End-users need to be able to submit forms containing any standard Gravity Forms 2.9 field type, and have that data correctly saved in WordPress.

**Why this priority**: Displaying the form is only half the battle; capturing data is the primary business value.

**Independent Test**: Can be tested by submitting a mutation with valid data for a form containing new field types and verifying the entry is created in the Gravity Forms backend.

**Acceptance Scenarios**:

1. **Given** a form with a new GF 2.9 field type, **When** a user submits a mutation with valid data for that field, **Then** the mutation returns success and the data is correctly stored in the Entry.
2. **Given** a complex field type (e.g., multi-input field) that changed structure in GF 2.9, **When** a user submits data matching the new structure, **Then** the submission is successful.

---

### User Story 3 - Server-Side Validation Parity (Priority: P2)

Developers rely on the API to enforce data integrity. The GraphQL API must return validation errors that match the behavior of a standard Gravity Forms submission, including new validation rules from GF 2.9.

**Why this priority**: Ensures data quality and provides feedback to users when their input is incorrect.

**Independent Test**: Can be tested by submitting invalid data to a form and comparing the GraphQL error response with the errors shown in a standard WP theme form submission.

**Acceptance Scenarios**:

1. **Given** a form with a field having a GF 2.9 specific validation rule (e.g., strict format check), **When** a user submits data violating that rule, **Then** the mutation returns a validation error matching the standard Gravity Forms error message.
2. **Given** a required field introduced in GF 2.9, **When** a user submits a mutation omitting that field, **Then** a "field is required" error is returned.

### Edge Cases

- **Deprecated Fields**: The system MUST support fields if they were in GF 2.6+ OR are currently supported, even if deprecated in GF 2.9, ensuring backwards compatibility for legacy forms.
- How does the system handle 3rd-party addon fields? (Scope is limited to standard GF fields, but should not crash on unknown types).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST support all standard field types present in Gravity Forms 2.9.x codebase (specifically verifying against `includes/fields`).
- **FR-002**: System MUST expose a specific GraphQL Type for each supported field type, implementing appropriate interfaces (e.g., `FormField`, `FieldWithChoices`).
- **FR-003**: System MUST map all public properties of GF 2.9 fields to the GraphQL schema, including new appearance and behavior settings.
- **FR-004**: System MUST provide input fields in the submission mutation that correspond to the data structure expected by GF 2.9 for each field type.
- **FR-005**: System MUST execute Gravity Forms validation logic during submission and return structured errors for any failures within the mutation payload (e.g., `errors` field).
- **FR-006**: System MUST support "missing" fields that were standard in GF 2.6 but not previously implemented in WPGraphQL, ensuring full coverage.
- **FR-007**: System MUST maintain support for deprecated fields found in GF 2.6 or the existing plugin to prevent breaking changes.
- **FR-008**: System MUST prioritize the reuse of existing GraphQL types for shared field properties (e.g., visibility, validation rules) before creating new types.
- **FR-009**: System MUST register new GraphQL interfaces in `GFTypeRegistry` for any new field settings introduced in Gravity Forms 2.9.
- **FR-010**: System MUST include corresponding tests for every `GF_Field` class present in `tests/_data/plugins/gravityforms/includes/fields/`, ensuring all 4 mutation types are covered (Submit, Update, SubmitDraft, UpdateDraft).

### Key Entities

- **FormField**: Represents a single field in a form. Needs to be polymorphic to handle different field types (Text, Number, Date, etc.).
- **FieldSubmissionInput**: The input object used in mutations to pass data for a specific field.
- **Entry**: The record created upon successful form submission.
- **ValidationError**: Represents a validation failure, including the field ID and error message.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: 100% of standard field types found in Gravity Forms 2.9.x `includes/fields` directory are represented in the GraphQL Schema.
- **SC-002**: Submitting a form with every supported field type results in a valid Entry in the database with no data loss.
- **SC-003**: GraphQL validation errors are identical (message and field association) to standard Gravity Forms validation errors for 100% of tested validation rules.
- **SC-004**: 100% of standard field types (as listed in PRD inventory) have a dedicated test class extending `FormFieldTestCase`.
- **SC-005**: All dedicated field tests pass for all 4 mutation scenarios (Submit, Update, SubmitDraft, UpdateDraft).