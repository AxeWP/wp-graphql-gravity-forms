<?php
/**
 * GraphQL Object Type - Gravity Forms 'Save and Continue' data.
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Button\Button;

/**
 * Class - SaveAndContinue
 */
class SaveAndContinue implements Hookable, Type {
	const TYPE = 'SaveAndContinue';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms form Save and Continue data.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'enabled'    => [
						'type'        => 'Boolean',
						'description' => __( 'Whether the Save And Continue feature is enabled.', 'wp-graphql-gravity-forms' ),
					],
					'buttonText' => [
						'type'        => 'string',
						'description' => __( 'Contains the save button text.', 'wp-graphql-gravity-forms' ),
						'resolve'     => function( $root ) : string {
							return $root['button']['text'];
						},
					],
				],
			]
		);
	}
}
