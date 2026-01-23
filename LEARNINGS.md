# Learnings: WPGraphQL Gravity Forms Implementation

## ImageChoice Field Implementation (2026-01-23)

**Issue**: ImageChoice field was not working in tests due to GF 2.9 test environment not loading the GF_Field_Image_Choice class.

**Root Cause**: The automatic field registration relies on GF_Fields::get_all() which only includes loaded field classes. GF 2.9 test environment doesn't load ImageChoice (and some other) field classes.

**Solution**: Load the field class manually in `tests/bootstrap.php` to ensure it's available for automatic registration.

**Code Change**: Added `require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-image-choice.php';` to `tests/bootstrap.php`

**Impact**: Field now properly registered and all 4 mutation tests pass. This pattern may be needed for other fields not loaded in GF 2.9 (Password, MultipleChoice, Price, Calculation).

**Testing**: All ImageChoiceFieldTest mutations now pass (submit, update, draft submit, draft update).

## PostCustomFieldTest Resolution (2026-01-23)

**Issue**: PostCustomFieldTest was marked as "HAS EXPECTATION ISSUES" in IMPLEMENTATION_PLAN.md.

**Investigation**: Upon running the test, it passes all 4 mutations (submit, update, draft submit, draft update) successfully.

**Root Cause**: The test expectations were correct and the implementation was working properly. The "expectation issues" note was outdated.

**Resolution**: Confirmed that PostCustomFieldTest passes all requirements. Updated IMPLEMENTATION_PLAN.md and PRD.md to reflect completion.

**Impact**: PostCustomField now fully verified with 4/4 mutations passing. All post fields are now complete.