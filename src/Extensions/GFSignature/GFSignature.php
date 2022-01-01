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
use WPGraphQL\GF\Extensions\GFSignature\Type\WPObject\FormField\FieldProperty\PropertyMapper;

/**
 * Class - GFSignature
 */
class GFSignature {
	/**
	 * Hook extension into plugin.
	 */
	public static function register_hooks() : void {
		if ( ! self::is_gf_signature_enabled() ) {
			return;
		}

		// Register Enums.
		add_filter( 'graphql_gf_registered_enum_classes', [ __CLASS__, 'enums' ] );

		// Register SignatureFieldValueInput.
		add_filter( 'graphql_gf_field_value_input_class', [ __CLASS__, 'field_value_input' ], 10, 3 );

		// Register field_settings.
		add_filter( 'graphql_gf_form_field_setting_properties', [ __CLASS__, 'field_setting_properties' ], 10, 3 );
	}

	/**
	 * Returns whether Gravity Forms Signature is enabled.
	 *
	 * @return boolean
	 */
	public static function is_gf_signature_enabled() : bool {
		return class_exists( 'GFSignature' );
	}

	/**
	 * Register enum classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function enums( array $registered_classes ) : array {
		$registered_classes[] = Enum\SignatureFieldBorderStyleEnum::class;
		$registered_classes[] = Enum\SignatureFieldBorderWidthEnum::class;
		return $registered_classes;
	}

	/**
	 * Registers the SignatureValuesInput class.
	 *
	 * @param string   $input_class .
	 * @param array    $args .
	 * @param GF_Field $field .
	 */
	public static function field_value_input( string $input_class, array $args, GF_Field $field ) : string {
		if ( 'signature' === $field->get_input_type() ) {
			$input_class = SignatureValuesInput::class;
		}

		return $input_class;
	}

	/**
	 * Registers Signature field settings mapper.
	 *
	 * @param array    $properties .
	 * @param string   $setting .
	 * @param GF_Field $field .
	 */
	public static function field_setting_properties( array $properties, string $setting, GF_Field $field ) : array {
		if ( method_exists( PropertyMapper::class, $setting ) ) {
			PropertyMapper::$setting( $field, $properties );
		}

		return $properties;
	}

}
