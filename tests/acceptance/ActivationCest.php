<?php

class ActivationCest {
	/**
	 * Tests activation and deactivation.
	 */
	public function testActivation( AcceptanceTester $I ) {
		$slug = 'wp-graphql-for-gravity-forms';

		$I->wantTo( 'activate and deactivate the plugin correctly' );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		// Log the page content.
		codecept_debug( $I->grabPageSource() );

		$I->seePluginInstalled( $slug );

		$I->activatePlugin( $slug );

		$I->seePluginActivated( $slug );

		$I->deactivatePlugin( $slug );

		$I->seePluginDeactivated( $slug );

		$I->activatePlugin( $slug );

		$I->seePluginActivated( $slug );
	}
}
