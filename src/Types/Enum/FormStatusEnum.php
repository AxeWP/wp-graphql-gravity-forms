<?php
/**
 * Enum Type - FormStatusEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FormStatusEnum
 */
class FormStatusEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormStatusEnum';

	// Individual elements.
	const ACTIVE           = 'ACTIVE';
	const INACTIVE         = 'INACTIVE';
	const TRASHED          = 'TRASHED';
	const INACTIVE_TRASHED = 'INACTIVE_TRASHED';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Status of forms to get. Default is ACTIVE.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			self::ACTIVE           => [
				'description' => __( 'Active forms (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ACTIVE,
			],
			self::INACTIVE         => [
				'description' => __( 'Inactive forms', 'wp-graphql-gravity-forms' ),
				'value'       => self::INACTIVE,
			],
			self::TRASHED          => [
				'description' => __( 'Active forms in the trash.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TRASHED,
			],
			self::INACTIVE_TRASHED => [
				'description' => __( 'Inactive forms in the trash.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INACTIVE_TRASHED,
			],
		];
	}
}
