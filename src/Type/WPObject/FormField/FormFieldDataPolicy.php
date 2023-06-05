<?php
/**
 * GraphQL Object Type - Gravity Forms form field data policies.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.10.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormFieldDataPolicy
 */
class FormFieldDataPolicy extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldDataPolicy';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The form field-specifc policies for exporting and erasing personal data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'shouldErase'           => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this field should be included when erasing personal data.', 'wp-graphql-gravity-forms' ),
			],
			'shouldExport'          => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this field should be included when exporting personal data.', 'wp-graphql-gravity-forms' ),
			],
			'isIdentificationField' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this field is used to identify the user\'s personal data.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
