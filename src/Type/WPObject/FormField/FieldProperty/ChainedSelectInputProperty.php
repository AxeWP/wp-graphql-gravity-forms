<?php
/**
 * GraphQL Object Type - ChainedSelectInputProperty
 * An individual property for the 'input' Chained Select field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - ChainedSelectInputProperty
 */
class ChainedSelectInputProperty extends AbstractObject {
	/** Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectInputProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
