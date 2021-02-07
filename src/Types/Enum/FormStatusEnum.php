<?php
/**
 * Enum Type - FormStatusEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Enum;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Enum;

/**
 * Class - FormStatusEnum
 */
class FormStatusEnum implements Hookable, Enum {
	const TYPE = 'FormStatusEnum';

	// Individual elements.
	const ACTIVE           = 'ACTIVE';
	const INACTIVE         = 'INACTIVE';
	const TRASHED          = 'TRASHED';
	const INACTIVE_TRASHED = 'INACTIVE_TRASHED';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register' ] );
	}

	/**
	 * Registers Enum type.
	 */
	public function register() {
		register_graphql_enum_type(
			self::TYPE,
			[
				'description' => __( 'Status of forms to get. Default is ACTIVE.', 'wp-graphql-gravity-forms' ),
				'values'      => [
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
				],
			]
		);
	}
}
