<?php
/**
 * Adds support for GFSignature.
 *
 * @package WPGraphQL\GF\Extensions\GFSignature,
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature;

use GF_Field;
use WPGraphQL\GF\Extensions\GFSignature\Data\FieldValueInput\SignatureValuesInput;
use WPGraphQL\GF\Extensions\GFSignature\Type\Enum;
use WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface;
use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - GFSignature
 */
class GFSignature implements Hookable {
	/**
	 * Hook extension into plugin.
	 */
	public static function register_hooks(): void {
		if ( ! self::is_gf_signature_enabled() ) {
			return;
		}

		// Register Enums.
		add_filter( 'graphql_gf_registered_enum_classes', [ self::class, 'enums' ] );
		// Register Form Field Settings interfaces.
		add_filter( 'graphql_gf_registered_form_field_setting_classes', [ self::class, 'form_field_settings' ] );

		// Register FieldValueInput.
		add_filter( 'graphql_gf_field_value_input_class', [ self::class, 'field_value_input' ], 10, 3 );
	}

	/**
	 * Returns whether Gravity Forms Signature is enabled.
	 */
	public static function is_gf_signature_enabled(): bool {
		return class_exists( 'GFSignature' );
	}

	/**
	 * Register enum classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function enums( array $registered_classes ): array {
		$registered_classes[] = Enum\SignatureFieldBorderStyleEnum::class;
		$registered_classes[] = Enum\SignatureFieldBorderWidthEnum::class;
		return $registered_classes;
	}

	/**
	 * Registers the mapped list of GF form field settings to their interface classes.
	 *
	 * @param array $classes .
	 */
	public static function form_field_settings( array $classes ): array {
		$classes['background_color_setting'] = WPInterface\FieldSetting\FieldWithBackgroundColor::class;
		$classes['border_color_setting']     = WPInterface\FieldSetting\FieldWithBorderColor::class;
		$classes['border_style_setting']     = WPInterface\FieldSetting\FieldWithBorderStyle::class;
		$classes['border_width_setting']     = WPInterface\FieldSetting\FieldWithBorderWidth::class;
		$classes['box_width_setting']        = WPInterface\FieldSetting\FieldWithBoxWidth::class;
		$classes['pen_color_setting']        = WPInterface\FieldSetting\FieldWithPenColor::class;
		$classes['pen_size_setting']         = WPInterface\FieldSetting\FieldWithPenSize::class;

		return $classes;
	}

	/**
	 * Registers the SignatureValuesInput class.
	 *
	 * @param string    $input_class .
	 * @param array     $args .
	 * @param \GF_Field $field .
	 */
	public static function field_value_input( string $input_class, array $args, GF_Field $field ): string {
		if ( 'signature' === $field->get_input_type() ) {
			$input_class = SignatureValuesInput::class;
		}

		return $input_class;
	}
}
