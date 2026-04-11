<?php
/**
 * Tests Utils functions.
 *
 * @package .
 */

use WPGraphQL\GF\Utils\Utils;

/**
 * Class - UtilsUtilsTest
 */
class UtilsUtilsTest extends \Codeception\Test\Unit {
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

	public function testMaybeDecodeJson() {
		$value = [
			'someKey' => 'someData',
		];

		$actual = Utils::maybe_decode_json( $value );
		$this->assertEquals( $value, $actual, 'Unable to pass array.' );

		$expected = json_encode( $value );
		$actual   = Utils::maybe_decode_json( $expected );
		$this->assertEquals( $value, $actual, 'Unable to convert associative array.' );

		$value    = [ 'someData', 'someOtherData' ];
		$expected = json_encode( $value );
		$actual   = Utils::maybe_decode_json( $expected );
		$this->assertEquals( $value, $actual, 'Unable to convert non-associative array.' );

		$expected = 235;
		$actual   = Utils::maybe_decode_json( $expected );
		$this->assertFalse( $actual, 'Unable to pass bad value' );

		$value  = 'some-string';
		$actual = Utils::maybe_decode_json( $value );
		$this->assertEquals( [ $value ], $actual, 'Unable to convert string to array' );
	}
}
