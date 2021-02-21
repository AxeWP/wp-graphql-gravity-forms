<?php
/**
 * GraphQL Object Type - TimeField
 *
 * @see https://docs.gravityforms.com/gf_field_time/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - TimeField
 */
class TimeField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'TimeField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'time';

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
				'description' => __( 'Gravity Forms Time field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\InputsProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\SubLabelPlacementProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'timeFormat' => [
							'type'        => 'String',
							'description' => __( 'Determines how the time is displayed. Possible values: "12", "24"', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
