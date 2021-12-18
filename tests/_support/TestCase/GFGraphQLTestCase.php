<?php
/**
 * WPGraphQL test case
 *
 * For testing WPGraphQL responses.
 *
 * @since 0.8.0
 * @package Tests\WPGraphQL\TestCase
 */

namespace Tests\WPGraphQL\GF\TestCase;

use WPGraphQL\GF\Type\Enum;

/**
 * Class - GraphQLTestCase
 */
class GFGraphQLTestCase extends \Tests\WPGraphQL\TestCase\WPGraphQLTestCase {
	/**
	 * Holds the User ID of a user whith the "admin" role. For use through the tests for the purpose of testing user access levels.
	 *
	 * @var WP_User
	 */
	protected $admin;

	/**
	 * Holds a helper class to easily get default properties.
	 *
	 * @var object .
	 */
	protected $property_helper;

	/**
	 * Creates users and loads factories.
	 */
	public function setUp() : void {
		parent::setUp();

		// Load factories.
		$factories = [
			'DraftEntry',
			'Entry',
			'Field',
			'Form',
		];

		foreach ( $factories as $factory ) {
			$factory_name                   = strtolower( preg_replace( '/\B([A-Z])/', '_$1', $factory ) );
			$factory_class                  = '\\Tests\\WPGraphQL\\GF\\Factory\\' . $factory;
			$this->factory->{$factory_name} = new $factory_class( $this->factory );
		}

		$this->admin = $this->factory()->user->create_and_get( [ 'role' => 'administrator' ] );
		$this->admin->add_cap( 'gravityforms_view_entries' );
		$this->admin->add_cap( 'gravityforms_delete_entries' );
	}

	/**
	 * Post test tear down.
	 */
	public function tearDown(): void {
		// Your tear down methods here.
		wp_delete_user( $this->admin->id );

		// Then...
		parent::tearDown();
	}

	/**
	 * Programmatically generate an expectedField array for assertions.
	 *
	 * @param array $value_array .
	 * @return array
	 */
	protected function get_expected_fields( $value_array ): array {
		$expected = [];
		foreach ( $value_array as $key => $value ) {
			$expected[] = $this->expectedField( $key, $value );
		}
		return $expected;
	}

	protected function get_expected_conditional_logic_fields( $conditional_logic ) {
		return $this->expectedObject(
			'conditionalLogic',
			[
				$this->expectedField( 'actionType', $this->tester->get_enum_for_value( Enum\ConditionalLogicActionTypeEnum::$type, $conditional_logic['actionType'] ) ),
				$this->expectedField(
					'logicType',
					$this->tester->get_enum_for_value( Enum\ConditionalLogicLogicTypeEnum::$type, $conditional_logic['logicType'] ),
					$this->expectedObject(
						'rules',
						[
							$this->expectedNode(
								'0',
								[
									$this->expectedField( 'fieldId', $conditional_logic['rules'][0]['fieldId'] ),
									$this->expectedField( 'operator', $conditional_logic['rules'][0]['operator'] ),
									$this->expectedField( 'value', $conditional_logic['rules'][0]['value'] ),
								]
							),
						]
					),
				),
			]
		);
	}
}
