<?php
/**
 * GraphQL Object Type - CheckboxInputValue
 * Value for a single input within a checkbox field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - CheckboxInputValue
 */
class CheckboxInputValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxInputValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Value for a single input within a checkbox field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
