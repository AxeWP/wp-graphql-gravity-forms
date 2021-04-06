<?php
/**
 * GraphQL Object Type - ChainedSelectField
 *
 * @see https://www.gravityforms.com/add-ons/chained-selects/
 * @see https://docs.gravityforms.com/category/add-ons-gravity-forms/chained-selects/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Types\Enum\ChainedSelectsAlignmentEnum;

/**
 * Class - ChainedSelectField
 */
class ChainedSelectField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'chainedselect';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Chained Select field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'choices'                    => [
					'type'        => [ 'list_of' => FieldProperty\ChainedSelectChoiceProperty::TYPE ],
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
					'type'        => [ 'list_of' => FieldProperty\ChainedSelectInputProperty::TYPE ],
					'description' => __( 'An array containing the the individual properties for each element of the Chained Select field.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
