<?php
/**
 * GraphQL Object Type - Gravity Forms form data policies.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormDataPolicies
 */
class FormDataPolicies extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormDataPolicies';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The policies governing which entry data to include when erasing and exporting personal data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'identificationFieldDatabaseId' => [
				'type'        => 'Int',
				'description' => __( 'The database ID of the Gravity Forms field used to identify the user.', 'wp-graphql-gravity-forms' ),
			],
			'entryData'                     => [
				'type'        => [ 'list_of' => FormEntryDataPolicy::$type ],
				'description' => __( 'The individual entry data exporting and erasing policies.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
