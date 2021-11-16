<?php

use WPGraphQL\GF\GF;

class GFTest extends \Codeception\TestCase\WPTestCase {
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
		$this->instance = new GF();

		$this->assertTrue( $this->instance instanceof GF );
	}

	public function testInstanceBeforeInstantiation() {
		$instances = GF::instances();
		codecept_debug( $instances );
		$this->assertNotEmpty( $instances );
	}

	public function testRun() {
		$this->instance = new GF();
		$this->instance->run();

		$this->assertTrue( true );
	}
}
