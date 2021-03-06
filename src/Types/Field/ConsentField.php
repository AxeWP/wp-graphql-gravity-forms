<?php
/**
 * GraphQL Object Type - ConsentField
 *
 * @see https://docs.gravityforms.com/gf_field_consent/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.3.0
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - ConsentField
 */
class ConsentField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ConsentField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'consent';

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
				'description' => __( 'Gravity Forms Consent field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'checkboxLabel' => [
							'type'        => 'String',
							'description' => __( 'Text of the consent checkbox', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			]
		);
	}
}
