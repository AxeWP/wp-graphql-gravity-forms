<?php
/**
 * GraphQL Object Type - ListFieldValue
 * Values for an individual List field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Return early if value is null or empty.
 * @since   0.3.0 Fix array structure and deprecate `value` in favor of `values`.
 */

namespace WPGraphQL\GF\Types\Field\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty\ListFieldValueProperty;

/**
 * Class - ListFieldValue
 */
class ListFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'List field values.', 'wp-graphql-gravity-forms' );
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
			'listValues' => [
				'type'        => [ 'list_of' => ListInputValue::$type ],
				'description' => __( 'Field values.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the field values.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field values.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		return [ 'listValues' => ListFieldValueProperty::get( $entry, $field ) ];
	}
}
