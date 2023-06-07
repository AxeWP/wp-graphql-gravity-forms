<?php
/**
 * GraphQL Object Type - Gravity Forms Personal Data data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormRetentionPolicyEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormPersonalData
 */
class FormPersonalData extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormPersonalData';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form Personal Data settings.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'daysToRetain'    => [
				'type'        => 'Int',
				'description' => __( 'The number of days to retain entries. `null` if `retentionPolicy` is set to `RETAIN` entries indefinitely.', 'wp-graphql-gravity-forms' ),
			],
			'retentionPolicy' => [
				'type'        => FormRetentionPolicyEnum::$type,
				'description' => __( 'The policy for retaining old entry data.', 'wp-graphql-gravity-forms' ),
			],
			'shouldSaveIP'    => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the IP address should be saved to the form submission.', 'wp-graphql-gravity-forms' ),
			],
			'dataPolicies'    => [
				'type'        => FormDataPolicies::$type,
				'description' => __( 'The policies governing which entry data to include when erasing and exporting personal data.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
