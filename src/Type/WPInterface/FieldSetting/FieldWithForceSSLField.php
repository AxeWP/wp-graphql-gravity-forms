<?php
/**
 * GraphQL Interface for a FormField with the `force_ssl_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithForceSSLField
 */
class FieldWithForceSSLField extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithForceSSLFieldSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'force_ssl_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isSSLForced' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field requires the user to enter a value. Fields marked as required will prevent the form from being submitted if the user has not entered a value in it.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->forceSSL ),
			],
		];
	}
}
