<?php
/**
 * Tests for Phone field format properties.
 *
 * @package WPGraphQL\GF\Tests\WPUnit
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class PhoneFieldFormatTest
 */
class PhoneFieldFormatTest extends GFGraphQLTestCase {
	/**
	 * The ID of the form created for testing.
	 *
	 * @var int
	 */
	private $form_id;

	/**
	 * {@inheritDoc}
	 */
	public function setUp(): void {
		parent::setUp();

		wp_set_current_user( $this->admin->ID );
		$this->clearSchema();
	}

	/**
	 * {@inheritDoc}
	 */
	public function tearDown(): void {
		$this->factory->form->delete( $this->form_id );

		parent::tearDown();
	}

	/**
	 * Helper method to create a form with a phone field with the given properties.
	 *
	 * @param array $props The properties for the phone field.
	 *
	 * @return int The ID of the created form.
	 */
	private function create_phone_form( array $props ): int {
		$field_helper = $this->tester->getPropertyHelper( 'PhoneField', $props );
		$field        = $this->factory->field->create( $field_helper->values );

		return $this->factory->form->create(
			array_merge(
				[ 'fields' => [ $field ] ],
				$this->tester->getFormDefaultArgs()
			)
		);
	}

	/**
	 * Test that the standard phone format returns all properties.
	 */
	public function testStandardPhoneFormat(): void {
		$this->form_id = $this->create_phone_form( [ 'phoneFormat' => 'standard' ] );

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on PhoneField {
								phoneFormatType
								phoneFormat {
									label
									mask
									regex
									instruction
									type
								}
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'STANDARD', $response['data']['gfForm']['formFields']['nodes'][0]['phoneFormatType'] );

		$properties = $response['data']['gfForm']['formFields']['nodes'][0]['phoneFormat'];
		$this->assertNotNull( $properties );
		$this->assertNotNull( $properties['label'] );
		$this->assertNotNull( $properties['mask'] );
		$this->assertNotNull( $properties['regex'] );
		$this->assertNotNull( $properties['instruction'] );
		$this->assertEquals( 'STANDARD', $properties['type'] );
	}

	/**
	 * Test that the international phone format returns null for mask, regex, and instruction.
	 */
	public function testInternationalPhoneFormat(): void {
		$this->form_id = $this->create_phone_form( [ 'phoneFormat' => 'international' ] );

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on PhoneField {
								phoneFormatType
								phoneFormat {
									label
									mask
									regex
									instruction
									type
								}
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'INTERNATIONAL', $response['data']['gfForm']['formFields']['nodes'][0]['phoneFormatType'] );

		$properties = $response['data']['gfForm']['formFields']['nodes'][0]['phoneFormat'];
		$this->assertNotNull( $properties );
		$this->assertNotNull( $properties['label'] );
		$this->assertNull( $properties['mask'] );
		$this->assertNull( $properties['regex'] );
		$this->assertNull( $properties['instruction'] );
		$this->assertEquals( 'INTERNATIONAL', $properties['type'] );
	}

	/**
	 * Test that a custom phone format added via filter is supported.
	 */
	public function testCustomPhoneFormat(): void {
		$custom_format = [
			'label'       => 'UK Format',
			'mask'        => '+44 9999 999999',
			'regex'       => '/^\+44\s?\d{4}\s?\d{6}$/',
			'instruction' => '+44 XXXX XXXXXX',
		];

		add_filter(
			'gform_phone_formats',
			static function ( $formats ) use ( $custom_format ) {
				$formats['uk'] = $custom_format;
				return $formats;
			}
		);

		// Use the property helper but override phoneFormat after creation.
		$field_helper       = $this->tester->getPropertyHelper( 'PhoneField' );
		$field              = $this->factory->field->create( $field_helper->values );
		$field->phoneFormat = 'uk';

		$this->form_id = $this->factory->form->create(
			array_merge(
				[ 'fields' => [ $field ] ],
				$this->tester->getFormDefaultArgs()
			)
		);

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on PhoneField {
								phoneFormat {
									label
									mask
									regex
									instruction
								}
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );

		$properties = $response['data']['gfForm']['formFields']['nodes'][0]['phoneFormat'];
		$this->assertNotNull( $properties );
		$this->assertEquals( $custom_format['label'], $properties['label'] );
		$this->assertEquals( $custom_format['mask'], $properties['mask'] );
		$this->assertEquals( $custom_format['regex'], $properties['regex'] );
		$this->assertEquals( $custom_format['instruction'], $properties['instruction'] );

		remove_all_filters( 'gform_phone_formats' );
	}
}
