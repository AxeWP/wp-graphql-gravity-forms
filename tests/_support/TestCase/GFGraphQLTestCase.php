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

use Helper\GFHelpers\GFHelpers;
use RuntimeException;
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
	 * @var \Helper\GFHelpers\PropertyHelper .
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
		global $_gf_state, $_gf_uploaded_files;
		$_gf_state = [];
		unset( $_gf_uploaded_files );

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

	public function get_expected_conditional_logic_fields( $conditional_logic ) {
		if ( empty( $conditional_logic ) ) {
			return $this->expectedField( 'conditionalLogic', static::IS_NULL );
		}

		return $this->expectedObject(
			'conditionalLogic',
			[
				$this->expectedField( 'actionType', ! empty( $conditional_logic['actionType'] ) ? GFHelpers::get_enum_for_value( Enum\ConditionalLogicActionTypeEnum::$type, $conditional_logic['actionType'] ) : static::IS_NULL ),
				$this->expectedField(
					'logicType',
					! empty( $conditional_logic['actionType'] ) ? GFHelpers::get_enum_for_value( Enum\ConditionalLogicLogicTypeEnum::$type, $conditional_logic['logicType'] ) : static::IS_NULL
				),
				$this->expectedNode(
					'rules',
					[
						$this->expectedField( 'fieldId', ! empty( $conditional_logic['rules'][0]['fieldId'] ) ? (float) $conditional_logic['rules'][0]['fieldId'] : static::IS_NULL ),
						$this->expectedField( 'operator', ! empty( $conditional_logic['rules'][0]['operator'] ) ? GFHelpers::get_enum_for_value( Enum\FormRuleOperatorEnum::$type, $conditional_logic['rules'][0]['operator'] ) : static::IS_NULL ),
						$this->expectedField( 'value', ! empty( $conditional_logic['rules'][0]['value'] ) ? $conditional_logic['rules'][0]['value'] : static::IS_NULL ),
					],
					0
				),
			]
		);
	}
}
