<?php
/**
 * Object Type - PhoneFormat
 *
 * @package WPGraphQL\GF\Type\WPObject
 * @since   0.13.4
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Type\Enum\PhoneFieldFormatEnum;

/**
 * Class - PhoneFormat
 */
class PhoneFormat extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfPhoneFormat';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Properties of a phone number format, including label, mask, regex, instruction and type.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'label'       => [
				'type'        => 'String',
				'description' => static fn () => __( 'The display label for the phone format.', 'wp-graphql-gravity-forms' ),
			],
			'mask'        => [
				'type'        => 'String',
				'description' => static fn () => __( 'The input mask for the phone format (e.g., "(999) 999-9999").', 'wp-graphql-gravity-forms' ),
			],
			'regex'       => [
				'type'        => 'String',
				'description' => static fn () => __( 'The regex pattern for validating the phone format.', 'wp-graphql-gravity-forms' ),
			],
			'instruction' => [
				'type'        => 'String',
				'description' => static fn () => __( 'The instruction text displayed to users for this phone format.', 'wp-graphql-gravity-forms' ),
			],
			'type'        => [
				'type'        => PhoneFieldFormatEnum::$type,
				'description' => static fn () => __( 'The internal type identifier for the phone format.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
