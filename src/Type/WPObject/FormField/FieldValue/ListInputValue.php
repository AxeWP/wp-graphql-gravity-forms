<?php
/**
 * GraphQL Object Type - ListInputValue
 * Value for a single input within a List field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Deprecate `value` in favor of `values`.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - ListInputValue
 */
class ListInputValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ListInputValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Value for a single input within a list field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [

			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values', 'wp-graphql-gravity-forms' ),
			],
		];
	}

}
