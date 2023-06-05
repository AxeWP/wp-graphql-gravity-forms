<?php
/**
 * GraphQL Object Type - Gravity Forms Entry Limits data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormLimitEntriesPeriodEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormEntryLimits
 */
class FormEntryLimits extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormEntryLimits';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form entry limititation details.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasLimit'            => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the form has a limit on the number of submissions.', 'wp-graphql-gravity-forms' ),
			],
			'limitationPeriod'    => [
				'type'        => FormLimitEntriesPeriodEnum::$type,
				'description' => __( 'The time period during which submissions are allowed.', 'wp-graphql-gravity-forms' ),
			],
			'limitReachedMessage' => [
				'type'        => 'String',
				'description' => __( 'Message that will be displayed when the maximum number of submissions have been reached.', 'wp-graphql-gravity-forms' ),
			],
			'maxEntries'          => [
				'type'        => 'Int',
				'description' => __( 'The number of submissions allowed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
