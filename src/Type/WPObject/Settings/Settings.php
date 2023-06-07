<?php
/**
 * GraphQL Object Type - Gravity Forms Settings.
 *
 * @package WPGraphQL\GF\Type\WPObject
 * @since   0.10.2
 */

namespace WPGraphQL\GF\Type\WPObject\Settings;

use GFCommon;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\Enum\CurrencyEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - Settings
 */
class Settings extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfSettings';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'gfSettings';

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		parent::register();

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms Settings.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'currency'                => [
				'type'        => CurrencyEnum::$type,
				'description' => __( 'The default currency for your forms. Used for product, credit card, and other fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): string => GFCommon::get_currency(),
			],
			'hasDefaultCss'           => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to output Gravity Forms\' default CSS.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => ! (bool) get_option( 'rg_gforms_disable_css' ),
			],
			'hasBackgroundUpdates'    => [
				'type'        => 'Boolean',
				'description' => __( 'Whether Gravity Forms to download and install bug fixes and security updates automatically in the background. Requires a valid license key.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => (bool) get_option( 'gform_enable_background_updates' ),
			],
			'hasToolbar'              => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to display the forms menu in the WordPress top toolbar. The forms menu will display the ten forms recently opened in the form editor.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => (bool) get_option( 'gform_enable_toolbar_menu' ),
			],
			'isHtml5Enabled'          => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the server-generated form markup uses HTML5.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => (bool) get_option( 'rg_gforms_enable_html5', false ),
			],
			'isNoConflictModeEnabled' => [
				'type'        => 'Boolean',
				'description' => __( 'Enable to prevent extraneous scripts and styles from being printed on a Gravity Forms admin pages, reducing conflicts with other plugins and themes.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): bool => (bool) get_option( 'gform_enable_noconflict' ),
			],
			'logging'                 => [
				'type'        => SettingsLogging::$type,
				'description' => __( 'Logging settings.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn () => [],
			],
			'recaptcha'               => [
				'type'        => SettingsRecaptcha::$type,
				'description' => __( 'Recaptcha settings.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn () => [],
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function register_field(): void {
		register_graphql_field(
			'RootQuery',
			self::$field_name,
			[
				'description' => __( 'Gravity Forms settings.', 'wp-graphql-gravity-forms' ),
				'type'        => self::$type,
				'resolve'     => static fn () => [],
			]
		);
	}
}
