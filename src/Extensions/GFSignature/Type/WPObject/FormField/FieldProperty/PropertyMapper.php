<?php
/**
 * Maps the Gravity Forms Field setting to the appropriate field settings.
 *
 * @package WPGraphQL\GF\Extensions\GFSignature\Type\WPObject\FormField\FieldProperty
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\GF\Extensions\GFSignature\Type\Enum;
/**
 * Class - PropertyMapper
 */
class PropertyMapper {
	/**
	 * Maps the `background_color_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function background_color_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'backgroundColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the background of the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `border_color_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function border_color_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'borderColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `border_style_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function border_style_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'borderStyle' => [
				'type'        => Enum\SignatureFieldBorderStyleEnum::$type,
				'description' => __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `border_width_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function border_width_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'borderWidth' => [
				'type'        => Enum\SignatureFieldBorderWidthEnum::$type,
				'description' => __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `box_width_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function box_width_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'boxWidth' => [
				'type'        => 'Int',
				'description' => __( 'Width of the signature field in pixels.', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Maps the `pen_color_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function pen_color_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'penColor' => [
				'type'        => 'String',
				'description' => __( 'Color of the pen to be used for the signature. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Maps the `pen_size_setting` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function pen_size_setting( GF_Field $field, array &$properties ) : void {
		$properties += [
			'penSize' => [
				'type'        => 'Int',
				'description' => __( 'Size of the pen cursor.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
