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

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Enum\ButtonTypeEnum;

/**
 * Class - LastPageButton
 */
class LastPageButton extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'LastPageButton';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms button.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
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
		];
	}
}
