<?php
/**
 * Test WPGraphQLSettings
 *
 * @package .
 */

use WPGraphQLGravityForms\Settings\WPGraphQLSettings;

/**
 * Class - WPGraphQLSettingsTest
 */
class WPGraphQLSettingsTest extends \Codeception\Test\Unit {
	public $instance;

	/**
	 * Run before each test.
	 */
	protected function _before() {
			$this->instance = new WPGraphQLSettings();
	}

	/**
	 * Tests setMaxQueryAmount .
	 */
	public function testSetMaxQueryAmount() {
			// Tests if current max_query_amount is larger than 600.
			$query_amount = $this->instance->set_max_query_amount( 900 );
			$this->assertEquals( '900', $query_amount );

			// Tests if current max_query_amount is smaller than 600.
			$query_amount = $this->instance->set_max_query_amount( 400 );
			$this->assertEquals( 600, $query_amount );
	}
}
