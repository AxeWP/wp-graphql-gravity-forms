# Tasks: GF 2.9 Parity Validation & Implementation

**Spec**: [specs/001-gravity-forms-parity/spec.md](specs/001-gravity-forms-parity/spec.md)
**Plan**: [specs/001-gravity-forms-parity/plan.md](specs/001-gravity-forms-parity/plan.md)
**PRD**: [PRD.md](../../PRD.md) - Complete inventory of 46 fields
**Implementation Plan**: [IMPLEMENTATION_PLAN.md](../../IMPLEMENTATION_PLAN.md) - Active task list for Ralph loop

---

## Ralph Loop Execution

This feature uses the **Ralph Loop** methodology for autonomous task execution:

### 1. Planning Mode (Run First)
Generate the implementation plan by analyzing specs, current code, and identifying gaps:

```bash
./loop.sh plan 5
```

This will:
- Study all spec files in `specs/001-gravity-forms-parity/`
- Audit existing tests in `tests/wpunit/`
- Compare against GF field classes in `tests/_data/plugins/gravityforms/includes/fields/`
- Generate/update `IMPLEMENTATION_PLAN.md` with prioritized tasks

### 2. Building Mode (Run Second)
Execute tasks from the implementation plan:

```bash
./loop.sh 20
```

This will:
- Pick the most important task from `IMPLEMENTATION_PLAN.md`
- Implement the task (create/update tests, schema, etc.)
- Run tests to verify (`npm run test:codecept run wpunit`)
- Commit and push on success
- Update `IMPLEMENTATION_PLAN.md` with progress
- Repeat for next task (up to 20 iterations in this example)

### 3. Manual Verification
Review progress and test results:

```bash
# Run full test suite
npm run test:codecept run wpunit

# Run specific field test
npm run test:codecept run wpunit tests/wpunit/TextFieldTest.php

# Check linting
npm run lint:php
```

---

## Task Structure (Reference - Managed by Ralph)

The actual tasks are in `IMPLEMENTATION_PLAN.md` which Ralph manages autonomously. This section documents the overall approach:

### Phase 1: Field Audit (Per Field)
For each of the 46 fields in PRD.md:
1. Check if test file exists
2. Verify test extends `FormFieldTestCase`
3. Verify all 4 mutations tested (Submit, Update, SubmitDraft, UpdateDraft)
4. Verify test queries all GF 2.9 properties
5. Verify test has field-specific assertions

**Acceptance**: Test file exists, all 4 mutations pass, field-specific properties verified

### Phase 2: Gap Implementation (As Needed)
When audit identifies missing functionality:
1. Create missing test file (if doesn't exist)
2. Add missing GraphQL interfaces to `src/Type/WPInterface/FieldSetting/`
3. Register interfaces in `src/Registry/TypeRegistry.php`
4. Update `FieldValueInput` handlers if needed
5. Re-run tests until pass

**Acceptance**: Tests pass, linting passes, no placeholders

### Phase 3: Validation
After all fields complete:
1. Run full test suite: `npm run test:codecept run wpunit`
2. Run linting: `npm run lint:php`
3. Update PRD.md with [x] for completed fields

**Acceptance**: 100% test pass rate, zero lint errors, PRD.md complete

---

## Key Files for Ralph

- **IMPLEMENTATION_PLAN.md** - The active task list (disposable, regenerate anytime)
- **AGENTS.md** - How to run tests and validate (operational guide)
- **PROMPT_plan.md** - Instructions for planning mode
- **PROMPT_build.md** - Instructions for building mode
- **loop.sh** - The loop orchestrator
- **PRD.md** - Master field inventory (46 fields)
- **specs/001-gravity-forms-parity/*** - Feature specifications

Ralph reads these files on every loop iteration to understand context and pick next task.
