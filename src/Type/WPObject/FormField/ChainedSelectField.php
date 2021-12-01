<?php
/**
 * GraphQL Object Type - ChainedSelectField
 *
 * @see https://www.gravityforms.com/add-ons/chained-selects/
 * @see https://docs.gravityforms.com/category/add-ons-gravity-forms/chained-selects/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Type\Enum\ChainedSelectsAlignmentEnum;

/**
 * Class - ChainedSelectField
 */
class ChainedSelectField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'chainedselect';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Chained Select field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'choices'                    => [
					'type'        => [ 'list_of' => FieldProperty\ChainedSelectChoiceProperty::$type ],
					'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
				],
				'chainedSelectsAlignment'    => [
					'type'        => ChainedSelectsAlignmentEnum::$type,
					'description' => __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' ),
				],
				'chainedSelectsHideInactive' => [
					'type'        => 'Boolean',
					'description' => __( 'Whether inactive dropdowns should be hidden.', 'wp-graphql-gravity-forms' ),
				],
				'inputs'                     => [
					'type'        => [ 'list_of' => FieldProperty\ChainedSelectInputProperty::$type ],
					'description' => __( 'An array containing the the individual properties for each element of the Chained Select field.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}
}
