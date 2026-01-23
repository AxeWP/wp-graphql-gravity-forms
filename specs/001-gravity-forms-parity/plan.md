# Implementation Plan: GF 2.9 Parity

**Branch**: `001-gravity-forms-parity` | **Date**: 2026-01-23 | **Spec**: [specs/001-gravity-forms-parity/spec.md](specs/001-gravity-forms-parity/spec.md)
**Input**: Feature specification from `specs/001-gravity-forms-parity/spec.md`
**Methodology**: [Ralph Loop](https://github.com/ClaytonFarr/ralph-playbook) for autonomous task execution

---

## Ralph Loop Overview

This feature uses the Ralph Loop methodology for autonomous, iterative implementation:

### Files Structure
```
wp-graphql-gravity-forms/
├── loop.sh                      # Loop orchestrator (start here)
├── PROMPT_plan.md               # Planning mode instructions
├── PROMPT_build.md              # Building mode instructions
├── AGENTS.md                    # Operational guide (how to run tests)
├── IMPLEMENTATION_PLAN.md       # Active task list (disposable, managed by Ralph)
├── PRD.md                       # Master field inventory (46 fields)
├── specs/001-gravity-forms-parity/
│   ├── spec.md                  # Requirements & acceptance criteria
│   ├── plan.md                  # This file (methodology)
│   ├── data-model.md            # GraphQL schema mappings
│   ├── research.md              # Audit findings
│   └── tasks.md                 # Execution guide
└── src/, tests/                 # Implementation & test code
```

### Execution Flow

**Phase 1: Planning Mode** (Run First)
```bash
./loop.sh plan 5
```
- Analyzes specs, existing code, and GF field classes
- Identifies gaps (missing tests, incomplete tests, missing schema)
- Generates/updates `IMPLEMENTATION_PLAN.md` with prioritized tasks
- No implementation, just gap analysis

**Phase 2: Building Mode** (Run Second)
```bash
./loop.sh 20
```
- Picks most important task from `IMPLEMENTATION_PLAN.md`
- Implements (creates tests, adds schema, etc.)
- Runs tests as backpressure
- Commits on success, updates plan
- Repeats for next task (20 iterations max in example)

**Phase 3: Regenerate Plan** (As Needed)
```bash
./loop.sh plan
```
- If plan becomes stale, wrong, or cluttered with completed items
- Regenerate fresh plan based on current code state
- Plan is disposable - regeneration is cheap

---

## Summary

The goal is to achieve full parity with Gravity Forms 2.9.x. The approach is **Audit-First**: for every supported field, we will compare the GraphQL query in the existing `wpunit` test against the GF 2.9 field class properties. Gaps identified during this audit will be implemented by adding fields to the schema and corresponding assertions to the tests.

**Key Principle**: One field at a time. Ralph picks the next most important field, implements complete support (all 4 mutations, all properties), tests pass, commits, moves to next field. Fresh context each iteration.

## Technical Context

**Language/Version**: PHP 7.4+
**Primary Dependencies**: 
- `gravityforms` (v2.9.x)
- `wp-graphql`
**Testing**: Codeception (`wpunit`)
**Project Type**: WordPress Plugin Extension

## Constitution Check

- **I. Strict Static Typing**: PHPStan Level 8.
- **II. WPCS**: `npm run lint:php`.
- **III. WPGraphQL Architecture**: Use Interfaces for shared field settings.
- **IV. Testing Discipline**: 100% pass rate on `tests/wpunit/*FieldTest.php`.

## Project Structure

### Documentation (this feature)

```text
specs/001-gravity-forms-parity/
├── plan.md              # This file
├── research.md          # Audit findings and gaps
├── data-model.md        # Mapping of GF Fields to GraphQL Types
├── quickstart.md        # How to run parity audits
├── contracts/           # GraphQL Schema definitions
├── checklists/
│   └── field-parity.md  # Tracking progress per field
└── tasks.md             # Validation & Implementation Tasks
```

### Source Code

```text
src/
├── Registry/
│   ├── FormFieldRegistry.php # Field registration logic
│   └── TypeRegistry.php      # Setting-to-Interface mapping
├── Type/
│   └── WPInterface/
│       └── FieldSetting/     # Reusable interfaces for settings
└── Data/
    └── FieldValueInput/      # Submission handling logic

tests/wpunit/
└── *FieldTest.php            # Primary source for validation
```

**Structure Decision**: Leverage and expand the existing interface-driven registration system in `FormFieldRegistry`. Focus on adding new interfaces for missing GF 2.9 settings.

## Complexity Tracking

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| N/A | | |
