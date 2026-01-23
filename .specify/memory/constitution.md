<!--
Sync Impact Report:
- Version: 1.0.0 (Initial Ratification)
- Added Principles: I. Strict Static Typing, II. WordPress Coding Standards (WPCS), III. WPGraphQL Architecture, IV. Testing Discipline
- Templates: Compatible (generic references in templates)
- TODOs: None
-->
# WPGraphQL for Gravity Forms Constitution

## Core Principles

### I. Strict Static Typing
All code must adhere to strict static analysis rules. The project enforces **PHPStan Level 8**. This is non-negotiable.
- Type safety prevents runtime errors and documents intent better than comments.
- Explicitly define return types and property types.
- Avoid `mixed` types where possible; use generics in PHPDoc tags (e.g., `array<string, int>`) to ensure clarity and precision.
- "Trust but verify" - cast legacy WordPress returns to strict types at the boundary.

### II. WordPress Coding Standards (WPCS)
Code must strictly follow the project's defined standards (based on `axepress/wp-graphql-cs`).
- **Linting**: Code must pass `phpcs` without errors before merge.
- **Formatting**: Use `phpcbf` to automate formatting compliance.
- **Naming**: Follow WordPress naming conventions (snake_case for variables/functions, PascalCase for classes).
- **Documentation**: PHPDoc blocks are required for all functions, classes, and methods, following WP conventions.

### III. WPGraphQL Architecture
Extensions must integrate seamlessly with the WPGraphQL schema and internal architecture.
- **Schema Registration**: Use standard WPGraphQL registration functions (`register_graphql_object_type`, `register_graphql_connection`, `register_graphql_mutation`).
- **Data Loading**: **MUST** use the Dataloader pattern (via `Data\Loader` classes) for fetching data to prevent N+1 performance issues.
- **Connections**: Implement `AbstractConnection` or use standard connection helpers for relay-spec compliant pagination.
- **Interfaces**: Use GraphQL Interfaces where polymorphism is appropriate to allow flexible queries.

### IV. Testing Discipline
Quality is ensured through a mix of acceptance, integration, and unit tests.
- **Integration First**: Focus on `wp-browser` and Codeception integration tests that verify the actual GraphQL response against WordPress state.
- **Unit Tests**: Use unit tests for complex, isolated logic (e.g., data transformation, utility functions).
- **Reliability**: Tests must be deterministic. Flaky tests are considered broken.
- **Coverage**: New features must include tests covering happy paths and critical error states.

## Security & Performance

### Security Requirements
- **Sanitization & Escaping**: All inputs must be sanitized; all outputs must be escaped (though GraphQL handles much of the output escaping, custom logic must be safe).
- **Capabilities**: All Mutations and sensitive Queries must explicitly check user capabilities (e.g., `current_user_can()`).

### Performance Standards
- **N+1 Prevention**: Strict adherence to the Dataloader pattern.
- **Query Complexity**: Schema design should avoid encouraging excessively deep or expensive queries.

## Development Workflow

### Code Review Gates
- **Automated Checks**: CI must pass (Lint, PHPStan, Tests) before review.
- **Manual Review**: At least one approval required from a maintainer.
- **Schema Review**: Changes to the GraphQL schema (breaking or additive) require specific scrutiny to ensure backward compatibility and API cleanliness.

## Governance

This Constitution serves as the primary source of truth for engineering decisions.
- **Amendments**: Changes to this document require a Pull Request and consensus among core maintainers.
- **Versioning**: This document follows Semantic Versioning.
    - **MAJOR**: Adding/Removing core principles or changing strictness levels (e.g., PHPStan level).
    - **MINOR**: Adding clarifications or new sections.
    - **PATCH**: Typos or non-semantic formatting.
- **Compliance**: All new code and refactors must adhere to these principles. "It was already like that" is not a valid excuse for introducing new violations in touched files.

**Version**: 1.0.0 | **Ratified**: 2026-01-23 | **Last Amended**: 2026-01-23