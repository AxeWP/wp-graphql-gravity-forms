<?php
/**
 * GraphQL Object Type - PostCustomFieldValue
 * Values for an individual Post custom field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.3.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\PostCustomFieldValueProperty;

/**
 * Class - PostCustomFieldValue
 */
class PostCustomFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PostCustomFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Post custom field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'The value.', 'wp-graphql-gravity-forms' ),
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
		return [ 'values' => PostCustomFieldValueProperty::get( $entry, $field ) ];
	}
}
