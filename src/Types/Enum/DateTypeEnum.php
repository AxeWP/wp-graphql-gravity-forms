<?php
/**
 * Enum Type - DateTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - DateTypeEnum
 */
class DateTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'DateTypeEnum';

	// Individual elements.
	const FIELD    = 'datefield';
	const DROPDOWN = 'datedropdown';
	const PICKER   = 'datepicker';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of date field to display.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'FIELD'    => [
				'description' => __( 'A simple date field.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FIELD,
			],
			'DROPDOWN' => [
				'description' => __( 'A date dropdown.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DROPDOWN,
			],
			'PICKER'   => [
				'description' => __( 'A datepicker.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PICKER,
			],
		];
	}
}
