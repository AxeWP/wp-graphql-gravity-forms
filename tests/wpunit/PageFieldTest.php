<?php
/**
 * Test PageField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
use WPGraphQL\GF\Types\Enum;

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
	public function generate_fields() : array {
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
	 * Thehe value as expected by Gravity Forms.
	 */
	public function value() {
		return []; }

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on PageField {
				adminLabel
				adminOnly
				allowsPrepopulate
				displayOnly
				label
				nextButton {
					imageUrl
					text
					type
				}
				previousButton {
					imageUrl
					text
					type
				}
				size
				visibility
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return ''; }

	/**
	 * Returns the UpdateEntry mutation string.
	 */
	public function update_entry_mutation() : string {
		return '';
	}

	/**
	 * Returns the UpdateDraftEntry mutation string.
	 */
	public function update_draft_entry_mutation() : string {
		return '';
	}

	/**
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 */
	public function expected_field_response( array $form ) : array {
		return [
			$this->expectedObject(
				'gravityFormsEntry',
				[
					$this->expectedObject(
						'form',
						[
							$this->expectedObject(
								'pagination',
								[
									$this->get_expected_fields(
										[
											'backgroundColor' => $form['pagination']['backgroundColor'],
											'color' => $form['pagination']['color'],
											'displayProgressbarOnConfirmation' => $form['pagination']['display_progressbar_on_confirmation'],
											'pages' => $form['pagination']['pages'],
											'progressbarCompletionText' => $form['pagination']['progressbar_completion_text'],
											'style' => $this->tester->get_enum_for_value( Enum\PageProgressStyleEnum::$type, $form['pagination']['style'] ),
											'type'  => $this->tester->get_enum_for_value( Enum\PageProgressTypeEnum::$type, $form['pagination']['type'] ),
										]
									),
								]
							),
						]
					),
					$this->expectedObject(
						'formFields',
						[
							$this->expectedNode(
								'0',
								$this->property_helper->getAllActualValues( $form['fields'][0] ),
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
	 * @return array
	 */
	public function expected_mutation_response( string $mutationName, $value ) : array {
		return [];
	}

	/**
	 * Checks if values submitted by GraphQL are the same as whats stored on the server.
	 *
	 * @param array $actual_entry .
	 * @param array $form .
	 */
	public function check_saved_values( $actual_entry, $form ) : void {}
}
