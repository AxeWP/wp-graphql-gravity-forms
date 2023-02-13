<?php
/**
 * Test case for Form Fields.
 *
 * For testing WPGraphQL responses.
 *
 * @since 0.9.0
 * @package Tests\WPGraphQL\GF\TestCase;
 */

namespace Tests\WPGraphQL\GF\TestCase;

use GFFormsModel;
use GFAPI;
use GF_Field;
use Helper\GFHelpers\ExpectedFormFields;
use WPGraphQL\GF\Registry\FormFieldRegistry;

/**
 * Class - FormFieldTestCase
 */
class FormFieldTestCase extends GFGraphQLTestCase {
	use ExpectedFormFields;

	protected $draft_token;
	protected $entry_id;
	protected $field_value;
	protected $field_value_input;
	protected $draft_field_value;
	protected $draft_field_value_input;
	protected $updated_field_value;
	protected $updated_field_value_input;
	protected $updated_draft_field_value;
	protected $updated_draft_field_value_input;
	protected $fields;
	protected $form_id;
	protected $field_id;
	protected $is_draft;

	/**
	 * Toggles testing draft entries.
	 *
	 * @var bool
	 */
	protected $test_draft = true;

	/**
	 * Plain representation of the value. Used to derive the GF values and the GraphQL values.
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Set up.
	 */
	public function setUp(): void {
		// Before...
		$this->is_draft = false;
		parent::setUp();

		wp_set_current_user( $this->admin->ID );

		$this->property_helper = $this->field_helper();

		$this->fields    = $this->generate_fields();
		$this->field_id  = $this->mutation_value_field_id();
		$this->form_args = $this->generate_form_args();

		$this->form_id = $this->createTestForm();

		$this->field_value                     = $this->field_value();
		$this->field_value_input               = $this->field_value_input();
		$this->draft_field_value               = $this->draft_field_value();
		$this->draft_field_value_input         = $this->draft_field_value_input();
		$this->updated_field_value             = $this->updated_field_value();
		$this->updated_field_value_input       = $this->updated_field_value_input();
		$this->updated_draft_field_value       = $this->updated_draft_field_value();
		$this->updated_draft_field_value_input = $this->updated_draft_field_value_input();
		$this->value                           = $this->value();
		$this->field_query                     = $this->field_query();
		$this->entry_query                     = $this->entry_query();

		$this->entry_id = $this->createTestEntry();

		if ( $this->test_draft ) {
			$this->draft_token = $this->createDraftEntry();
		}

		$this->clearSchema();
	}

