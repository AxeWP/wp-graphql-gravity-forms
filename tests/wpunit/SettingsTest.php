<?php

use WPGraphQL\GF\GF;

class SettingsTest extends \Tests\WPGraphQL\TestCase\WPGraphQLTestCase {
	/**
	 * @var \WpunitTesterActions
	 */
	protected $tester;
	public $instance;
	/**
	 * Provides access to dummy functions.
	 *
	 * @var Dummy
	 */
	public $dummy;
	public array $options;

	public function setUp(): void {
		require_once __DIR__ . '/../_support/Helper/GFHelpers/inc/Dummy.php';
		$this->dummy = new Dummy();

		// Before...
		parent::setUp();

		$this->options = [
			'currency'                => [ 'rg_gforms_currency' => 'ILS' ],
			'hasBackgroundUpdates'    => [ 'gform_enable_background_updates' => $this->dummy->yesno() ],
			'hasDefaultCss'           => [ 'rg_gforms_disable_css' => $this->dummy->yesno() ],
			'hasToolbar'              => [ 'gform_enable_toolbar_menu' => $this->dummy->yesno() ],
			'isHtml5Enabled'          => [ 'rg_gforms_enable_html5' => $this->dummy->yesno() ],
			'isLoggingEnabled'        => [ 'gform_enable_logging' => $this->dummy->yesno() ],
			'isNoConflictModeEnabled' => [ 'gform_enable_noconflict' => $this->dummy->yesno() ],
		];

		foreach ( $this->options as $key => $setting ) {
			$key = key( $setting );
			update_option( $key, $setting[ $key ] );
		}
		\WPGraphQL::clear_schema();
	}

	public function tearDown(): void {
		// Your tear down methods here.

		unset( $this->instance );
		\WPGraphQL::clear_schema();

		// Then...
		parent::tearDown();
	}

	// Tests.
	/**
	 * Tests GfSettings object.
	 */
	public function testSettings() {
		$query = '
			query gfSettings {
				gfSettings {
					currency
					hasBackgroundUpdates
					hasDefaultCss
					hasToolbar
					isHtml5Enabled
					isNoConflictModeEnabled
					logging {
						isLoggingEnabled
						loggers {
							isEnabled
							name
						}
					}
				}
			}
		';

		$response = $this->graphql( compact( 'query' ) );
		$this->assertArrayNotHasKey( 'errors', $response, 'field has errors' );

		$expected = $this->expected_field_response();

		$this->assertQuerySuccessful( $response, $expected );
	}

	public function expected_field_response() : array {
		return [
			$this->expectedObject(
				'gfSettings',
				[
					$this->expectedField( 'currency', $this->options['currency']['rg_gforms_currency'] ),
					$this->expectedField( 'hasBackgroundUpdates', $this->options['hasBackgroundUpdates']['gform_enable_background_updates'] ),
					$this->expectedField( 'hasDefaultCss', ! $this->options['hasDefaultCss']['rg_gforms_disable_css'] ),
					$this->expectedField( 'hasToolbar', $this->options['hasToolbar']['gform_enable_toolbar_menu'] ),
					$this->expectedField( 'isHtml5Enabled', $this->options['isHtml5Enabled']['rg_gforms_enable_html5'] ),
					$this->expectedField( 'isNoConflictModeEnabled', $this->options['isNoConflictModeEnabled']['gform_enable_noconflict'] ),
					$this->expectedObject(
						'logging',
						[
							$this->expectedField( 'isLoggingEnabled', $this->options['isLoggingEnabled']['gform_enable_logging'] ),
						]
					),

				]
			),
		];
	}
}
