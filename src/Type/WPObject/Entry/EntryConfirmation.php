<?php
/**
 * GraphQL Object Type - Gravity Forms Form Entry
 *
 * @see https://docs.gravityforms.com/entry-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Entry
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\Enum\FormConfirmationTypeEnum;

/**
 * Class - FormConfirmation
 */
class EntryConfirmation extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryConfirmation';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Confirmation on form submission.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'message'          => [
				'type'        => 'String',
				'description' => __( 'Contains the confirmation message that will be displayed. Only applicable when type is set to "MESSAGE".', 'wp-graphql-gravity-forms' ),
			],
			'type'             => [
				'type'        => FormConfirmationTypeEnum::$type,
				'description' => __( 'Determines the type of confirmation to be used.', 'wp-graphql-gravity-forms' ),
			],
			'url'              => [
				'type'        => 'String',
				'description' => __( 'Contains the URL that the browser will be redirected to. Only applicable when type is set to `REDIRECT`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
