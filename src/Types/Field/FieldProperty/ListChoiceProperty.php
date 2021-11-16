<?php
/**
 * GraphQL Object Type - ListChoiceProperty
 * An individual property for the 'choices' field property of the List field.
 *
 * @see https://docs.gravityforms.com/gf_field_list/#highlighter_635805
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - ListChoiceProperty
 */
class ListChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListChoiceProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'List field column labels.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
			'text'  => [
				'type'        => 'String',
				'description' => __( 'The text to be displayed in the column header. Required.', 'wp-graphql-gravity-forms' ),
			],
			'value' => [
				'type'        => 'String',
				'description' => __( 'The text to be displayed in the column header.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
