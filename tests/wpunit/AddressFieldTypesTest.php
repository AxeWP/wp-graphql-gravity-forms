<?php
/**
 * Tests for Address field type issues.
 *
 * @package WPGraphQL\GF\Tests\WPUnit
 */

use Tests\WPGraphQL\GF\TestCase\GFGraphQLTestCase;

/**
 * Class AddressFieldTypesTest
 */
class AddressFieldTypesTest extends GFGraphQLTestCase {
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
	 * Helper method to create a form with an address field with the given properties.
	 *
	 * @param array $props The properties for the address field.
	 *
	 * @return int The ID of the created form.
	 */
	private function create_address_form( array $props ): int {
		$field_helper = $this->tester->getPropertyHelper( 'AddressField', $props );
		$field        = $this->factory->field->create( $field_helper->values );

		return $this->factory->form->create(
			array_merge(
				[ 'fields' => [ $field ] ],
				$this->tester->getFormDefaultArgs()
			)
		);
	}

	/**
	 * Test that the US address type returns the correct address type and default state, and that default province is null.
	 */
	public function testUsAddressType(): void {
		$this->form_id = $this->create_address_form(
			[
				'addressType'     => 'us',
				'defaultState'    => 'Michigan',
				'defaultProvince' => 'Ontario',
			]
		);

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on AddressField {
								addressType
								defaultState
								defaultProvince
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'US', $response['data']['gfForm']['formFields']['nodes'][0]['addressType'] );
		$this->assertNotNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultState'] );
		$this->assertEquals( 'MICHIGAN', $response['data']['gfForm']['formFields']['nodes'][0]['defaultState'] );
		$this->assertNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
	}

	/**
	 * Test that the Canadian address type returns the correct address type and default province, and that default state is null.
	 */
	public function testCanadianAddressTypeWithProvince(): void {
		$this->form_id = $this->create_address_form(
			[
				'addressType'     => 'canadian',
				'defaultProvince' => 'Ontario',
				'defaultState'    => 'Michigan',
			]
		);

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on AddressField {
								addressType
								defaultState
								defaultProvince
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'CANADA', $response['data']['gfForm']['formFields']['nodes'][0]['addressType'] );
		$this->assertNotNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
		$this->assertEquals( 'ONTARIO', $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
		$this->assertNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultState'] );
	}

	/**
	 * Test that the Canadian address type returns the correct address type and default province,
	 * and that default state is null, when default province is set but default state is not set.
	 */
	public function testCanadianAddressTypeWithState(): void {
		$this->form_id = $this->create_address_form(
			[
				'addressType'     => 'canadian',
				'defaultProvince' => null,
				'defaultState'    => 'Ontario',
			]
		);

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on AddressField {
								addressType
								defaultState
								defaultProvince
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'CANADA', $response['data']['gfForm']['formFields']['nodes'][0]['addressType'] );
		$this->assertNotNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
		$this->assertEquals( 'ONTARIO', $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
		$this->assertNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultState'] );
	}

	/**
	 * Test that the international address type returns the correct address type and that default state and default province are null.
	 */
	public function testInternationalAddressType(): void {
		$this->form_id = $this->create_address_form(
			[
				'addressType'     => 'international',
				'defaultState'    => 'Michigan',
				'defaultProvince' => 'Ontario',
			]
		);

		$query = '
			query ($id: ID!) {
				gfForm(id: $id, idType: DATABASE_ID) {
					formFields {
						nodes {
							... on AddressField {
								addressType
								defaultState
								defaultProvince
							}
						}
					}
				}
			}
		';

		$variables = [ 'id' => $this->form_id ];
		$response  = $this->graphql( compact( 'query', 'variables' ) );

		$this->assertArrayNotHasKey( 'errors', $response );
		$this->assertEquals( 'INTERNATIONAL', $response['data']['gfForm']['formFields']['nodes'][0]['addressType'] );
		$this->assertNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultState'] );
		$this->assertNull( $response['data']['gfForm']['formFields']['nodes'][0]['defaultProvince'] );
	}
}
