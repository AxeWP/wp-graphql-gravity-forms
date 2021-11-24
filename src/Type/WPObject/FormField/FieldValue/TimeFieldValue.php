<?php
/**
 * GraphQL Field - TimeFieldValue
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty\TimeValueProperty;

/**
 * Class - TimeFieldValue
 */
class TimeFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'TimeField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'timeValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Time field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : string {
		return TimeValueProperty::$type;
	}

	/**
	 * {@inheritDoc}
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
