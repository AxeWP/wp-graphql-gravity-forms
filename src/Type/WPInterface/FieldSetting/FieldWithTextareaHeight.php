<?php
/**
 * GraphQL Interface for a FormField with the `textarea_height_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.13.5
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\FormFieldSizeEnum;

/**
 * Class - FieldWithTextareaHeight
 */
class FieldWithTextareaHeight extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithTextareaHeightSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'textarea_height_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'textareaHeight' => [
				'type'        => FormFieldSizeEnum::$type,
				'description' => static fn () => __( 'Determines the height of the field when displayed on the page.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => self::resolve_textarea_height( $source ),
			],
			'size'           => [
				'type'              => FormFieldSizeEnum::$type,
				'description'       => static fn () => __( 'Determines the size of the field when displayed on the page.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => static fn () => __( 'Gravity Forms 3.0 replaced the Field Size setting with Field Height. Use `textareaHeight` instead.', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => self::resolve_textarea_height( $source ),
			],
		];
	}

	/**
	 * Resolves the field height the way Gravity Forms renders it.
	 *
	 * Fields saved before Gravity Forms 3.0 still carry the legacy `size` property until their height is changed in the form editor.
	 *
	 * @param \GF_Field $source The Gravity Forms field.
	 */
	private static function resolve_textarea_height( $source ): ?string {
		if ( ! empty( $source->textareaHeight ) ) {
			return $source->textareaHeight;
		}

		return ! empty( $source->size ) ? $source->size : null;
	}
}
