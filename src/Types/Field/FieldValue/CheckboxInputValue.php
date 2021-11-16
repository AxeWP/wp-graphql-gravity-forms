<?php
/**
 * GraphQL Object Type - CheckboxInputValue
 * Value for a single input within a checkbox field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldValue;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - CheckboxInputValue
 */
class CheckboxInputValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxInputValue';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Value for a single input within a checkbox field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
