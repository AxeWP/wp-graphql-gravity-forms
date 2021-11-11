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
	}

	public function tearDown(): void {
		// Your tear down methods here.

		unset( $this->instance );
		\WPGraphQL::clear_schema();

		// Then...
		parent::tearDown();
	}

	// Tests
	public function testInstance() {
		$this->instance = new WPGraphQLGravityForms();

		$this->assertTrue( $this->instance instanceof WPGraphQLGravityForms );
	}

	public function testInstanceBeforeInstantiation() {
		$instances = WPGraphQLGravityForms::instances();
		codecept_debug( $instances );
		$this->assertNotEmpty( $instances );
	}

	public function testRun() {
		$this->instance = new WPGraphQLGravityForms();
		$this->instance->run();

		$this->assertTrue( true );
	}
}
