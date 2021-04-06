<?php
/**
 * GraphQL Object Type - LastPageButton
 *
 * @see https://docs.gravityforms.com/button/
 *
 * @package WPGraphQLGravityForms\Types\LastPageButton
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Button;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Enum\ButtonTypeEnum;

/**
 * Class - LastPageButton
 */
class LastPageButton implements Hookable, Type {
	const TYPE = 'LastPageButton';

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
				'description' => __( 'Gravity Forms button.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'type'     => [
						'type'        => ButtonTypeEnum::$type,
						'description' => __( 'Specifies the type of button to be displayed. Defaults to TEXT.', 'wp-graphql-gravity-forms' ),
					],
					'text'     => [
						'type'        => 'String',
						'description' => __( 'Contains the button text. Only applicable when type is set to text.', 'wp-graphql-gravity-forms' ),
					],
					'imageUrl' => [
						'type'        => 'String',
						'description' => __( 'Contains the URL for the image button. Only applicable when type is set to image.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
