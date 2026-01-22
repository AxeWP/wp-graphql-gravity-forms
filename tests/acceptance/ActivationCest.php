<?php

class ActivationCest {
	/**
	 * Tests activation and deactivation.
	 */
	public function testActivation( AcceptanceTester $I ) {
		$slug = 'wp-graphql-gravity-forms';

		$I->wantTo( 'activate and deactivate the plugin correctly' );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->seePluginInstalled( $slug );
		$I->seePluginActivated( $slug );

		$I->deactivatePlugin( $slug );

		// For some reason, data-slug changes after live-refresh.
		$I->seePluginDeactivated( 'wpgraphql-for-gravity-forms' );

		$I->activatePlugin( 'wpgraphql-for-gravity-forms' );
		$I->seePluginActivated( $slug );
	}
}
