<?php
/**
 * Interface for Gravity Forms field vlues.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Interfaces;

use GF_Field;
/**
 * Interface - FieldValue
 */
interface FieldValue {
	/**
	 * Get the field value.
	 *
	 * @todo stop returning array once fieldValue is removed.
	 *
	 * @param array    $entry_values the submission values from the GF entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return mixed Entry field value.
	 */
	public static function get( array $entry_values, GF_Field $field );

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string|array
	 */
	public static function get_field_type();
}
