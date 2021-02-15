<?php
/**
 * GraphQL Object Type - InputProperty
 * An individual input for the 'inputs' field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - InputProperty
 */
class InputProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'InputProperty';

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
				'description' => __( 'Gravity Forms input property.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'id'       => [
						'type'        => 'Float',
						'description' => __( 'The input ID. Input IDs follow the following naming convention: FIELDID.INPUTID (i.e. 5.1), where FIELDID is the id of the containing field and INPUTID specifies the input field.', 'wp-graphql-gravity-forms' ),
					],
					'label'    => [
						'type'        => 'String',
						'description' => __( 'Input label.', 'wp-graphql-gravity-forms' ),
					],
					'name'     => [
						'type'        => 'String',
						'description' => __( 'When the field is configured with allowsPrepopulate set to 1, this property contains the parameter name to be used to populate this field (equivalent to the inputName property of single-input fields).', 'wp-graphql-gravity-forms' ),
					],
					'key'      => [
						'type'        => 'String',
						'description' => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
					],
					'isHidden' => [
						'type'        => 'Boolean',
						'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
