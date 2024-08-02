<?php
/**
 * Includes the composer Autoloader used for packages and classes in the src/ directory.
 *
 * @package WPGraphQL\GF
 * @since 0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF;

/**
 * Class - Autoloader
 *
 * @internal
 */
class Autoloader {
	/**
	 * Whether the autoloader has been loaded.
	 *
	 * @var bool
	 */
	protected static bool $is_loaded = false;

	/**
	 * Attempts to autoload the Composer dependencies.
	 */
	public static function autoload(): bool {
		// If we're not *supposed* to autoload anything, then return true.
		if ( defined( 'WPGRAPHQL_GF_AUTOLOAD' ) && false === WPGRAPHQL_GF_AUTOLOAD ) {
			return true;
		}

		// If the autoloader has already been loaded, then return true.
		if ( self::$is_loaded ) {
			return self::$is_loaded;
		}

		// If the main class has already been loaded, then they must be using a different autoloader.
		if ( class_exists( 'WPGraphQL\GF\GF' ) ) {
			return true;
		}

		$autoloader      = dirname( __DIR__ ) . '/vendor/autoload.php';
		self::$is_loaded = self::require_autoloader( $autoloader );

		return self::$is_loaded;
	}

	/**
	 * Attempts to load the autoloader file, if it exists.
	 *
	 * @param string $autoloader_file The path to the autoloader file.
	 */
	protected static function require_autoloader( string $autoloader_file ): bool {
		if ( ! is_readable( $autoloader_file ) ) {
			self::missing_autoloader_notice();
			return false;
		}

		return (bool) require_once $autoloader_file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable -- Autoloader is a Composer file.
	}

	/**
	 * Displays a notice if the autoloader is missing.
	 */
	protected static function missing_autoloader_notice(): void {
		// Translators: %s is a link to the latest release file.
		$error_message = __( 'WPGraphQL for GF: The Composer autoloader was not found. This usually means you downloaded the repository source code instead of the latest %s release file. If you are intentionally using the GitHub source code, make sure to run `composer install`.', 'wp-graphql-gravity-forms' );

		$release_link = '<a href="https://github.com/axewp/wp-graphql-gravity-forms/releases/latest/download/wp-graphql-gravity-forms.zip" target="_blank">wp-graphql-gravity-forms.zip</a>';

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- This is a development notice.
				sprintf(
					esc_html( $error_message ),
					wp_kses(
						$release_link,
						[
							'a' => [
								'href'   => [],
								'target' => [],
							],
						]
					)
				)
			);
		}

		$hooks = [
			'admin_notices',
			'network_admin_notices',
		];

		foreach ( $hooks as $hook ) {
			add_action(
				$hook,
				static function () use ( $error_message, $release_link ) {
					?>
					<div class="error notice">
						<p>
							<?php
							printf(
								esc_html( $error_message ),
								wp_kses(
									$release_link,
									[
										'a' => [
											'href'   => [],
											'target' => [],
										],
									]
								)
							)
							?>
						</p>
					</div>
					<?php
				}
			);
		}
	}
}
