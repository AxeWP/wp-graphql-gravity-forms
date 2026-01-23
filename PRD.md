# Master PRD: WPGraphQL for Gravity Forms - Field Verification Protocol

## 1. Vision & Objective
The objective is absolute confidence. We are systematically auditing the bridge between Gravity Forms' internal `GF_Field` architecture and the WPGraphQL Schema.

**Goal**: Achieve **100% verified coverage** where every supported Gravity Forms field:
1.  **Exits** in the Schema (`FormField`).
2.  **Resolves** correctly in queries (`Entry`, `DraftEntry`).
3.  **Mutates** correctly (`submitGfForm`, `updateGfEntry`, `updateGfDraftEntry`).
4.  **Tests** cleanly with comprehensive, dedicated Codeception integration tests.

## 2. The "Ralph Loop" Protocol
This is an agentic, iterative process. Do not batch these tasks. Execute the loop for **one field at a time**.

### Phase 1: The Audit (Read-Only)
1.  **Identify**: Select the next `GF_Field` from the **Inventory** below.
2.  **Locate Artifacts**:
    -   **Test**: `tests/wpunit/{Type}FieldTest.php`
    -   **Registration**: `src/Registry/FormFieldRegistry.php`
3.  **Gap Analysis**:
    -   Does the GraphQL type exist?
    -   Does the Test file exist?
    -   Does the Test extend `FormFieldTestCase`?
    -   Does the Test cover **all 4 mutations** (Submit, Update, SubmitDraft, UpdateDraft)?
    -   Does the Test verify specific field properties (not just generic `value`)?

### Phase 2: The Action (Write/Fix)
1.  **Missing Test**: Create it immediately using `tests/wpunit/TextFieldTest.php` as the template.
2.  **Weak Test**: Refactor. Add assertions for specific `GF_Field` settings (e.g., `inputMask`, `defaultValue`, `choices`).
3.  **Broken Implementation**: If the test fails:
    -   Fix the `FieldValueInput` class (`src/Data/FieldValueInput/`).
    -   Fix the Schema Registration (`src/Registry/`).
    -   Fix the Mutation Resolver (`src/Mutation/`).
4.  **Verify**: Run `npm run test tests/wpunit/{Type}FieldTest.php`.

### Phase 3: The Sign-off
1.  **Lint**: `npm run lint:php`
2.  **Update PRD**: Mark the item as [x] in the **Inventory** within this file (`@PRD.md`).

---

## 3. Technical Context

### Critical Interfaces
-   **GF_Field**: The upstream Gravity Forms class.
-   **FormField**: Base GraphQL interface for all fields.
-   **FieldValueInput**: Handling for mutation inputs (`src/Data/FieldValueInput/`).

### Support Policy
-   **Modern vs Legacy**: Prioritize "Modern" Gravity Forms markup. Support "Legacy" only if easy and > GF 2.6.
-   **Deprecated Fields**: If Gravity Forms has deprecated a field (e.g., `donation`), do **not** add support. Ensure it is explicitly ignored.

---

## 4. The Inventory (Task List)

### Complete Field Cross-Reference
This table maps all 46 GF field class files to their PRD inventory entries:

