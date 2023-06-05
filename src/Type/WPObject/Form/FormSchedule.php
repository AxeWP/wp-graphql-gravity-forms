<?php
/**
 * GraphQL Object Type - Gravity Forms Login data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormSchedule
 */
class FormSchedule extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormSchedule';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form scheduling data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'closedMessage'  => [
				'type'        => 'String',
				'description' => __( 'Message to be displayed when form is no longer available.', 'wp-graphql-gravity-forms' ),
			],
			'endDetails'     => [
				'type'        => FormScheduleDetails::$type,
				'description' => __( 'The Date/time details when the form will become inactive.', 'wp-graphql-gravity-forms' ),
			],
			'hasSchedule'    => [
				'type'        => 'Boolean',
				'description' => __( 'Specifies if this form is scheduled to be displayed only during a certain configured date/time.', 'wp-graphql-gravity-forms' ),
			],
			'pendingMessage' => [
				'type'        => 'String',
				'description' => __( 'Message to be displayed when form is not yet available.', 'wp-graphql-gravity-forms' ),
			],
			'startDetails'   => [
				'type'        => FormScheduleDetails::$type,
				'description' => __( 'The Date/time details when the form will become active/visible.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
