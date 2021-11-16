<?php
/**
 * GraphQL Field - TimeFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - TimeFieldValueProperty
 */
class TimeFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'TimeField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'timeValues';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Time field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string
	 */
	public function get_field_type() : string {
		return TimeValueProperty::$type;
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
		if ( ! isset( $entry [ $field->id ] ) ) {
			return [
				'displayValue' => null,
				'hours'        => null,
				'minutes'      => null,
				'amPm'         => null,
			];
		}

		$display_value  = $entry[ $field->id ];
		$parts_by_colon = explode( ':', $display_value );
		$hours          = $parts_by_colon[0] ?? '';
		$parts_by_space = explode( ' ', $display_value );
		$am_pm          = $parts_by_space[1] ?? '';
		$minutes        = rtrim( ltrim( $display_value, "{$hours}:" ), " {$am_pm}" );

		return [
			'displayValue' => $display_value ?: null,
			'hours'        => $hours ?: null,
			'minutes'      => $minutes ?: null,
			'amPm'         => $am_pm ?: null,
		];
	}
}
