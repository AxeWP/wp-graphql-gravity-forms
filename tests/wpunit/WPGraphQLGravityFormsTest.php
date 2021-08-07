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
		\WPGraphQL::clear_schema();
		$this->instance = new WPGraphQLGravityForms();
	}

	public function tearDown(): void {
		// Your tear down methods here.

		unset( $this->wPGraphQLGravityForms );
		\WPGraphQL::clear_schema();

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
}
