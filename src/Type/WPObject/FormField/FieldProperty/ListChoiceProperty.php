<?php
/**
 * GraphQL Object Type - ListChoiceProperty
 * An individual property for the 'choices' field property of the List field.
 *
 * @see https://docs.gravityforms.com/gf_field_list/#highlighter_635805
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - ListChoiceProperty
 */
class ListChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ListChoiceProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'List field column labels.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