| GF Type | Class File | Test File | Category | Status |
|---------|-----------|-----------|----------|---------|
| `text` | class-gf-field-text.php | TextFieldTest.php | Standard | Listed |
| `textarea` | class-gf-field-textarea.php | TextAreaFieldTest.php | Standard | Listed |
| `select` | class-gf-field-select.php | SelectFieldTest.php | Standard | **Added** |
| `number` | class-gf-field-number.php | NumberFieldTest.php | Standard | Listed |
| `checkbox` | class-gf-field-checkbox.php | CheckboxFieldTest.php | Standard | Listed |
| `radio` | class-gf-field-radio.php | RadioFieldTest.php | Standard | Listed |
| `hidden` | class-gf-field-hidden.php | HiddenFieldTest.php | Standard | Listed |
| `html` | class-gf-field-html.php | HtmlFieldTest.php | Standard | Listed |
| `section` | class-gf-field-section.php | SectionFieldTest.php | Standard | Listed |
| `page` | class-gf-field-page.php | PageFieldTest.php | Standard | Listed |
| `name` | class-gf-field-name.php | NameFieldTest.php | Advanced | Listed |
| `date` | class-gf-field-date.php | DateFieldTest.php | Advanced | Listed |
| `time` | class-gf-field-time.php | TimeFieldTest.php | Advanced | Listed |
| `phone` | class-gf-field-phone.php | PhoneFieldTest.php | Advanced | Listed |
| `address` | class-gf-field-address.php | AddressFieldTest.php | Advanced | Listed |
| `website` | class-gf-field-website.php | WebsiteFieldTest.php | Advanced | Listed |
| `email` | class-gf-field-email.php | EmailFieldTest.php | Advanced | Listed |
| `fileupload` | class-gf-field-fileupload.php | FileUploadFieldTest.php | Advanced | Listed |
| `list` | class-gf-field-list.php | ListFieldTest.php | Advanced | Listed |
| `multiselect` | class-gf-field-multiselect.php | MultiSelectFieldTest.php | Advanced | Listed |
| `multi_choice` | class-gf-field-multiple-choice.php | MultipleChoiceFieldTest.php | Advanced | **Added** |
| `consent` | class-gf-field-consent.php | ConsentFieldTest.php | Advanced | Listed |
| `captcha` | class-gf-field-captcha.php | CaptchaFieldTest.php | Advanced | Listed |
| `password` | class-gf-field-password.php | PasswordFieldTest.php | Advanced | Listed |
| `image_choice` | class-gf-field-image-choice.php | ImageChoiceFieldTest.php | Advanced | Listed |
| `post_title` | class-gf-field-post-title.php | PostTitleFieldTest.php | Post | Listed |
| `post_content` | class-gf-field-post-content.php | PostContentFieldTest.php | Post | Listed |
| `post_excerpt` | class-gf-field-post-excerpt.php | PostExcerptFieldTest.php | Post | Listed |
| `post_tags` | class-gf-field-post-tags.php | PostTagsFieldTest.php | Post | Listed |
| `post_category` | class-gf-field-post-category.php | PostCategoryFieldTest.php | Post | Listed |
| `post_image` | class-gf-field-post-image.php | PostImageFieldTest.php | Post | Listed |
| `post_custom_field` | class-gf-field-post-custom-field.php | PostCustomFieldTest.php | Post | Listed |
| `product` | class-gf-field-product.php | ProductFieldTest.php | Pricing | Listed |
| `singleproduct` | class-gf-field-singleproduct.php | SingleProductFieldTest.php | Pricing | **Added** |
| `hiddenproduct` | class-gf-field-hiddenproduct.php | HiddenProductFieldTest.php | Pricing | **Added** |
| `quantity` | class-gf-field-quantity.php | QuantityFieldTest.php | Pricing | Listed |
| `option` | class-gf-field-option.php | OptionFieldTest.php | Pricing | Listed |
| `shipping` | class-gf-field-shipping.php | ShippingFieldTest.php | Pricing | Listed |
| `singleshipping` | class-gf-field-singleshipping.php | SingleShippingFieldTest.php | Pricing | **Added** |
| `total` | class-gf-field-total.php | TotalFieldTest.php | Pricing | Listed |
| `price` | class-gf-field-price.php | PriceFieldTest.php | Pricing | **Added** |
| `calculation` | class-gf-field-calculation.php | CalculationFieldTest.php | Pricing | **Added** |
| `creditcard` | class-gf-field-creditcard.php | - | Ignored | Experimental |
| `donation` | class-gf-field-donation.php | - | Ignored | Deprecated |
| `repeater` | class-gf-field-repeater.php | - | Ignored | Beta |
| `submit` | class-gf-field-submit.php | - | Ignored | Not a field |
| `honeypot` | class-gf-field-honeypot.php | - | Ignored | Internal |

