<?php
/**
 * Test FileUploadField.
 *
 * @package Tests\WPGraphQL\GF
 */

namespace Tests\WPGraphQL\GF;

use GFFormsModel;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
use WPGraphQL\GF\Utils\GFUtils;

class FooFileUpload extends \GF_Field_FileUpload {
	public function upload_file( $form_id, $file ) {
		\GFCommon::log_debug( __METHOD__ . '(): Uploading file: ' . $file['name'] );
		$target = GFFormsModel::get_file_upload_path( $form_id, $file['name'] );
		if ( ! $target ) {
			\GFCommon::log_debug( __METHOD__ . '(): FAILED (Upload folder could not be created.)' );

			return 'FAILED (Upload folder could not be created.)';
		}
		\GFCommon::log_debug( __METHOD__ . '(): Upload folder is ' . print_r( $target, true ) );

		if ( copy( $file['tmp_name'], $target['path'] ) ) {
			\GFCommon::log_debug( __METHOD__ . '(): File ' . $file['tmp_name'] . ' successfully moved to ' . $target['path'] . '.' );
			$this->set_permissions( $target['path'] );

			return $target['url'];
		} else {
			\GFCommon::log_debug( __METHOD__ . '(): FAILED (Temporary file ' . $file['tmp_name'] . ' could not be copied to ' . $target['path'] . '.)' );

			return 'FAILED (Temporary file could not be copied.)';
		}
	}
}
/**
 * Class -FileUploadFieldTest
 */
class FileUploadFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Set up.
	 */
	public function setUp(): void {
		// Before...

		copy( dirname( __DIR__ ) . '/_support/files/img1.png', '/tmp/img1.png' );
		$stat  = stat( dirname( '/tmp/img1.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img1.png', $perms );
		copy( dirname( __DIR__ ) . '/_support/files/img2.png', '/tmp/img2.png' );
		$stat  = stat( dirname( '/tmp/img2.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img2.png', $perms );
		add_filter( 'gform_gf_field_create', [ $this, 'mock_file_upload_field' ], 10, 2 );

		parent::setUp();

		global $_gf_uploaded_files;
		$_gf_uploaded_files = [];
	}

	public function tearDown(): void {
		GFFormsModel::delete_files( $this->entry_id, $this->factory->form->get_object_by_id( $this->form_id ) );
		remove_filter( 'gform_gf_field_create', [ $this, 'mock_file_upload_field' ] );

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
	public function testUpdateDraft():void {
		$this->runTestUpdateDraft();
	}

	/**
	 * Sets the correct Field Helper.
	 */
	public function field_helper() {
		return $this->tester->getPropertyHelper( 'FileUploadField' );
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
		$field_value_input = $this->field_value_input();
		return [
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'filename' => $field_value_input[0]['name'],
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[0]['name'],
			],
		];
	}

	public function draft_field_value() {
		return null;
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
		];
	}

	/**
	 * Sets the value as expected by Gravity Forms.
	 */
	public function value() {
		return [ $this->fields[0]['id'] => $this->field_value()[0]['url'] ];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		$field_value_input = $this->updated_field_value_input();
		return [
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[0]['name'],
				'filename' => $field_value_input[0]['name'],
			],
		];
	}

		/**
		 * The graphql field value input.
		 */
	public function updated_field_value_input() {
		return [
			[
				'name'     => 'img2.png',
				'type'     => 'image/png',
				'size'     => filesize( '/tmp/img2.png' ),
				'tmp_name' => '/tmp/img2.png',
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
				fileUploadValues {
					baseUrl
					filename
					url
				}
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
									fileUploadValues {
										baseUrl
										filename
										url
									}
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
									fileUploadValues {
										baseUrl
										filename
										url
									}
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
									fileUploadValues {
										baseUrl
										filename
										url
									}
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

		$expected_field_value    = $this->field_value;
		$expected_field_value[0] = array_merge(
			$expected_field_value[0],
			[
				'url' => $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ],
			]
		);

		$expected[] = $this->expected_field_value( 'fileUploadValues', $expected_field_value );

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
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$url = ! $this->is_draft ? $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] : null;

		if ( $this->is_draft ) {
			$expected[] = $this->expected_field_value( 'fileUploadValues.0', null );
		} else {
			$value[0]   = array_merge(
				$value[0],
				[ 'url' => $url ?: self::IS_NULL ]
			);
			$expected[] = $this->expected_field_value( 'fileUploadValues.0', $value[0] );
		}

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
										$expected,
										0
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
		$ends_with = preg_replace( '/(.*?)gravity_forms\/(.*?)\/(.*?)/', '$3', $this->field_value[0]['url'] );
		$this->assertStringEndsWith( $ends_with, $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry value not equal.' );
	}

	public function mock_file_upload_field( $field, $properties ) {
		if ( $field->type !== 'fileupload' || $field instanceof FooFileUpload ) {
			return $field;
		}

		return new FooFileUpload( $properties );
	}
}
