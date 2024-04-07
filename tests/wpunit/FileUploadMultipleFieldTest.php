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

/**
 * Class -FileUploadMultipleFieldTest
 */
class FileUploadMultipleFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Set up.
	 */
	public function setUp(): void {
		// Before...

		copy( __DIR__ . '/../_support/files/img1.png', '/tmp/img1.png' );
		$stat  = stat( dirname( '/tmp/img1.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img1.png', $perms );
		copy( __DIR__ . '/../_support/files/img2.png', '/tmp/img2.png' );
		$stat  = stat( dirname( '/tmp/img2.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img2.png', $perms );
		copy( __DIR__ . '/../_support/files/img2.png', '/tmp/img3.png' );
		$stat  = stat( dirname( '/tmp/img3.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img3.png', $perms );
		copy( __DIR__ . '/../_support/files/img2.png', '/tmp/img4.png' );
		$stat  = stat( dirname( '/tmp/img4.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img4.png', $perms );

		parent::setUp();

		global $_gf_uploaded_files;
		$_gf_uploaded_files = [];
	}

	public function tearDown(): void {
		GFFormsModel::delete_files( $this->entry_id, $this->factory->form->get_object_by_id( $this->form_id ) );

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
		return $this->tester->getPropertyHelper( 'FileUploadField', [ 'multipleFiles' => true ] );
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
		$field_value_input = $this->field_value_input();
		return [
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'filename' => $field_value_input[0]['name'],
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[0]['name'],
			],
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'filename' => $field_value_input[1]['name'],
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[1]['name'],
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
		return [
			$this->fields[0]['id'] => wp_json_encode(
				[
					$this->field_value()[0]['url'],
					$this->field_value()[1]['url'],
				]
			),
		];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		$field_value_input = $this->updated_field_value_input();
		return [
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'filename' => $field_value_input[0]['name'],
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[0]['name'],
			],
			[
				'baseUrl'  => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
				'filename' => $field_value_input[1]['name'],
				'url'      => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input[1]['name'],
			],
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
	 */
	public function field_query(): string {
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
	 */
	public function submit_form_mutation(): string {
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
	 * {@inheritDoc}
	 */
	public function expected_field_response( array $form ): array {
		$expected = $this->getExpectedFormFieldValues( $form['fields'][0] );

		$urls = json_decode( $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] );

		$expected_field_value    = $this->field_value;
		$expected_field_value[0] = array_merge(
			$expected_field_value[0],
			[
				'url' => $urls[0],
			]
		);
		$expected[]              = $this->expected_field_value(
			'fileUploadValues',
			$expected_field_value
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
	 */
	public function expected_mutation_response( string $mutationName, $value ): array {
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$urls = ! $this->is_draft ? json_decode( $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] ) : [];

		if ( $this->is_draft ) {
			$expected[] = $this->expected_field_value( 'fileUploadValues.0', null );
		} else {
			$value[0]   = array_merge(
				$value[0],
				[ 'url' => ! empty( $urls[0] ) ? $urls[0] : self::IS_NULL ]
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

		$actual_files = json_decode( $actual_entry[ $form['fields'][0]['id'] ], true );

		$this->assertStringEndsWith( $ends_with, $actual_files[0], 'Submit mutation entry value not equal.' );
	}
}
