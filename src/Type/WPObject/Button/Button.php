<?php
/**
 * GraphQL Object Type - Button
 *
 * @see https://docs.gravityforms.com/button/
 *
 * @package WPGraphQL\GF\Type\WPObject\Button
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Button;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;
use WPGraphQL\GF\Type\Enum\ButtonTypeEnum;

/**
 * Class - Button
 */
class Button extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'Button';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms button.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
