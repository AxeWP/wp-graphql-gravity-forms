<?php
/**
 * Enum Type - PageProgressTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - PageProgressTypeEnum
 */
class PageProgressTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PageProgressTypeEnum';

	// Individual elements.
	const PERCENTAGE = 'percentage';
	const STEPS      = 'steps';
	const NONE       = 'none';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of page progress indicator to be displayed', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'PERCENTAGE' => [
				'description' => __( 'Show page progress indicator as a percentage.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PERCENTAGE,
			],
			'STEPS'      => [
				'description' => __( 'Show page progress indicator as steps.', 'wp-graphql-gravity-forms' ),
				'value'       => self::STEPS,
			],
			'NONE'       => [
				'description' => __( "Don't show a page progress indicator.", 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
		];
	}
}
