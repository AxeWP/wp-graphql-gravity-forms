<?php
/**
 * Enum Type - VisibilityPropertyEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - VisibilityPropertyEnum
 */
class VisibilityPropertyEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'VisibilityPropertyEnum';

	// Individual elements.
	const VISIBLE        = 'visible';
	const HIDDEN         = 'hidden';
	const ADMINISTRATIVE = 'administrative';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Field visibility.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'VISIBLE'        => [
				'description' => __( 'The field is "visible".', 'wp-graphql-gravity-forms' ),
				'value'       => self::VISIBLE,
			],
			'HIDDEN'         => [
				'description' => __( 'The field is "hidden".', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
			'ADMINISTRATIVE' => [
				'description' => __( 'The field is for "administrative" use.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ADMINISTRATIVE,
			],
		];
	}
}
