<?php
/**
 * GraphQL Object Type - ConsentFieldValue
 * Values for an individual Consent field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.3.0
 * @since   0.3.1 `value` changed to type `String`.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\ConsentFieldValueProperty;

/**
 * Class - ConsentFieldValue
 */
class ConsentFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConsentFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Consent field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value. Returns the consent message on `true`, `null` on false.', 'wp-graphql-gravity-forms' ),
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
		return [ 'value' => ConsentFieldValueProperty::get( $entry, $field ) ];
	}
}
