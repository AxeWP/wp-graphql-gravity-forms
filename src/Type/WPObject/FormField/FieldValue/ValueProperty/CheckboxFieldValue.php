<?php
/**
 * GraphQL Object Type - CheckboxValuePropery
 * An individual property for the 'value' Checkbox field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - CheckboxValueProperty
 */
class CheckboxFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The individual properties for each element of the Checkbox value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value.', 'wp-graphql-gravity-forms' ),
			],
			'text'    => [
				'type'        => 'String',
				'description' => __( 'Input text.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
