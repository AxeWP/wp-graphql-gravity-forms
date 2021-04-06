<?php
/**
 * Interface for Gravity Forms field values.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

use GF_Field;

/**
 * Interface - FieldValue
 */
interface FieldValue {

	/**
	 * Sets the Enum type description.
	 *
	 * @since 0.4.0
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string;

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_properties() : array;

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) : array;
}
