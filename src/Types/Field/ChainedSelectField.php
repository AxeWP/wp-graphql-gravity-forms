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

/**
 * Class - ChainedSelectField
 */
class ChainedSelectField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ChainedSelectField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'chainedselect';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms Chained Select field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
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
						// @TODO: Convert to an enum.
						'chainedSelectsAlignment'    => [
							'type'        => 'String',
							'description' => __( 'Alignment of the dropdown fields. Possible values: "horizontal" (in a row) or "vertical" (in a column).', 'wp-graphql-gravity-forms' ),
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
				),
			]
		);
	}
}
