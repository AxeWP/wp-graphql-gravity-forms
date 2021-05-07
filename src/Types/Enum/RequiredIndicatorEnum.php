<?php
/**
 * Enum Type - RequiredIndicatorEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.6.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - RequiredIndicatorEnum
 */
class RequiredIndicatorEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'RequiredIndicatorEnum';

	// Individual elements.
	const ASTERISK = 'asterisk';
	const CUSTOM   = 'custom';
	const TEXT     = 'text';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of indicator to use when field is required.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'ASTERISK' => [
				'description' => __( 'Asterisk (*) indicator.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ASTERISK,
			],
			'CUSTOM'   => [
				'description' => __( 'Custom indicator.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CUSTOM,
			],
			'TEXT'     => [
				'description' => __( 'Text (Required) indicator (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::TEXT,
			],
		];
	}
}
