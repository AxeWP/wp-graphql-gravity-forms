<?php
/**
 * GraphQL Object Type - ListInputValue
 * Value for a single input within a List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Deprecate `value` in favor of `values`.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - ListInputValue
 */
class ListInputValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListInputValue';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Value for a single input within a list field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'value'  => [
				'type'              => [ 'list_of' => 'String' ],
				'description'       => __( 'Input value', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `values` instead.', 'wp-graphql-gravity-forms' ),
			],
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values', 'wp-graphql-gravity-forms' ),
			],
		];
	}

}
