# Learnings: WPGraphQL Gravity Forms Implementation

## ImageChoice Field Implementation (2026-01-23)

**Issue**: ImageChoice field was not working in tests due to GF 2.9 test environment not loading the GF_Field_Image_Choice class.

**Root Cause**: The automatic field registration relies on GF_Fields::get_all() which only includes loaded field classes. GF 2.9 test environment doesn't load ImageChoice (and some other) field classes.

**Solution**: Load the field class manually in `tests/bootstrap.php` to ensure it's available for automatic registration.

**Code Change**: Added `require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-image-choice.php';` to `tests/bootstrap.php`

**Impact**: Field now properly registered and all 4 mutation tests pass. This pattern may be needed for other fields not loaded in GF 2.9 (Password, MultipleChoice, Price, Calculation).

**Testing**: All ImageChoiceFieldTest mutations now pass (submit, update, draft submit, draft update).