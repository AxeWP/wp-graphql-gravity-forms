<?php
/**
 * Interface for Gravity Forms entry value property.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.5.0
 */

namespace WPGraphQLGravityForms\Interfaces;

use GF_Field;

/**
 * Interface - ValueProperty
 */
interface ValueProperty {

	/**
	 * Sets the field type description.
	 *
	 * @return string field type description.
	 */
	public function get_type_description() : string;

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string|array
	 */
	public function get_field_type();

	/**
	 * Get the field value.
	 *
	 * @todo stop returning array once fieldValue is removed.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return mixed Entry field value.
	 */
	public static function get( array $entry, GF_Field $field );
}
