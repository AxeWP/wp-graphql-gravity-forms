<?php

class ActivationCest {

	// tests
	public function testActivation( AcceptanceTester $I ) {
		$pluginSlug = 'wp-graphql-gravity-forms';

		$I->wantTo( 'activate and deactivate the plugin correctly' );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginActivated( $pluginSlug );
		$I->deactivatePlugin( $pluginSlug );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		// Not sure why the slug changes
		$I->seePluginDeactivated( 'wpgraphql-for-gravity-forms' );
		$I->activatePlugin( 'wpgraphql-for-gravity-forms' );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginActivated( $pluginSlug );
	}
}
