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
 * Class - FormEntryDataPolicy
 */
class FormEntryDataPolicy extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormEntryDataPolicy';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual entry data exporting and erasing policies.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'key'          => [
				'type'        => 'String',
				'description' => __( 'The array key for the Gravity Forms Entry.', 'wp-graphql-gravity-forms' ),
			],
			'shouldErase'  => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this field should be included when erasing personal data.', 'wp-graphql-gravity-forms' ),
			],
			'shouldExport' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this field should be included when exporting personal data.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
