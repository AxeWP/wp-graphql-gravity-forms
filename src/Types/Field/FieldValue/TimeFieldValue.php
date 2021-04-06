<?php
/**
 * GraphQL Object Type - TimeFieldValue
 * Values for an individual Time field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Class - TimeFieldValue
 */
class TimeFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'TimeFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Time field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'displayValue' => [
				'type'        => 'String',
				'description' => __( 'The full display value. Example: "08:25 am".', 'wp-graphql-gravity-forms' ),
			],
			'hours'        => [
				'type'        => 'String',
				'description' => __( 'The hours, in this format: hh.', 'wp-graphql-gravity-forms' ),
			],
			'minutes'      => [
				'type'        => 'String',
				'description' => __( 'The minutes, in this format: mm.', 'wp-graphql-gravity-forms' ),
			],
			'amPm'         => [
				'type'        => 'String',
				'description' => __( 'AM or PM.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		if ( ! isset( $entry [ $field['id'] ] ) ) {
			return [
				'displayValue' => null,
				'hours'        => null,
				'minutes'      => null,
				'amPm'         => null,
			];
		}

			$display_value  = $entry[ $field['id'] ];
			$parts_by_colon = explode( ':', $display_value );
			$hours          = $parts_by_colon[0] ?? '';
			$parts_by_space = explode( ' ', $display_value );
			$am_pm          = $parts_by_space[1] ?? '';
			$minutes        = rtrim( ltrim( $display_value, "{$hours}:" ), " {$am_pm}" );

			return [
				'displayValue' => $display_value,
				'hours'        => $hours,
				'minutes'      => $minutes,
				'amPm'         => $am_pm,
			];
	}
}
