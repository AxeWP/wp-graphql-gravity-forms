<?php
/**
 * GraphQL Object Type - Gravity Forms 'Save and Continue' data.
 *
 * @package WPGraphQL\GF\Types\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Form;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - SaveAndContinue
 */
class SaveAndContinue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SaveAndContinue';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms form Save and Continue data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'enabled'    => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the Save And Continue feature is enabled.', 'wp-graphql-gravity-forms' ),
			],
			'buttonText' => [
				'type'        => 'string',
				'description' => __( 'Contains the save button text.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $root ) : string {
					return $root['button']['text'];
				},
			],
		];
	}
}
