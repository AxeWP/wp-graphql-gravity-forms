<?php
/**
 * GraphQL Object Type - TimeField
 *
 * @see https://docs.gravityforms.com/gf_field_time/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
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
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\SizeProperty::get(),
					[
						/**
						 * Possible values: 12, 24
						 */
						'timeFormat' => [
							'type'        => 'String',
							'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
						],
					]
					// @TODO: Add placeholders.
				),
			]
		);
	}
}
