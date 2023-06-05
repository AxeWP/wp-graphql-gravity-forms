<?php
/**
 * GraphQL Object Type - Gravity Forms Login data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\AmPmEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormScheduleDetails
 */
class FormScheduleDetails extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormScheduleDetails';

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
			'amPm'    => [
				'type'        => AmPmEnum::$type,
				'description' => __( 'Whether the date is in the AM or PM of a 12-hour clock.', 'wp-graphql-gravity-forms' ),
			],
			'date'    => [
				'type'        => 'String',
				'description' => __( 'The schedule date in local time.', 'wp-graphql-gravity-forms' ),
			],
			'dateGmt' => [
				'type'        => 'String',
				'description' => __( 'The schedule date in GMT.', 'wp-graphql-gravity-forms' ),
			],
			'hour'    => [
				'type'        => 'Int',
				'description' => __( 'The hour (1-12).', 'wp-graphql-gravity-forms' ),
			],
			'minute'  => [
				'type'        => 'Int',
				'description' => __( 'The minute.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
