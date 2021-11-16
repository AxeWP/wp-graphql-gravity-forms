<?php
/**
 * Enum Type - ButtonTypeEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Types\Enum;

/**
 * Class - ButtonTypeEnum
 */
class ButtonTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ButtonTypeEnum';

	// Individual elements.
	const TEXT  = 'text';
	const IMAGE = 'image';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of button to be displayed. Default is TEXT.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'IMAGE' => [
				'description' => __( 'Image button.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IMAGE,
			],
			'TEXT'  => [
				'description' => __( 'Text button (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::TEXT,
			],
		];
	}
}
