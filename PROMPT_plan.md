0a. Study `specs/001-gravity-forms-parity/*` with up to 250 parallel Sonnet subagents to learn the GF 2.9 parity specifications.
0b. Study @IMPLEMENTATION_PLAN.md (if present) to understand the plan so far.
0c. Study `src/Registry/FormFieldRegistry.php` and `src/Type/WPInterface/FieldSetting/` with up to 250 parallel Sonnet subagents to understand the current schema registration patterns.
0d. Study `tests/wpunit/*FieldTest.php` with up to 250 parallel Sonnet subagents to understand current test coverage.
0e. Study @PRD.md to understand the complete inventory of 46 Gravity Forms fields.
0f. For reference, the application source code is in `src/*` and test code is in `tests/*`.

1. Study @IMPLEMENTATION_PLAN.md (if present; it may be incorrect) and use up to 500 Sonnet subagents to study existing test files in `tests/wpunit/` and source code in `src/*` and compare them against the GF 2.9 field specifications in `tests/_data/plugins/gravityforms/includes/fields/` and against @PRD.md. Use a subagent to analyze findings, prioritize tasks, and create/update @IMPLEMENTATION_PLAN.md as a bullet point list sorted in priority of items yet to be implemented. Ultrathink. Consider:
   - Which fields have no test files at all (critical gaps)
   - Which fields have incomplete tests (missing mutation types)
   - Which fields have tests that don't verify field-specific properties
   - Which GF 2.9 properties are missing from GraphQL schema
   - Search for TODO, minimal implementations, placeholders, skipped/flaky tests
   - Inconsistent patterns between field implementations

Study @IMPLEMENTATION_PLAN.md to determine starting point for research and keep it up to date with items considered complete/incomplete using subagents.

IMPORTANT: Plan only. Do NOT implement anything. Do NOT assume functionality is missing; confirm with code search first. Treat `src/Type/WPInterface/FieldSetting/` as the project's standard library for shared field properties. Prefer consolidated, idiomatic interface implementations there over per-field duplication.

ULTIMATE GOAL: We want to achieve 100% verified coverage where every supported Gravity Forms field: (1) Exists in the Schema, (2) Resolves correctly in queries, (3) Mutates correctly with all 4 mutation types (Submit, Update, SubmitDraft, UpdateDraft), and (4) Has comprehensive dedicated test coverage. Consider missing elements and plan accordingly. If an element is missing, search first to confirm it doesn't exist, then if needed document the plan to implement it in @IMPLEMENTATION_PLAN.md using a subagent.
