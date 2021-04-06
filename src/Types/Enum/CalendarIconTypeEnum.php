<?php
/**
 * Enum Type - CalendarIconTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - CalendarIconTypeEnum
 */
class CalendarIconTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CalendarIconTypeEnum';

	// Individual elements.
	const CALENDAR = 'calendar';
	const CUSTOM   = 'custom';
	const NONE     = 'none';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'How the date field displays its calendar icon.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'CALENDAR' => [
				'description' => __( 'Default calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CALENDAR,
			],
			'CUSTOM'   => [
				'description' => __( 'Custom calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CUSTOM,
			],
			'NONE'     => [
				'description' => __( 'No calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
		];
	}
}
