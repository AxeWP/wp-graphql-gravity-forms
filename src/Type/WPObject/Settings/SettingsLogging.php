<?php
/**
 * GraphQL Object Type - Logging Settings
 *
 * @package WPGraphQL\GF\Type
 * @since   0.10.2
 */

namespace WPGraphQL\GF\Type\WPObject\Settings;

use GFLogging;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - SettingsLogging
 */
class SettingsLogging extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfSettingsLogging';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms Logging Settings.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isLoggingEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether Gravity Forms internal logging is enabled. Logging allows you to easily debug the inner workings of Gravity Forms to solve any possible issues.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => (bool) get_option( 'gform_enable_logging' ),
			],
			'loggers'          => [
				'type'        => [ 'list_of' => Logger::$type ],
				'description' => __( 'A list of registered Gravity Forms loggers and their configurations.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function () {
					$logging_instance  = GFLogging::get_instance();
					$settings          = $logging_instance->get_plugin_settings();
					$supported_plugins = $logging_instance->get_supported_plugins();

					if ( false === $settings ) {
						return null;
					}

					// Add plugin name to array.
					return array_map(
						static function ( $key, $setting ) use ( $supported_plugins ) {
							$setting['name'] = $supported_plugins[ $key ];
							return $setting;
						},
						array_keys( $settings ),
						$settings
					);
				},
			],
		];
	}
}
