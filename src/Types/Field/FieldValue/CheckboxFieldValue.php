<?php
/**
 * GraphQL Object Type - CheckboxFieldValue
 * Value for a checkbox field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty\CheckboxFieldValueProperty;
use WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty\CheckboxValueProperty;

/**
 * Value for a checkbox field.
 */
class CheckboxFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Checkbox field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'checkboxValues' => [
				'type'        => [ 'list_of' => CheckboxValueProperty::$type ],
				'description' => __( 'Values.', 'wp-graphql-gravity-forms' ),
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
		return [ 'checkboxValues' => CheckboxFieldValueProperty::get( $entry, $field ) ];
	}
}
