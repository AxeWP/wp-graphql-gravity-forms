<?php

use WPGraphQLGravityForms\WPGraphQLGravityForms;

class WPGraphQLGravityFormsTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;

		public $instance;

	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->instance = new WPGraphQLGravityForms();
	}

	public function tearDown(): void {
		// Your tear down methods here.

		unset( $this->wPGraphQLGravityForms );
		// Then...
		parent::tearDown();
	}

	// Tests
	public function testInstance() {
		$this->assertTrue( $this->instance instanceof WPGraphQLGravityForms );
	}

	public function testRun() {
		$this->instance->run();

		$this->assertTrue( true );
	}

	public function testGetEnabledFieldTypes() {
		$fields = $this->instance::get_enabled_field_types();
		$this->assertIsArray( $fields );
	}
}
