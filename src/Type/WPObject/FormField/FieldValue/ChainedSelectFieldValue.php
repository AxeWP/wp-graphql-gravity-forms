<?php
/**
 * GraphQL Object Type - ChainedSelectFieldValue
 * Values for an individual Chained Select field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\ChainedSelectFieldValueProperty;

/**
 * Class - ChainedSelectFieldValue
 */
class ChainedSelectFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Chained Select field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Field values.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the field values.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field values.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		return [ 'values' => ChainedSelectFieldValueProperty::get( $entry, $field ) ];
	}
}
