<?php
/**
 * Test PageField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -PageFieldTest
 */
class PageFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Tests the field properties and values.
	 */
	public function testField(): void {
		$this->runTestField();
	}

	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		return $this->tester->getPropertyHelper( 'PageField' );
	}

	/**
	 * Generates the form fields from factory. Must be wrappend in an array.
	 */
	public function generate_fields(): array {
		return [ $this->factory->field->create( $this->property_helper->values ) ];
	}

	/**
	 * The value as expected in GraphQL.
	 */
	public function field_value() {
		return ''; }

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return ''; }

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return []; }

	/**
	 * The GraphQL query string.
	 */
	public function field_query(): string {
		return '
			... on PageField {
				conditionalLogic {
					actionType
					logicType
					rules {
						fieldId
						operator
						value
					}
				}
				cssClass
				nextButton {
					conditionalLogic {
						actionType
						logicType
						rules {
							fieldId
							operator
							value
						}
					}
					imageUrl
					text
					type
				}
				previousButton {
					conditionalLogic {
						actionType
						logicType
						rules {
							fieldId
							operator
							value
						}
					}
					imageUrl
					text
					type
				}
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation(): string {
		return ''; }

	/**
	 * Returns the UpdateEntry mutation string.
	 */
	public function update_entry_mutation(): string {
		return '';
	}

	/**
	 * Returns the UpdateDraftEntry mutation string.
	 */
	public function update_draft_entry_mutation(): string {
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected = $this->getExpectedFormFieldValues( $form['fields'][0] );

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
		return [];
	}

	/**
	 * Checks if values submitted by GraphQL are the same as whats stored on the server.
	 *
	 * @param array $actual_entry .
	 * @param array $form .
	 */
	public function check_saved_values( $actual_entry, $form ): void {}
}
