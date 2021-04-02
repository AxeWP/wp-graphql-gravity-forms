<?php

use WPGraphQLGravityForms\Utils\Utils;

class UtilsUtilsTest extends \Codeception\Test\Unit {


		/**
		 * Tests that deprecationReason is added to the property definition.
		 *
		 * @throws Exception
		 */
	public function testDeprecateProperty() {
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

	public function testToSnakeCase() {
		$expected = 'test_string_case';

		$string        = 'testStringCase';
		$to_snake_case = Utils::to_snake_case( $string );

		$this->assertEquals( $expected, $to_snake_case );

		$string        = 'TestStringCase';
		$to_snake_case = Utils::to_snake_case( $string );
		$this->assertEquals( $expected, $to_snake_case );
	}
}
