<?php
/**
 * GraphQL Object Type - Button
 *
 * @see https://docs.gravityforms.com/button/
 *
 * @package WPGraphQLGravityForms\Types\Button
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Button;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\ConditionalLogic\ConditionalLogic;
use WPGraphQLGravityForms\Types\Enum\ButtonTypeEnum;

/**
 * Class - Button
 */
class Button implements Hookable, Type {
	const TYPE = 'Button';

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
					'type'             => [
						'type'        => ButtonTypeEnum::$type,
						'description' => __( 'Specifies the type of button to be displayed. Defaults to TEXT.', 'wp-graphql-gravity-forms' ),
					],
					'text'             => [
						'type'        => 'String',
						'description' => __( 'Contains the button text. Only applicable when type is set to text.', 'wp-graphql-gravity-forms' ),
					],
					'imageUrl'         => [
						'type'        => 'String',
						'description' => __( 'Contains the URL for the image button. Only applicable when type is set to image.', 'wp-graphql-gravity-forms' ),
					],
					'conditionalLogic' => [
						'type'        => ConditionalLogic::TYPE,
						'description' => __( 'Controls when the form button should be visible based on values selected on the form.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
