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
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty\TimeFieldValueProperty;

/**
 * Class - TimeFieldValue
 */
class TimeFieldValue extends AbstractObject implements FieldValue {
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
	public function get_type_fields() : array {
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
		return TimeFieldValueProperty::get( $entry, $field );
	}
}
