<?php
/**
 * GraphQL Object Type - FormLastPageButton
 *
 * @see https://docs.gravityforms.com/button/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormLastPageButton
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\WPObject\Button;

use WPGraphQL\GF\Type\Enum\FormButtonTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormLastPageButton
 */
class FormLastPageButton extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormLastPageButton';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms button.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'type'     => [
				'type'        => FormButtonTypeEnum::$type,
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
		];
	}
}
