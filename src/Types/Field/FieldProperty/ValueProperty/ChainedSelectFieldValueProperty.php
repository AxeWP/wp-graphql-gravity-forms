<?php
/**
 * GraphQL Field - ChainedSelectFieldValueProperty
 * Values for an individual ChainedSelect field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - ChainedSelectFieldValueProperty
 */
class ChainedSelectFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'values';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return array
	 */
	public function get_field_type() : array {
		return [ 'list_of' => 'String' ];
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
		return array_map(
			function( $input ) use ( $entry ) {
				return $entry[ $input['id'] ] ?: null;
			},
			$field->inputs
		);
	}
}
