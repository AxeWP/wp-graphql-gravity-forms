<?php
/**
 * Checks updates via Github Releases.
 *
 * @package WPGraphQL\GF
 * @since   0.11.0
 */

namespace WPGraphQL\GF;

use Puc_v4_Factory;
use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - Update Checker
 */
class UpdateChecker implements Hookable {
	/**
	 * Registers hooks to WordPress.
	 */
	public static function register_hooks(): void {
		add_filter( 'auto_update_plugin', [ self::class, 'disable_autoupdates' ], 10, 2 );
		add_action( 'admin_init', [ self::class, 'check_updates' ] );
		add_action( 'in_plugin_update_message-wp-graphql-gravity-forms/wp-graphql-gravity-forms.php', [ self::class, 'in_plugin_update_message' ], 10, 2 );
	}

	/**
	 * Checks github for latest release.
	 */
	public static function check_updates(): void {
		/**
		 * Filters the repo url used in the update checker.
		 *
		 * Useful for checking updates against a fork.
		 *
		 * @see https://github.com/YahnisElsts/plugin-update-checker#github-integration
		 *
		 * @param string           $repo_link The url to the repo.
		 */
		$repo_link = apply_filters( 'graphql_gf_update_repo_url', 'https://github.com/harness-software/wp-graphql-gravity-forms/' );

		/** @var \Puc_v4p13_Vcs_PluginUpdateChecker */
		$update_checker = Puc_v4_Factory::buildUpdateChecker(
			trailingslashit( $repo_link ),
			WPGRAPHQL_GF_PLUGIN_FILE,
			'wp-graphql-gravity-forms',
		);

		// @phpstan-ignore-next-line
		$update_checker->getVcsApi()->enableReleaseAssets();
	}

	/**
	 * Disable autoupdates if breaking release.
	 *
	 * @param boolean|null $update .
	 * @param object       $item .
	 *
	 * @return boolean|null
	 */
	public static function disable_autoupdates( $update, $item ) {
		// Bail early while respecting user filters.
		if ( isset( $item->slug ) && 'wp-graphql-gravity-forms' === $item->slug ) {
			return false;
		}

		return $update;
	}

	/**
	 * Display notice on plugin screen.
	 *
	 * @param array  $plugin_data .
	 * @param object $response .
	 */
	public static function in_plugin_update_message( array $plugin_data, $response ): void {
		if ( empty( $response->new_version ) ) {
			return;
		}
		$new_version = $response->new_version;

		$current_version_parts = explode( '.', WPGRAPHQL_GF_VERSION );
		$new_version_parts     = explode( '.', $new_version );

		// Return early if user is moving to minor release.
		if ( version_compare( $current_version_parts[0] . '.' . $current_version_parts[1], $new_version_parts[0] . '.' . $new_version_parts[1], '=' ) ) {
			return;
		}

		// translators: %s: version number.
		$message = sprintf( __( '<strong>Warning!</strong> Version %s may contain breaking changes. Please review the <a href="https://github.com/harness-software/wp-graphql-gravity-forms/releases" target="_blank">release notes</a> before upgrading.', 'wp-graphql-gravity-forms' ), $new_version );

		echo '</p></div><div class="notice inline notice-error notice-alt"><p>' . wp_kses_post( $message );
	}
}