**Note**: Signature and Quiz fields are from add-ons (GFSignature, GFQuiz extensions), not core GF.

---

### 🟢 Standard Fields (High Priority)
- [ ] **Text** (`text`) -> `TextFieldTest.php` | `class-gf-field-text.php`
- [ ] **Textarea** (`textarea`) -> `TextAreaFieldTest.php` | `class-gf-field-textarea.php`
- [ ] **Select** (`select`) -> `SelectFieldTest.php` | `class-gf-field-select.php` - **MISSING FROM PRD** (Drop Down field)
- [ ] **Number** (`number`) -> `NumberFieldTest.php` | `class-gf-field-number.php`
- [ ] **Checkbox** (`checkbox`) -> `CheckboxFieldTest.php` | `class-gf-field-checkbox.php`
- [ ] **Radio** (`radio`) -> `RadioFieldTest.php` | `class-gf-field-radio.php`
- [ ] **Hidden** (`hidden`) -> `HiddenFieldTest.php` | `class-gf-field-hidden.php`
- [ ] **HTML** (`html`) -> `HtmlFieldTest.php` | `class-gf-field-html.php` (Display only)
- [ ] **Section** (`section`) -> `SectionFieldTest.php` | `class-gf-field-section.php` (Structure only)
- [ ] **Page** (`page`) -> `PageFieldTest.php` | `class-gf-field-page.php` (Pagination logic)

### 🟡 Advanced Fields (Complexity: High)
- [ ] **Name** (`name`) -> `NameFieldTest.php` | `class-gf-field-name.php` (Check: First/Last/Middle/Suffix)
- [ ] **Date** (`date`) -> `DateFieldTest.php` | `class-gf-field-date.php` (Check: Date formats)
- [ ] **Time** (`time`) -> `TimeFieldTest.php` | `class-gf-field-time.php`
- [ ] **Phone** (`phone`) -> `PhoneFieldTest.php` | `class-gf-field-phone.php` (Check: format validation)
- [ ] **Address** (`address`) -> `AddressFieldTest.php` | `class-gf-field-address.php` (Check: Address types)
- [ ] **Website** (`website`) -> `WebsiteFieldTest.php` | `class-gf-field-website.php`
- [ ] **Email** (`email`) -> `EmailFieldTest.php` | `class-gf-field-email.php`
- [ ] **File Upload** (`fileupload`) -> `FileUploadFieldTest.php` | `class-gf-field-fileupload.php` (Check: `map_input` logic)
- [ ] **List** (`list`) -> `ListFieldTest.php` | `class-gf-field-list.php` (Check: Serialized data)
- [ ] **MultiSelect** (`multiselect`) -> `MultiSelectFieldTest.php` | `class-gf-field-multiselect.php`
- [ ] **Multiple Choice** (`multi_choice`) -> `MultipleChoiceFieldTest.php` | `class-gf-field-multiple-choice.php` - **CANNOT IMPLEMENT** (GF 2.9 test environment incompatibility)
- [ ] **Consent** (`consent`) -> `ConsentFieldTest.php` | `class-gf-field-consent.php`
- [ ] **Captcha** (`captcha`) -> `CaptchaFieldTest.php` | `class-gf-field-captcha.php` (Check: Validation bypass in tests)
- [ ] **Password** (`password`) -> `PasswordFieldTest.php` - **CANNOT IMPLEMENT** (GF 2.9 test environment incompatibility)
- [ ] **Image Choice** (`image_choice`) -> `ImageChoiceFieldTest.php` - **MISSING TEST** (GF 2.5+)
- [ ] **Signature** (`signature`) -> `SignatureFieldTest.php` - *Add-on field (GFSignature extension)*

