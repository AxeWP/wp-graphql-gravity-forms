<?php
/**
 * GraphQL Object Type - FormSubmitButton
 *
 * @see https://docs.gravityforms.com/submit-button
 *
 * @package WPGraphQL\GF\Type\WPObject\Form\FormSubmitButton
 * @since   0.11.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormButtonTypeEnum;
use WPGraphQL\GF\Type\Enum\FormSubmitButtonLocationEnum;
use WPGraphQL\GF\Type\Enum\FormSubmitButtonWidthEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;

/**
 * Class - FormSubmitButton
 */
class FormSubmitButton extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormSubmitButton';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms submit button.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'type'                 => [
				'type'        => FormButtonTypeEnum::$type,
				'description' => __( 'Specifies the type of button to be displayed. Defaults to TEXT.', 'wp-graphql-gravity-forms' ),
			],
			'text'                 => [
				'type'        => 'String',
				'description' => __( 'Contains the button text. Only applicable when type is set to text.', 'wp-graphql-gravity-forms' ),
			],
			'imageUrl'             => [
				'type'        => 'String',
				'description' => __( 'Contains the URL for the image button. Only applicable when type is set to image.', 'wp-graphql-gravity-forms' ),
			],
			'conditionalLogic'     => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls when the form button should be visible based on values selected on the form.', 'wp-graphql-gravity-forms' ),
			],
			'layoutGridColumnSpan' => [
				'type'        => 'Int',
				'description' => __( 'The number of CSS grid columns the field should span.', 'wp-graphql-gravity-forms' ),
			],
			'location'             => [
				'type'        => FormSubmitButtonLocationEnum::$type,
				'description' => __( 'Where the submit button should be located.', 'wp-graphql-gravity-forms' ),
			],
			'width'                => [
				'type'        => FormSubmitButtonWidthEnum::$type,
				'description' => __( 'The width of the submit button element.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
