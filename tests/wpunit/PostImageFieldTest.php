<?php
/**
 * Test PostImageField.
 *
 * @package Tests\WPGraphQL\GF
 */

namespace Tests\WPGraphQL\GF;

use GFFormsModel;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCase;
use Tests\WPGraphQL\GF\TestCase\FormFieldTestCaseInterface;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class -PostImageFieldTest
 */
class PostImageFieldTest extends FormFieldTestCase implements FormFieldTestCaseInterface {
	/**
	 * Set up.
	 */
	public function setUp(): void {

		// Before...
		copy( dirname( __FILE__ ) . '/../_support/files/img1.png', '/tmp/img1.png' );
		$stat  = stat( dirname( '/tmp/img1.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img1.png', $perms );
		copy( dirname( __FILE__ ) . '/../_support/files/img2.png', '/tmp/img2.png' );
		$stat  = stat( dirname( '/tmp/img2.png' ) );
		$perms = $stat['mode'] & 0000666;
		chmod( '/tmp/img2.png', $perms );
		parent::setUp();

		global $_gf_uploaded_files;
		$_gf_uploaded_files = [];
	}

	/**
	 * Tear down
	 */
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
		return $this->tester->getPropertyHelper( 'PostImageField' );
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
			'altText'     => $field_value_input['altText'],
			'baseUrl'     => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
			'caption'     => $field_value_input['caption'],
			'description' => $field_value_input['description'],
			'title'       => $field_value_input['title'],
			'url'         => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input['image']['name'],
		];
	}

	/**
	 * The graphql field value input.
	 */
	public function field_value_input() {
		return [
			'altText'     => 'someAlt',
			'caption'     => 'someCaption',
			'description' => 'someDesc',
			'title'       => 'someTitle',
			'image'       => [
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
		$field_value = $this->field_value();
		$value       = implode( '|:|', [ $field_value['url'], $field_value['title'], $field_value['caption'], $field_value['description'], $field_value['altText'] ] );
		return [ $this->fields[0]['id'] => $value ];
	}

	/**
	 * The value as expected in GraphQL when updating from field_value().
	 */
	public function updated_field_value() {
		$field_value_input = $this->updated_field_value_input();
		return [
			'altText'     => $field_value_input['altText'],
			'caption'     => $field_value_input['caption'],
			'description' => $field_value_input['description'],
			'title'       => $field_value_input['title'],
			'baseUrl'     => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/',
			'url'         => GFUtils::get_gravity_forms_upload_dir( $this->form_id )['url'] . '/' . $field_value_input['image']['name'],
		];
	}

		/**
		 * The graphql field value input.
		 */
	public function updated_field_value_input() {
		return [
			'altText'     => 'upated Alt',
			'caption'     => 'upated Caption',
			'description' => 'upated Desc',
			'title'       => 'upated Title',

			'image'       => [
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
			... on PostImageField {
				adminLabel
				allowedExtensions
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
				hasAlt
				hasCaption
				hasDescription
				hasTitle
				imageValues {
					altText
					baseUrl
					caption
					description
					filename
					title
					url
				}
				isFeaturedImage
				isRequired
				label
				labelPlacement
				personalData {
					isIdentificationField
					shouldErase
					shouldExport
				}
				subLabelPlacement
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
			mutation ($formId: ID!, $fieldId: Int!, $value: ImageInput!, $draft: Boolean) {
				submitGfForm( input: { id: $formId, saveAsDraft: $draft, fieldValues: {id: $fieldId, imageValues: $value}}) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on PostImageField {
									imageValues {
										altText
										baseUrl
										caption
										description
										title
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
			mutation updateGfEntry( $entryId: ID!, $fieldId: Int!, $value: ImageInput! ){
				updateGfEntry( input: { id: $entryId, shouldValidate: true, fieldValues: {id: $fieldId, imageValues: $value} }) {
					errors {
						id
						message
					}
					entry {
						formFields {
							nodes {
								... on PostImageField {
									imageValues {
										altText
										baseUrl
										caption
										description
										title
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
			mutation updateGfDraftEntry( $resumeToken: ID!, $fieldId: Int!, $value: ImageInput! ){
				updateGfDraftEntry( input: {id: $resumeToken, idType: RESUME_TOKEN, shouldValidate: true, fieldValues: {id: $fieldId, imageValues: $value} }) {
					errors {
						id
						message
					}
					entry: draftEntry {
						formFields {
							nodes {
								... on PostImageField {
									imageValues {
										altText
										baseUrl
										caption
										description
										title
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

		$expected[] = $this->expected_field_value(
			'imageValues',
			array_merge(
				$this->field_value,
				[ 'url' => $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] ] ?: null
			)
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
		$form = $this->factory->form->get_object_by_id( $this->form_id );

		$url = ! $this->is_draft_mutation ? $this->factory->entry->get_object_by_id( $this->entry_id )[ $form['fields'][0]->id ] : null;

		$expected[] = $this->expected_field_value(
			'imageValues',
			array_merge(
				$value,
				[ 'url' => $url ?: self::IS_NULL ]
			)
		);

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
		$ends_with = preg_replace( '/(.*?)gravity_forms\/(.*?)\/(.*?)/', '$3', $this->field_value['url'] );
		$this->assertStringContainsString( $ends_with, $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry id value not equal.' );
		$this->assertStringContainsString( $this->field_value['altText'], $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry altText value not equal.' );
		$this->assertStringContainsString( $this->field_value['caption'], $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry caption value not equal.' );
		$this->assertStringContainsString( $this->field_value['description'], $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry description value not equal.' );
		$this->assertStringContainsString( $this->field_value['title'], $actual_entry[ $form['fields'][0]['id'] ], 'Submit mutation entry Url value not equal.' );
	}
}