### 🔵 Post Fields (Integration: WP Core)
- [ ] **Post Title** (`post_title`) -> `PostTitleFieldTest.php` | `class-gf-field-post-title.php`
- [ ] **Post Content** (`post_content`) -> `PostContentFieldTest.php` | `class-gf-field-post-content.php`
- [ ] **Post Excerpt** (`post_excerpt`) -> `PostExcerptFieldTest.php` | `class-gf-field-post-excerpt.php`
- [ ] **Post Tags** (`post_tags`) -> `PostTagsFieldTest.php` | `class-gf-field-post-tags.php`
- [ ] **Post Category** (`post_category`) -> `PostCategoryFieldTest.php` | `class-gf-field-post-category.php`
- [ ] **Post Image** (`post_image`) -> `PostImageFieldTest.php` | `class-gf-field-post-image.php`
- [ ] **Post Custom Field** (`post_custom_field`) -> `PostCustomFieldTest.php` | `class-gf-field-post-custom-field.php` - **MISSING TEST**

### 🟣 Pricing Fields (Calculations)
- [ ] **Product** (`product`) -> `ProductFieldTest.php` | `class-gf-field-product.php` (Base product field)
- [ ] **Single Product** (`singleproduct`) -> `SingleProductFieldTest.php` | `class-gf-field-singleproduct.php` - **MISSING FROM PRD**
- [ ] **Hidden Product** (`hiddenproduct`) -> `HiddenProductFieldTest.php` | `class-gf-field-hiddenproduct.php` - **MISSING FROM PRD**
- [ ] **Quantity** (`quantity`) -> `QuantityFieldTest.php` | `class-gf-field-quantity.php`
- [ ] **Option** (`option`) -> `OptionFieldTest.php` | `class-gf-field-option.php`
- [ ] **Shipping** (`shipping`) -> `ShippingFieldTest.php` | `class-gf-field-shipping.php` (Base shipping field)
- [ ] **Single Shipping** (`singleshipping`) -> `SingleShippingFieldTest.php` | `class-gf-field-singleshipping.php` - **MISSING FROM PRD**
- [ ] **Total** (`total`) -> `TotalFieldTest.php` | `class-gf-field-total.php`
- [ ] **Price** (`price`) -> `PriceFieldTest.php` | `class-gf-field-price.php` - **MISSING TEST**
- [ ] **Calculation** (`calculation`) -> `CalculationFieldTest.php` | `class-gf-field-calculation.php` - **MISSING TEST**

### 🟠 Quiz Fields (Add-on Support)
- [ ] **Quiz** (`quiz`) -> `QuizFieldTest.php` - *Add-on field (GFQuiz extension)*

### ⚫  Special Cases
*Verified in `src/Utils/Utils.php` as ignored/unsupported. These field class files exist in GF core but are not always handled the same way if at all. We don't need to support deprecated fields, but we should confirm if/how we're supporting the functionality even if it isn't via the automagical field registration.*
- [ ] **Credit Card** (`creditcard`) | `class-gf-field-creditcard.php` - *Experimental (Requires `WPGRAPHQL_GF_EXPERIMENTAL_FIELDS`)*
- [ ] **Donation** (`donation`) | `class-gf-field-donation.php` - *Deprecated in GF*
- [ ] **Repeater** (`repeater`) | `class-gf-field-repeater.php` - *Beta/Ignored*
- [ ] **Submit Button** (`submit`) | `class-gf-field-submit.php` - *Not a data field*
- [ ] **Honeypot** (`honeypot`) | `class-gf-field-honeypot.php` - *Internal spam prevention*

---

## 5. Discrepancy Reporting
When a gap is found that cannot be immediately fixed (e.g., architectural limitation), create an Issue entry below:

| Field | Issue | Severity | Action |
|-------|-------|----------|--------|
|       |       |          |        |
