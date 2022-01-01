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
	 * @var ?WPGraphQL_Settings_API
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
	 * Gets an instance of the WPGraphQL settings api.
	 */
	public static function get_settings_api() : WPGraphQL_Settings_API {
		if ( ! isset( self::$settings_api ) ) {
			self::$settings_api = new WPGraphQL_Settings_API();
		}

		return self::$settings_api;
	}

	/**
	 * Registers settings to enable/disable action monitoring to Settings>GatsbyJS.
	 */
	public static function register_settings() : void {
		$settings_api = self::get_settings_api();

		$settings_api->add_field(
			self::$section_name,
			[
				'name'    => self::$option_name,
				'label'   => __( 'Monitor Gravity Forms Actions', 'wp-graphql-gravity-forms' ),
				'type'    => 'multicheck',
				'options' => [
					'create_form'        => 'Form Creation',
					'update_form'        => 'Form Updates',
					'delete_form'        => 'Form Deletions',
					'create_entry'       => 'Entry Submission',
					'update_entry'       => 'Entry Updates',
					'create_draft_entry' => 'Draft Entry Creation',
				],
				'default' => [
					'create_form'        => 'create_form',
					'update_form'        => 'update_form',
					'delete_form'        => 'delete_form',
					'create_entry'       => 'create_entry',
					'update_entry'       => 'update_entry',
					'create_draft_entry' => 'create_draft_entry',
				],
			]
		);

		$settings_api->admin_init();
	}

	/**
	 * Gets an array of Gravity Forms actions that should be monitored.
	 */
	public static function get_enabled_actions() : array {
		$options = get_option( self::$section_name );

		if ( ! isset( $options[ self::$option_name ] ) ) {
			$options[ self::$option_name ] = [
				'create_form'        => 'create_form',
				'update_form'        => 'update_form',
				'delete_form'        => 'delete_form',
				'create_entry'       => 'create_entry',
				'update_entry'       => 'update_entry',
				'create_draft_entry' => 'create_draft_entry',
			];

			update_option( self::$section_name, $options );
		}

		$enabled_actions = $options[ self::$option_name ];

		/**
		 * Filter for overriding the list of enabled actions.
		 * Possible array values: `create_form`, `update_form`, `delete_form`, `create_entry`, `update_entry`.
		 *
		 * @param array $enabled_actions. An array of enabled actions.
		 */
		return apply_filters( 'graphql_gf_gatsby_enabled_actions', array_keys( $enabled_actions ) );
	}

	/**
	 * Checks whether a specific Gravity Forms action is being monitored.
	 *
	 * @param string $action_name the name of the action triggering.
	 */
	public static function is_action_enabled( string $action_name ) : bool {
		$enabled_actions = self::get_enabled_actions();

		return ! empty( $enabled_actions ) && in_array( $action_name, $enabled_actions, true );
	}
}