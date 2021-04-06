<?php
/**
 * Enum Type - NotificationToTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - NotificationToTypeEnum
 */
class NotificationToTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NotificationToTypeEnum';

	// Individual elements.
	const EMAIL   = 'email';
	const FIELD   = 'field';
	const ROUTING = 'routing';
	const HIDDEN  = 'hidden';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'What to use for the notification "to".', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'EMAIL'   => [
				'description' => __( 'Email address.', 'wp-graphql-gravity-forms' ),
				'value'       => self::EMAIL,
			],
			'FIELD'   => [
				'description' => __( 'Form field.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FIELD,
			],
			'ROUTING' => [
				'description' => __( 'Routing using conditional rules.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ROUTING,
			],
			'HIDDEN'  => [
				'description' => __( 'Hidden.', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
		];
	}
}
