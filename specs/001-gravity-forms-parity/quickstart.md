# Quickstart

## Prerequisites
- Local WordPress environment with `wp-graphql` and `gravityforms` enabled.
- Database configured in `.env.testing`.

## Running Parity Tests

We use a "One-by-One" approach.

### 1. Run All Field Tests
```bash
npm run test:codecept run wpunit tests/wpunit/Fields/
```

### 2. Run Specific Field Test
```bash
npm run test:codecept run wpunit tests/wpunit/Fields/TextFieldTest.php
```

## Development Workflow

1.  Pick a field from `tests/_data/plugins/gravityforms/includes/fields/`.
2.  Generate/Update its test in `tests/wpunit/Fields/`.
3.  Run the test.
4.  Implement missing schema in `src/`.
5.  Repeat.
