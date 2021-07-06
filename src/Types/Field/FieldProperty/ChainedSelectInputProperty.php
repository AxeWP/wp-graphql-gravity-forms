<?php
/**
 * GraphQL Object Type - ChainedSelectInputProperty
 * An individual property for the 'input' Chained Select field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - ChainedSelectInputProperty
 */
class ChainedSelectInputProperty extends AbstractObject {
	/** Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectInputProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			InputProperty\InputIdProperty::get(),
			LabelProperty::get(),
			InputProperty\InputNameProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.2.0
			 */

			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
