0b. Study @IMPLEMENTATION_PLAN.md to what's been done.
0b. Study @PRD.md to understand the complete field inventory.
0c. Study @AGENTS.md to understand how to run tests and validate changes.
0d. For reference, the application source code is in `src/*` and test code is in `tests/*`. Gravity Forms source of truth is in `tests/_data/plugins/gravityforms/` (the fields are in `tests/_data/plugins/gravityforms/includes/fields/`).

1. Your task is to implement functionality per the specifications using parallel subagents. Follow @PRD.md and choose the most important item to address (hint: the most important item might not be the first item in the list). CHOOSE ONLY ONE ITEM TO WORK ON AT A TIME. Before making changes, search the codebase (don't assume not implemented) using subagents. You may use up to 500 parallel subagents for searches/reads and only 1  subagent for build/tests. Use thinking subagents when complex reasoning is needed (debugging, architectural decisions).

2. After implementing functionality or resolving problems, run the tests per @AGENTS.md for that unit of code that was improved. If functionality is missing then it's your job to add it as per the specifications. Ultrathink.

3. When you discover issues, immediately update @IMPLEMENTATION_PLAN.md with your findings using a subagent. When resolved, update and remove the item.

4. When the tests pass, update @ =LEARNINGS.md and @PRD.md (mark field as [x] if complete), then `git add -A` then `git commit` with a message describing the changes. After the commit, `git push`.

99999. Important: When authoring documentation, capture the why — tests and implementation importance.

999999. Important: Single sources of truth, no migrations/adapters. If tests unrelated to your work fail, resolve them as part of the increment.

9999999. You may add extra logging if required to debug issues.

99999999. Keep @IMPLEMENTATION_PLAN.md current with learnings using a subagent — future work depends on this to avoid duplicating efforts. Update especially after finishing your turn.

999999999. When you learn something new about how to run the application or tests, update @AGENTS.md using a subagent but keep it brief. For example if you run commands multiple times before learning the correct command then that file should be updated.

9999999999. For any bugs you notice, resolve them or document them in @IMPLEMENTATION_PLAN.md using a subagent even if it is unrelated to the current piece of work.

99999999999. Implement functionality completely. Placeholders and stubs waste efforts and time redoing the same work.

999999999999. When @IMPLEMENTATION_PLAN.md becomes large periodically clean out the items that are completed from the file using a subagent.

9999999999999. If you find inconsistencies in the specs/* then use an   thinking 4.5 subagent with 'ultrathink' requested to update the specs.

99999999999999. IMPORTANT: Keep @AGENTS.md operational only — status updates and progress notes belong in @IMPLEMENTATION_PLAN.md. A bloated AGENTS.md pollutes every future loop's context.

999999999999999. CRITICAL: Every field must have all 4 mutation tests passing (Submit, Update, SubmitDraft, UpdateDraft). A field is NOT complete until all 4 tests pass. This is the acceptance criteria per the spec.

9999999999999999. When creating or updating field tests, always extend `FormFieldTestCase` which provides the standard 4-mutation test structure. Verify field-specific properties, not just generic value assertions.
