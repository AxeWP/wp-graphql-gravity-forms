<?php
/**
 * GraphQL Field - ChainedSelectFieldValue
 * Values for an individual ChainedSelect field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;

/**
 * Class - ChainedSelectFieldValue
 */
class ChainedSelectFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'values';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : array {
		return [ 'list_of' => 'String' ];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		return array_map(
			function( $input ) use ( $entry ) {
				return $entry[ $input['id'] ] ?: null;
			},
			$field->inputs
		);
	}
}
