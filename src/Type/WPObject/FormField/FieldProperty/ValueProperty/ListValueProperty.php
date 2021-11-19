<?php
/**
 * GraphQL Object Type - ListValuePropery
 * An individual property for the 'value' List field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - ListValueProperty
 */
class ListValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ListValueProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The individual properties for each element of the List value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
