<?php
/**
 * Test Signature type.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;

/**
 * Class -SignatureFieldTest.
 */
class SignatureFieldTest  extends FormFieldTestCase implements FormFieldTestCaseInterface {
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
	public function testSubmit(): void {
		$this->runTestSubmit();
	}
	/**
	 * Tests updating the field value with updateGfEntry.
	 */
	public function testUpdate(): void {
		$this->runTestUpdate();
	}
	/**
	 * Tests updating the draft field value with updateGfEntry.
	 */
	public function testUpdateDraft():void {
		$this->runTestUpdateDraft();
	}
	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		return $this->tester->getPropertyHelper( 'SignatureField' );
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
		return '.png';
	}

	public function field_value_input() {
		return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAC0CAYAAAAuPxHvAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAETUlEQVR4nO3d23KbOgBAUdw5///LnKdME0/TYgeQNqz1ngxIaA++ID/WdV0XgIBfow8AYCvBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjL+G30AR3g8HqMPYVmWZVnXdfQhwKVcIlizBOrZn45LxOB9yWDNGqgtRAzelwhWOVBbfHd+QgZfTRmsqwdqK3dj8NUUwToiUCMX9pHBFTHu7LEOuNqvFqgtzr5rnH084B2nBmvPRXuFBSli8JpTgrXHwrzTYjszZHcaV/oOD9a7i89C+uqsiBl3ZnZosF5ZZBbK686ImHlhJocF61+LyUI4xtERM2+MdEiw/rZoXPDnOzJi5pMznfY9LBf2OB9jf0S4nv+neeZIU3xxlOP96/GfvWImYBxJsG5gy7OKz2ERsO99PqcrnE+JYF3cuw9WnxGw4mJ/HofH45E8j6rTgmViz7fnLhBHBKweL87nDuuijt6yZu+AXfGlI/s7JFjrutoiZqAR+2sdGTDx4oM7rAuZ6ftvewZMvPhwarC8j3WMwlMFn49hr3g9/9+jedUw3ul3WKK1ny0LaMax3itez38/4lxnHN8rG/rws8l+z9ZFXhzfWfdMs+/+HIZvL2PCt7vb7hez7EwrVvOYYgM/E/93dwvVn8z4/tFVx3pmp22RLFqvE6rvjQ7Y3cZ7FtPt6X73C+HVhXj38VoWe+PfydS/mnOHC8MW0vuz/9d1DQnWsrx3UV3lYvnJgrrKGJztpxEz7nMYFqxluc/C9atBsI+hwVqW/W/fR53OUS9DhAp+Gx6sD7O9cTryU6hJpgSmM02wPhv9kfXZJpwCmNKUuzUctdvlTEQKXjdlsJ7t+bDsKAIFP5cI1mffLfwZQiZKcKxcsL6zNRYed4GuywRrKxGCrl+jDwBgK8ECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjL+B69XbUab8vDaAAAAAElFTkSuQmCC';
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return '.png';
	}

	public function updated_field_value_input() {
		return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAC0CAYAAAAuPxHvAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAETUlEQVR4nO3d23KbOgBAUdw5///LnKdME0/TYgeQNqz1ngxIaA++ID/WdV0XgIBfow8AYCvBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjL+G30AR3g8HqMPYVmWZVnXdfQhwKVcIlizBOrZn45LxOB9yWDNGqgtRAzelwhWOVBbfHd+QgZfTRmsqwdqK3dj8NUUwToiUCMX9pHBFTHu7LEOuNqvFqgtzr5rnH084B2nBmvPRXuFBSli8JpTgrXHwrzTYjszZHcaV/oOD9a7i89C+uqsiBl3ZnZosF5ZZBbK686ImHlhJocF61+LyUI4xtERM2+MdEiw/rZoXPDnOzJi5pMznfY9LBf2OB9jf0S4nv+neeZIU3xxlOP96/GfvWImYBxJsG5gy7OKz2ERsO99PqcrnE+JYF3cuw9WnxGw4mJ/HofH45E8j6rTgmViz7fnLhBHBKweL87nDuuijt6yZu+AXfGlI/s7JFjrutoiZqAR+2sdGTDx4oM7rAuZ6ftvewZMvPhwarC8j3WMwlMFn49hr3g9/9+jedUw3ul3WKK1ny0LaMax3itez38/4lxnHN8rG/rws8l+z9ZFXhzfWfdMs+/+HIZvL2PCt7vb7hez7EwrVvOYYgM/E/93dwvVn8z4/tFVx3pmp22RLFqvE6rvjQ7Y3cZ7FtPt6X73C+HVhXj38VoWe+PfydS/mnOHC8MW0vuz/9d1DQnWsrx3UV3lYvnJgrrKGJztpxEz7nMYFqxluc/C9atBsI+hwVqW/W/fR53OUS9DhAp+Gx6sD7O9cTryU6hJpgSmM02wPhv9kfXZJpwCmNKUuzUctdvlTEQKXjdlsJ7t+bDsKAIFP5cI1mffLfwZQiZKcKxcsL6zNRYed4GuywRrKxGCrl+jDwBgK8ECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjL+B69XbUab8vDaAAAAAElFTkSuQmCC';
	}

	/**
	 * The value as expected by Gravity Forms.
	 */
	public function value() {
		return [ $this->fields[0]['id'] => $this->field_value ];
	}

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on SignatureField {
				adminLabel
				backgroundColor
				borderColor
				borderStyle
				borderWidth
				boxWidth
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
				description
				descriptionPlacement
				errorMessage
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				penColor
				penSize
				value
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: String!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, value: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on SignatureField {
									value
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
	public function update_entry_mutation() : string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: String! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on SignatureField {
									value
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
	public function update_draft_entry_mutation() : string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: String! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, value: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on SignatureField {
									value
								}
							}
						}
					}
				}
			}
		';
	}

	/**
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 */
	public function expected_field_response( array $form ) : array {
		$expected   = $this->getExpectedFormFieldValues( $form['fields'][0] );
		$expected[] = $this->expected_field_value( 'value', $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] );

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
	 * @return array
	 */
	public function expected_mutation_response( string $mutationName, $value ) : array {
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
											$this->expected_field_value( 'value', static::NOT_FALSY ),
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
	public function check_saved_values( $actual_entry, $form ) : void {
		$this->assertStringEndsWith( $this->field_value, $actual_entry[ $form['fields'][0]->id ], 'Submit mutation entry value not equal' );
	}
}