	/**
	 * Run after each test.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		$this->factory->entry->delete( $this->entry_id );
		$this->factory->draft_entry->delete( $this->draft_token );
		$this->factory->form->delete( $this->form_id );
		GFFormsModel::set_current_lead( null );
		unset( $_POST );

		// Then...
		parent::tearDown();
	}

	/**
	 * Creates the test form.
	 *
	 * @uses $this->fields
	 * @uses $this->form_args
	 */
	public function createTestForm() : int {
		$form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->form_args,
			)
		);

		return $form_id;
	}

	/**
	 * Creates the test entry.
	 *
	 * @uses $this->form
	 * @uses $this->value
	 *
	 * @return integer
	 */
	public function createTestEntry() : int {
		$entry_id = $this->factory->entry->create(
			[ 'form_id' => $this->form_id ] + $this->value
		);

		return $entry_id;
	}

	/**
	 * Creates the draft entry.
	 *
	 * @uses $this->get_draft_field_values()
	 * @uses $this->form_id
	 * @uses $this->value
	 *
	 * @return string
	 */
	public function createDraftEntry() : string {
		$draft_entry_field_values = $this->get_draft_field_values();

		$draft_token = $this->factory->draft_entry->create(
			[
				'form_id'     => $this->form_id,
				'entry'       => $this->value + [
					'fieldValues' => $draft_entry_field_values,
				],
				'created_by'  => $this->admin->ID,
				'fieldValues' => $draft_entry_field_values,
			]
		);

		return $draft_token;
	}

	/**
	 * The default form args.
	 */
	public function generate_form_args() {
		return $this->tester->getFormDefaultArgs(
			[
				'button'        => null,
				'confirmations' => null,
				'notifications' => null,
			]
		);
	}

	public function mutation_value_field_id() : int {
		return $this->fields[0]->id;
	}

	public function get_draft_field_values() {
		return $this->property_helper->get_field_values( $this->value );
	}

	/**
	 * The field value expected by graphql.
	 */
	public function field_value() {
		return $this->field_value;
	}

	/**
	 * The draft field value expected by graphql.
	 */
	public function draft_field_value() {
		return $this->field_value;
	}

	/**
	 * The updated field value expected by graphql.
	 */
	public function updated_field_value() {
		return $this->updated_field_value;
	}

	/**
	 * The updated draft field value expected by graphql.
	 */
	public function updated_draft_field_value() {
		return $this->updated_field_value;
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return $this->field_value;
	}

	/**
	 * The graphql field value input.
	 */
	public function draft_field_value_input() {
		return $this->field_value_input;
	}

	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		return $this->updated_field_value;
	}
	/**
	 * The graphql field value input.
	 */
	public function updated_draft_field_value_input() {
		return $this->updated_field_value_input;
	}

	/**
	 * The entire GraphQL query with the form field values added.
	 */
	protected function entry_query() : string {
		return "
			query getFieldValue(\$id: ID!, \$idType: EntryIdTypeEnum) {
				gfEntry(id: \$id, idType: \$idType ) {
					formFields {
						nodes {
							displayOnly
							id
							inputType
							layoutGridColumnSpan
							layoutSpacerGridColumnSpan
							pageNumber
							type
							visibility
							{$this->field_query}
						}
					}
				}
			}
		";
	}

	/**
	 * Tests the field properties and values.
	 */
	protected function runTestField(): void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->entry_query;

		$variables = [
			'id'     => $this->entry_id,
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );
		$this->assertArrayNotHasKey( 'errors', $response, 'field has errors' );

		$expected = $this->expected_field_response( $form );

		$this->assertQuerySuccessful( $response, $expected );

		// Test Draft entry.
		if ( $this->test_draft ) {
			$this->is_draft = true;
			$expected       = $this->expected_field_response( $form );

			$variables = [
				'id'     => $this->draft_token,
				'idType' => 'RESUME_TOKEN',
			];

			$response = $this->graphql( compact( 'query', 'variables' ) );

			$this->assertQuerySuccessful( $response, $expected );
		}
	}

	/**
	 * Tests submitting the field values as a draft entry with submitGfForm.
	 */
	protected function runTestSubmitDraft() : void {
		$this->is_draft = true;
		wp_set_current_user( $this->admin->ID );

		$query = $this->submit_form_mutation();

		$variables = [
			'draft'   => true,
			'formId'  => $this->form_id,
			'fieldId' => $this->field_id,
			'value'   => $this->draft_field_value_input,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'submitGfForm', $this->draft_field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$resume_token = $response['data']['submitGfForm']['entry']['resumeToken'];

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Tests submitting the field values as an entry with submitGfForm.
	 */
	protected function runtestSubmitForm() : void {
		$this->is_draft = false;
		$form           = $this->factory->form->get_object_by_id( $this->form_id );
		wp_set_current_user( $this->admin->ID );

		$query     = $this->submit_form_mutation();
		$variables = [
			'draft'   => false,
			'formId'  => $this->form_id,
			'fieldId' => $this->field_id,
			'value'   => $this->field_value_input,
		];

		// Test entry.
		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'submitGfForm', $this->field_value );
		$this->assertQuerySuccessful( $response, $expected );
		$this->assertEquals( $response['data']['submitGfForm']['errors'], null );

		$entry_id = $response['data']['submitGfForm']['entry']['databaseId'];

		$actual_entry = GFAPI::get_entry( $entry_id );

		$this->assertNotWPError( $actual_entry );

		$this->check_saved_values( $actual_entry, $form );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Tests updating the field value with updateGfEntry.
	 */
	protected function runtestUpdateEntry() : void {
		$this->is_draft = false;
		wp_set_current_user( $this->admin->ID );

		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$field_value       = $this->updated_field_value;
		$field_value_input = $this->updated_field_value_input;

		$query = $this->update_entry_mutation();

		$variables = [
			'entryId' => $this->entry_id,
			'fieldId' => $this->field_id,
			'value'   => $field_value_input,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'updateGfEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Tests updating the draft field value with updateGfEntry.
	 */
	protected function runTestUpdateDraft() : void {
		$this->is_draft = true;
		wp_set_current_user( $this->admin->ID );

		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create(
			[
				'form_id'    => $this->form_id,
				'created_by' => $this->admin->ID,
			]
		);

		$field_value       = $this->updated_draft_field_value;
		$field_value_input = $this->updated_draft_field_value_input;

		$query = $this->update_draft_entry_mutation();

		$variables = [
			'resumeToken' => $resume_token,
			'fieldId'     => $this->field_id,
			'value'       => $field_value_input,
			'createdBy'   => $this->admin->ID,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'updateGfDraftEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$this->factory->draft_entry->delete( $resume_token );
	}

	protected function getExpectedFormFieldValues( GF_Field $field ) {
		$expected = [];

		$field_settings = str_replace( '-', '_', FormFieldRegistry::get_field_settings( $field ) );

		foreach ( $field_settings as $setting ) {
			if ( method_exists( $this, $setting ) ) {
				$this->$setting( $field, $expected );
			}
		}

		if ( ! in_array( $field->get_input_type(), [ 'captcha', 'html', 'page', 'section' ], true ) ) {
			$expected[] = $this->expectedObject(
				'personalData',
				[
					$this->expectedField( 'shouldErase', ! empty( $field['personalDataErase'] ) ),
					$this->expectedField( 'shouldExport', ! empty( $field['personalDataExport'] ) ),
				]
			);
		}

		return $expected;
	}
}
