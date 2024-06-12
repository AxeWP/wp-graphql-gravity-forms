<?php
/**
 * Test PostTagsMultiSelectField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -PostTagsMultiSelectFieldTest
 */
class PostTagsMultiSelectFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	public int $tag_id_1;
	public int $tag_id_2;
	public int $tag_id_3;

	public function setUp(): void {
		// Before...
		$this->tag_id_1 = self::factory()->tag->create();
		$this->tag_id_2 = self::factory()->tag->create();
		$this->tag_id_3 = self::factory()->tag->create();

		parent::setUp();

		$this->clearSchema();
	}

	public function tearDown(): void {
		wp_delete_term( $this->tag_id_1, 'post_tag' );
		wp_delete_term( $this->tag_id_2, 'post_tag' );
		wp_delete_term( $this->tag_id_3, 'post_tag' );

		parent::tearDown();
	}

	/**
	 * Tests the field properties and values.
	 */
	public function testField(): void {
		$this->runTestField();
	}

	/**
	 * Tests submitting the field values as a draft entry with submitGfForm.
	 */
	public function testSubmitDraft(): void {
		$this->runTestSubmitDraft();
	}

	/**
	 * Tests submitting the field values as an entry with submitGfForm.
	 */
	public function testSubmitForm(): void {
		$this->runtestSubmitForm();
	}

	/**
	 * Tests updating the field value with updateGfEntry.
	 */
	public function testUpdateEntry(): void {
		$this->runtestUpdateEntry();
	}

	/**
	 * Tests updating the draft field value with updateGfEntry.
	 */
	public function testUpdateDraft(): void {
		$this->runTestUpdateDraft();
	}

	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		return $this->tester->getPropertyHelper( 'PostTagsField' );
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields(): array {
			return [
				$this->factory->field->create(
					array_merge(
						$this->property_helper->values,
						[ 'inputType' => 'multiselect' ],
						[
							'choices' => [
								[
									'text'       => self::factory()->tag->get_object_by_id( $this->tag_id_1 )->name,
									'value'      => (string) self::factory()->tag->get_object_by_id( $this->tag_id_1 )->term_id,
									'isSelected' => false,
								],
								[
									'text'       => self::factory()->tag->get_object_by_id( $this->tag_id_2 )->name,
									'value'      => (string) self::factory()->tag->get_object_by_id( $this->tag_id_2 )->term_id,
									'isSelected' => false,
								],
								[
									'text'       => self::factory()->tag->get_object_by_id( $this->tag_id_3 )->name,
									'value'      => (string) self::factory()->tag->get_object_by_id( $this->tag_id_3 )->term_id,
									'isSelected' => false,
								],
							],
						],
					)
				),
			];
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function field_value() {
		return [
			$this->fields[0]['choices'][0]['value'],
			$this->fields[0]['choices'][1]['value'],
		];
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return [
			$this->fields[0]['choices'][0]['value'],
			$this->fields[0]['choices'][1]['value'],
		];
	}

	/**
	 * The graphql field value input.
	 */
	public function updated_field_value_input() {
		return [ $this->fields[0]['choices'][2]['value'] ];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			$this->fields[0]['choices'][2]['value'],
		];
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [
			$this->fields[0]['id'] => $this->fields[0]->to_string( $this->field_value ),
		];
	}

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '
			... on PostTagsField {
				adminLabel
				canPrepopulate
				conditionalLogic {
					actionType
					logicType
					rules{
						fieldId
						operator
						value
					}
				}
				cssClass
				defaultValue
				description
				descriptionPlacement
				errorMessage
				inputName
				isRequired
				label
				labelPlacement
				placeholder
				pageNumber
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				size
				... on PostTagsMultiSelectField {
					choices {
						isSelected
						text
						value
					}
					hasChoiceValue
					hasEnhancedUI
					values
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return 'mutation ($formId: ID!, $fieldId: Int!, $value: [String]!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, values: $value}}) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry {
						formFields {
							nodes {
								... on PostTagsMultiSelectField {
									values
								}
							}
						}
						... on GfSubmittedEntry {
							databaseId
						}
						... on GfDraftEntry {
							resumeToken
						}
					}
				}
			}
		';
	}

	/**
	 * Returns the UpdateEntry mutation string.
	 */
	public function update_entry_mutation(): string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: [String]! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, values: $value} }) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry {
						formFields {
							nodes {
								... on PostTagsMultiSelectField {
									values
								}
							}
						}
					}
				}
			}
		';
	}

	/**
	 * Returns the UpdateDraftEntry mutation string.
	 */
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: [String]! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, values: $value} }) {
					errors {
						id
						message
						connectedFormField {
							databaseId
							type
						}
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on PostTagsMultiSelectField {
									values
								}
							}
						}
					}
				}
			}
		';
	}

	/**
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expected_field_value( 'values', $this->field_value );

		return [
			$this->expectedObject(
				'gfEntry',
				[
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'nodes',
								$expected,
								0
							),
						]
					),
				]
			),
		];
	}

	/**
	 * The expected WPGraphQL mutation response.
	 *
	 * @param string $mutationName .
	 * @param mixed  $value .
	 */
	public function expected_mutation_response( string $mutationName, $value ): array {
		return [
			$this->expectedObject(
				$mutationName,
				[
					$this->expectedObject(
						'entry',
						[
							$this->expectedObject(
								'formFields',
								[
									$this->expectedNode(
										'nodes',
										[
											$this->expected_field_value( 'values', self::NOT_FALSY ),
										]
									),
								]
							),
						]
					),
				]
			),
		];
	}

	/**
	 * Checks if values submitted by GraphQL are the same as whats stored on the server.
	 *
	 * @param array $actual_entry .
	 * @param array $form .
	 */
	public function check_saved_values( $actual_entry, $form ): void {
		$this->assertEquals(
			$this->field_value,
			$form['fields'][0]->to_array( $actual_entry[ $form['fields'][0]['id'] ] ),
			'Submit mutation entry value not equal.'
		);
	}
}
