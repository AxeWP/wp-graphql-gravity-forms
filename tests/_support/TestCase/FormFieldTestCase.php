<?php
/**
 * Test case for Form Fields.
 *
 * For testing WPGraphQL responses.
 *
 * @since 0.8.3
 * @package Tests\WPGraphQL\Gravity\GravityForms
 */

namespace Tests\WPGraphQL\GravityForms\TestCase;

use GFFormsModel;
use GFAPI;

/**
 * Class - FormFieldTestCase
 */
class FormFieldTestCase extends GFGraphQLTestCase {
	protected $draft_token;
	protected $entry_id;
	protected $field_value;
	protected $fields;
	protected $form_id;

	/**
	 * Toggles testing draft entries.
	 *
	 * @var bool
	 */
	protected $test_draft = false;

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
		parent::setUp();
		wp_set_current_user( $this->admin->ID );

		$this->property_helper = $this->field_helper();

		$this->fields = $this->generate_fields();

		$this->field_value       = $this->field_value();
		$this->field_value_input = $this->field_value_input();
		$this->value             = $this->value();

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => $this->fields ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$this->entry_id = $this->factory->entry->create(
			array_merge(
				[ 'form_id' => $this->form_id ],
				$this->value
			)
		);

		if ( $this->test_draft ) {
			$this->draft_token = $this->factory->draft_entry->create(
				[
					'form_id'     => $this->form_id,
					'entry'       => array_merge(
						$this->value,
						[
							'fieldValues' => $this->property_helper->get_field_values( $this->value ),
						]
					),
					'fieldValues' => $this->property_helper->get_field_values( $this->value ),
				]
			);
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
		// Then...
		parent::tearDown();
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
	public function updated_field_value_input() {
		return $this->updated_field_value();
	}

	/**
	 * Tests the field properties and values.
	 */
	protected function runTestField(): void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->field_query();

		$variables = [
			'id'     => $this->entry_id,
			'idType' => 'DATABASE_ID',
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_field_response( $form );

		codecept_debug( $expected );
		codecept_debug( $response );

		$this->assertQuerySuccessful( $response, $expected );

		// Test Draft entry.
		if ( $this->test_draft ) {
			$variables = [
				'id'     => $this->draft_token,
				'idType' => 'ID',
			];

			$response = $this->graphql( compact( 'query', 'variables' ) );

			$this->assertQuerySuccessful( $response, $expected );
		}
	}

	/**
	 * Tests submitting the field values as a draft entry with submitGravityFormsForm.
	 */
	protected function runTestSubmitDraft() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query = $this->submit_form_mutation();

		$variables = [
			'draft'   => true,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $this->field_value_input,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'submitGravityFormsForm', $this->field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$resume_token = $response['data']['submitGravityFormsForm']['resumeToken'];

		$this->factory->draft_entry->delete( $resume_token );
	}

	/**
	 * Tests submitting the field values as an entry with submitGravityFormsForm.
	 */
	protected function runTestSubmit() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$query     = $this->submit_form_mutation();
		$variables = [
			'draft'   => false,
			'formId'  => $this->form_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $this->field_value_input,
		];

		codecept_debug( $variables );

		// Test entry.
		$response = $this->graphql( compact( 'query', 'variables' ) );

		codecept_debug( $response );

		$expected = $this->expected_mutation_response( 'submitGravityFormsForm', $this->field_value );
		$this->assertQuerySuccessful( $response, $expected );

		$entry_id = $response['data']['submitGravityFormsForm']['entryId'];

		$actual_entry = GFAPI::get_entry( $entry_id );

		$this->check_saved_values( $actual_entry, $form );

		$this->factory->entry->delete( $entry_id );
	}

	/**
	 * Tests updating the field value with updateGravityFormsEntry.
	 */
	protected function runTestUpdate() : void {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$field_value       = $this->updated_field_value();
		$field_value_input = $this->updated_field_value_input();

		$query = $this->update_entry_mutation();

		$variables = [
			'entryId' => $this->entry_id,
			'fieldId' => $form['fields'][0]->id,
			'value'   => $field_value_input,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'updateGravityFormsEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );
	}

	/**
	 * Tests updating the draft field value with updateGravityFormsEntry.
	 */
	protected function runTestUpdateDraft() : void {
		$form         = $this->factory->form->get_object_by_id( $this->form_id );
		$resume_token = $this->factory->draft_entry->create( [ 'form_id' => $this->form_id ] );

		$field_value       = $this->updated_field_value();
		$field_value_input = $this->updated_field_value_input();

		$query = $this->update_draft_entry_mutation();

		$variables = [
			'resumeToken' => $resume_token,
			'fieldId'     => $form['fields'][0]->id,
			'value'       => $field_value_input,
		];

		$response = $this->graphql( compact( 'query', 'variables' ) );

		$expected = $this->expected_mutation_response( 'updateGravityFormsDraftEntry', $field_value );

		$this->assertQuerySuccessful( $response, $expected );

		$this->factory->draft_entry->delete( $resume_token );
	}

}