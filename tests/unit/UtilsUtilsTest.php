<?php
/**
 * Tests Utils functions.
 *
 * @package .
 */

use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - UtilsUtilsTest
 */
class UtilsUtilsTest extends \Codeception\Test\Unit {
		/**
		 * Tests that deprecationReason is added to the property definition.
		 */
	public function testDeprecateProperty() : void {
		$property = [
			'propertyName' => [
				'type'        => 'String',
				'description' => 'Some description',
			],
		];

		$deprecationReason = 'This is a test deprecation';

		$deprecatedProperty = Utils::deprecate_property( $property, $deprecationReason );

		$this->assertEquals( $deprecationReason, $deprecatedProperty['propertyName']['deprecationReason'] );
	}

	/**
	 * Tests Utils::to_snake_case() .
	 */
	public function testToSnakeCase() {
		$expected = 'test_string_case';

		$string        = 'testStringCase';
		$to_snake_case = Utils::to_snake_case( $string );
		$this->assertEquals( $expected, $to_snake_case );

		$string        = 'TestStringCase';
		$to_snake_case = Utils::to_snake_case( $string );
		$this->assertEquals( $expected, $to_snake_case );
	}

	/**
	 * Tests Utils::truncate() .
	 */
	public function testTruncate() {
		$string = 'this is a string';

		$truncated = Utils::truncate( $string, 4 );
		$this->assertEquals( 'this', $truncated );

		$truncate = Utils::truncate( $string, 16 );
		$this->assertEquals( $string, $truncate );
	}
}
