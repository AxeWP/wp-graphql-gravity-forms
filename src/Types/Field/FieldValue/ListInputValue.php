<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Value for a single input within a List field.
 */
class ListInputValue implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ListInputValue';

	public function register_hooks() {
			add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Value for a single input within a list field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'value' => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
