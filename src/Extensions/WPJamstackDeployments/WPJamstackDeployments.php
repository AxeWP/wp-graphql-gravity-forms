<?php
/**
 * Enables and initializes the Gravity Forms Action Monitor
 *
 * @package WPGraphQL\GF\Extensions\WPJamstackDeployments
 * @since 0.10.3
 */

namespace WPGraphQL\GF\Extensions\WPJamstackDeployments;

use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - WPJamstackDeployments
 */
class WPJamstackDeployments implements Hookable {
	/**
	 * The option named used in the settings API.
	 *
	 * @var string
	 */
	public static string $option_name = 'webhook_gf';

	/**
	 * The options array.
	 *
	 * @var array
	 */
	public static array $options;

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		if ( ! self::is_wp_jamstack_deployments_enabled() ) {
			return;
		}

		// Register settings.
		add_action( 'admin_init', [ self::class, 'register_settings' ], 11 );
		// Filters sanitization callback.
		add_filter( 'sanitize_option_' . self::get_options_key(), [ self::class, 'sanitize' ], 10 );

		// Trigger deployments.
		self::trigger_deployments();
	}

	/**
	 * Returns whether WPJamstackDeployments is enabled.
	 */
	public static function is_wp_jamstack_deployments_enabled(): bool {
		return class_exists( 'Crgeary\JAMstackDeployments\App' );
	}

	/**
	 * Returns the Options Key used by WPJamstackDeployments.
	 */
	public static function get_options_key(): string {
		return defined( 'CRGEARY_JAMSTACK_DEPLOYMENTS_OPTIONS_KEY' ) ? CRGEARY_JAMSTACK_DEPLOYMENTS_OPTIONS_KEY : 'wp_jamstack_deployments';
	}

	/**
	 * Returns the array of options.
	 */
	public static function get_options(): array {
		if ( empty( self::$options ) ) {
			self::$options = \jamstack_deployments_get_options();
		}

		return self::$options;
	}

	/**
	 * Registers settings to enable/disable deployments.
	 */
	public static function register_settings(): void {
		$key = self::get_options_key();

		$option = self::get_options();

		$option_name = self::$option_name;

		add_settings_field(
			$option_name,
			__( 'Gravity Forms', 'wp-graphql-gravity-forms' ),
			[ 'Crgeary\JAMstackDeployments\Field', 'checkboxes' ],
			$key,
			'general',
			[
				'name'        => "{$key}[{$option_name}]",
				'value'       => isset( $option[ $option_name ] ) ? $option[ $option_name ] : [],
				'choices'     => [
					'create_form'        => __( 'Form Creation', 'wp-graphql-gravity-forms' ),
					'update_form'        => __( 'Form Updates', 'wp-graphql-gravity-forms' ),
					'delete_form'        => __( 'Form Deletions', 'wp-graphql-gravity-forms' ),
					'create_entry'       => __( 'Entry Submission', 'wp-graphql-gravity-forms' ),
					'update_entry'       => __( 'Entry Updates', 'wp-graphql-gravity-forms' ),
					'create_draft_entry' => __( 'Draft Entry Creation', 'wp-graphql-gravity-forms' ),
				],
				'description' => __( 'Only selected Gravity Forms actions will trigger a deployment.', 'wp-graphql-gravity-forms' ),
				'legend'      => __( 'Gravity Forms', 'wp-graphql-gravity-forms' ),
			]
		);
	}

	/**
	 * Sanitize user input.
	 *
	 * @param array $input .
	 */
	public static function sanitize( array $input ): array {
		if ( ! isset( $input[ self::$option_name ] ) || ! is_array( $input[ self::$option_name ] ) ) {
			$input[ self::$option_name ] = [];
		}

		return $input;
	}

	/**
	 * Adds actions to trigger deployments based on the settings.
	 */
	public static function trigger_deployments(): void {
		$options = self::get_options();
		if ( empty( $options[ self::$option_name ] ) ) {
			return;
		}

		foreach ( $options[ self::$option_name ] as $gf_hook ) {
			switch ( $gf_hook ) {
				case 'create_form':
					add_action( 'gform_post_form_duplicated', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_after_save_form', [ self::class, 'after_save_form' ], 10, 2 );
					break;
				case 'update_form':
					// Only add action if it doenst already exist.
					if ( ! has_action( 'gform_after_save_form', [ self::class, 'after_save_form' ] ) ) {
						add_action( 'gform_after_save_form', [ self::class, 'after_save_form' ], 10, 2 );
					}
					add_action( 'gform_post_update_form_meta', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_post_form_activated', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_post_form_deactivated', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_post_form_restored', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_post_form_trashed', 'jamstack_deployments_fire_webhook' );
					break;
				case 'delete_form':
					add_action( 'gform_after_delete_form', 'jamstack_deployments_fire_webhook' );
					break;
				case 'create_entry':
					add_action( 'gform_after_submission', 'jamstack_deployments_fire_webhook' );
					break;
				case 'update_entry':
					add_action( 'gform_after_update_entry', 'jamstack_deployments_fire_webhook' );
					add_action( 'gform_post_update_entry', 'jamstack_deployments_fire_webhook' );
					break;
				case 'create_draft_entry':
					add_action( 'gform_incomplete_submission_post_save', 'jamstack_deployments_fire_webhook' );
					break;
			}
		}
	}

	/**
	 * Triggers the correct deployment when a form is saved.
	 *
	 * @param array   $form .
	 * @param boolean $is_new .
	 */
	public static function after_save_form( array $form, bool $is_new ): void {
		$options = self::get_options();

		if ( in_array( 'create_form', $options[ self::$option_name ], true ) && $is_new ) {
			\jamstack_deployments_fire_webhook();
		} elseif ( in_array( 'update_form', $options[ self::$option_name ], true ) && ! $is_new ) {
			\jamstack_deployments_fire_webhook();
		}
	}
}
