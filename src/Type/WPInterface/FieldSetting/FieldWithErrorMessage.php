<?php
/**
 * GraphQL Interface for a FormField with the `error_message_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithErrorMessage
 */
class FieldWithErrorMessage extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithErrorMessageSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'error_message_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'errorMessage' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Contains the message that is displayed for fields that fail validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
