<?php
/**
 * Test FileUploadField.
 *
 * @package Tests\WPGraphQL\GF
 */

use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class -FileUploadMultipleFieldTest
 */
class FileUploadMultipleFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Set up.
	 */
	public function setUp(): void {
		// Before...

		copy( dirname( __FILE__ ) . '/../_support/files/img1.png', '/tmp/img1.png' );
		copy( dirname( __FILE__ ) . '/../_support/files/img2.png', '/tmp/img2.png' );
		copy( dirname( __FILE__ ) . '/../_support/files/img2.png', '/tmp/img3.png' );
		copy( dirname( __FILE__ ) . '/../_support/files/img2.png', '/tmp/img4.png' );
		parent::setUp();
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
		return $this->tester->getPropertyHelper( 'FileUploadField', [ 'multipleFiles' => true ] );
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
		return [
			GFUtils::get_gravity_forms_upload_dir( 1 )['url'] . '/' . $this->field_value_input()[0]['name'],
			GFUtils::get_gravity_forms_upload_dir( 1 )['url'] . '/' . $this->field_value_input()[1]['name'],
		];
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return [
			[
				'name'     => 'img1.png',
				'type'     => 'image/png',
				'size'     => filesize( '/tmp/img1.png' ),
				'tmp_name' => '/tmp/img1.png',
			],
			[
				'name'     => 'img2.png',
				'type'     => 'image/png',
				'size'     => filesize( '/tmp/img2.png' ),
				'tmp_name' => '/tmp/img2.png',
			],
		];
	}

	/**
	 * Sets the value as expected by Gravity Forms.
	 */
	public function value() {
		return [ $this->fields[0]['id'] => wp_json_encode( $this->field_value() ) ];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		return [
			GFUtils::get_gravity_forms_upload_dir( 1 )['url'] . '/' . $this->updated_field_value_input()[0]['name'],
			GFUtils::get_gravity_forms_upload_dir( 1 )['url'] . '/' . $this->updated_field_value_input()[1]['name'],
		];
	}

		/**
		 * The graphql field value input.
		 */
	public function updated_field_value_input() {
		return [
			[
				'name'     => 'img3.png',
				'type'     => 'image/png',
				'size'     => filesize( '/tmp/img3.png' ),
				'tmp_name' => '/tmp/img3.png',
			],
			[
				'name'     => 'img4.png',
				'type'     => 'image/png',
				'size'     => filesize( '/tmp/img4.png' ),
				'tmp_name' => '/tmp/img4.png',
			],
		];
	}

	/**
	 * The GraphQL query string.
	 *
	 * @return string
	 */
	public function field_query() : string {
		return '
			... on FileUploadField {
				adminLabel
				allowedExtensions
				canAcceptMultipleFiles
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
				maxFileSize
				maxFiles
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				values
			}
		';
	}

	/**
	 * SubmitForm mutation string.
	 *
	 * @return string
	 */
	public function submit_form_mutation() : string {
		return '
			mutation ($formId: ID!, $fieldId: Int!, $value: [Upload!], $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, fileUploadValues: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on FileUploadField {
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
	 *
	 * @return string
	 */
	public function update_entry_mutation(): string {
		return '
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: [Upload!] ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, fileUploadValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on FileUploadField {
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
	 *
	 * @return string
	 */
	public function update_draft_entry_mutation(): string {
		return '
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: [Upload!] ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, fileUploadValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on FileUploadField {
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
	 * The expected WPGraphQL field response.
	 *
	 * @param array $form the current form instance.
	 * @return array
	 */
	public function expected_field_response( array $form ) : array {
		$expected = $this->getExpectedFormFieldValues( $form['fields'][0] );

		$expected[] = $this->expectedField(
			'values.0',
			$this->field_value[0]
		);
		$expected[] = $this->expectedField(
			'values.1',
			$this->field_value[1]
		);

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
											$this->expectedField( 'values', self::NOT_FALSY ),
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
		$ends_with    = preg_replace( '/(.*?)gravity_forms\/(.*?)\/(.*?)/', '$3', $this->field_value );
		$actual_files = json_decode( $actual_entry[ $form['fields'][0]['id'] ], true );

		foreach ( $ends_with as $index => $filename ) {
			$this->assertStringEndsWith( $filename, $actual_files[ $index ], 'Submit mutation entry value not equal.' );
		}
	}
}
