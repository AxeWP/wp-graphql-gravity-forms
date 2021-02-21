<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Remove adminLabel, adminOnly, allowsPrepopulate, label, and visibility from global properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\ConditionalLogic\ConditionalLogic;

/**
 * Class - Field
 */
abstract class Field implements Hookable, Type {
	/**
	 * Get the global properties that apply to all GF field types.
	 *
	 * @return array
	 */
	protected function get_global_properties() : array {
		return [
			'conditionalLogic' => [
				'type'        => ConditionalLogic::TYPE,
				'description' => __( 'Controls the visibility of the field based on values selected by the user.', 'wp-graphql-gravity-forms' ),
			],
			'cssClass'         => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
			],
			'cssClassList'     => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Array of the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
			],
			'formId'           => [
				'type'        => 'Integer',
				'description' => __( 'The ID of the form this field belongs to.', 'wp-graphql-gravity-forms' ),
			],
			'id'               => [
				'type'        => 'Integer',
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
			],
			'type'             => [
				'type'        => 'String',
				'description' => __( 'The type of field to be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the custom properties.
	 *
	 * @return array
	 */
	protected function get_custom_properties() : array {
		/**
		 * Add GraphQL fields for custom field properties.
		 *
		 * @param array Additional GraphQL field definitions.
		 * @param array The type of Gravity Forms field.
		 */
		return apply_filters( 'wp_graphql_gf_custom_properties', [], static::GF_TYPE );
	}
}
