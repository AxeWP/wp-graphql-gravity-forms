<?php
/**
 * Settings - WPGatsby Settings.
 *
 * @package WPGraphQL\GF\Extensions\WPGatsby
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\WPGatsby;

use WPGraphQL_Settings_API;

/**
 * Class - Settings
 */
class Settings {
	/**
	 * An instance of the Settings API.
	 *
	 * @var ?\WPGraphQL_Settings_API
	 */
	private static $settings_api;

	/**
	 * The option named used in the settings API.
	 *
	 * @var string
	 */
	public static string $option_name = 'log_gatsby_gf_action';

	/**
	 * The section named used in the settings API.
	 *
	 * @var string
	 */
	public static string $section_name = 'wpgatsby_settings';

	/**
	 * The default GF Action monitor keys.
	 *
	 * @var string[]
	 */
	public static array $default_action_keys = [
		'create_form',
		'update_form',
		'delete_form',
		'create_entry',
		'update_entry',
		'create_draft_entry',
	];

	/**
	 * Gets an instance of the WPGraphQL settings api.
	 */
	public static function get_settings_api(): WPGraphQL_Settings_API {
		if ( ! isset( self::$settings_api ) ) {
			self::$settings_api = new WPGraphQL_Settings_API();
		}

		return self::$settings_api;
	}

	/**
	 * Registers settings to enable/disable action monitoring to Settings>GatsbyJS.
	 */
	public static function register_settings(): void {
		$settings_api = self::get_settings_api();

		$settings_api->add_field(
			self::$section_name,
			[
				'name'    => self::$option_name,
				'label'   => __( 'Monitor Gravity Forms Actions', 'wp-graphql-gravity-forms' ),
				'type'    => 'multicheck',
				'options' => [
					'create_form'        => __( 'Form Creation', 'wp-graphql-gravity-forms' ),
					'update_form'        => __( 'Form Updates', 'wp-graphql-gravity-forms' ),
					'delete_form'        => __( 'Form Deletions', 'wp-graphql-gravity-forms' ),
					'create_entry'       => __( 'Entry Submission', 'wp-graphql-gravity-forms' ),
					'update_entry'       => __( 'Entry Updates', 'wp-graphql-gravity-forms' ),
					'create_draft_entry' => __( 'Draft Entry Creation', 'wp-graphql-gravity-forms' ),
				],
				'default' => array_combine( self::$default_action_keys, self::$default_action_keys ),
			]
		);

		$settings_api->admin_init();
	}

	/**
	 * Gets an array of Gravity Forms actions that should be monitored.
	 */
	public static function get_enabled_actions(): array {
		/** @var array $options */
		$options = get_option( self::$section_name, [] );

		// By default monitor everything.
		if ( ! isset( $options[ self::$option_name ] ) ) {
			$options[ self::$option_name ] = array_combine( self::$default_action_keys, self::$default_action_keys );

			update_option( self::$section_name, $options );
		}

		$enabled_actions = is_array( $options[ self::$option_name ] ) ? $options[ self::$option_name ] : [];

		/**
		 * Filter for overriding the list of enabled actions.
		 * Possible array values: `create_form`, `update_form`, `delete_form`, `create_entry`, `update_entry`.
		 *
		 * @param array $enabled_actions. An array of enabled actions.
		 */
		return apply_filters( 'graphql_gf_gatsby_enabled_actions', array_values( $enabled_actions ) ?: [] );
	}

	/**
	 * Checks whether a specific Gravity Forms action is being monitored.
	 *
	 * @param string $action_name the name of the action triggering.
	 */
	public static function is_action_enabled( string $action_name ): bool {
		$enabled_actions = self::get_enabled_actions();

		return ! empty( $enabled_actions ) && in_array( $action_name, $enabled_actions, true );
	}
}
