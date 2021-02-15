<?php
/**
 * GraphQL Object Type - HtmlField
 *
 * @see https://docs.gravityforms.com/gf_field_html/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - HtmlField
 */
class HtmlField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'HtmlField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'html';

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
				'description' => __( 'Gravity Forms HTML field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\InputNameProperty::get(),
					[
						'content' => [
							'type'        => 'String',
							'description' => __( 'Content of an HTML block field to be displayed on the form.', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
