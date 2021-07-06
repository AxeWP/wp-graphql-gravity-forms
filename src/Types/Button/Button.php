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

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\ConditionalLogic\ConditionalLogic;
use WPGraphQLGravityForms\Types\Enum\ButtonTypeEnum;

/**
 * Class - Button
 */
class Button extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'Button';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms button.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
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
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls when the form button should be visible based on values selected on the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
