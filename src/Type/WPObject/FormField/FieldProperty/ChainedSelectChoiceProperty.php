<?php
/**
 * GraphQL Object Type - ChainedSelectChoiceProperty
 * An individual property for the 'choices' Chained Select field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use refactored ChoiceProperty fields.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ChoiceProperty;

/**
 * Class - ChainedSelectChoiceProperty
 */
class ChainedSelectChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectChoiceProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Chained Select field choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			ChoiceProperty\ChoiceIsSelectedProperty::get(),
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
			[
				'choices' => [
					'type'        => [ 'list_of' => self::$type ],
					'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}