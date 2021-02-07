<?php
/**
 * GraphQL Object Type - SectionField
 *
 * @see https://docs.gravityforms.com/gf_field_section/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

/**
 * Class - SectionField
 */
class SectionField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'SectionField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'section';

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
				'description' => __( 'Gravity Forms Section field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\DescriptionProperty::get()
				),
			]
		);
	}
}
