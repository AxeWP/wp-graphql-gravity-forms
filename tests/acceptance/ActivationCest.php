<?php

class ActivationCest {



	// tests
	public function testActivation( AcceptanceTesterActions $I ) {
			$pluginSlug = 'wpgraphql-for-gravity-forms';

			$I->wantTo( 'activate and deactivate the plugin correctly' );

			$I->loginAsAdmin();

			$I->amOnPluginsPage();

			$I->seePluginActivated( $pluginSlug );

			$I->deactivatePlugin( $pluginSlug );

			$I->seePluginDeactivated( $pluginSlug );

			$I->activatePlugin( $pluginSlug );

			$I->seePluginActivated( $pluginSlug );
	}
}
