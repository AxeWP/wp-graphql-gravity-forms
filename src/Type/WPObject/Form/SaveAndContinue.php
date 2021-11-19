<?php
/**
 * GraphQL Object Type - Gravity Forms 'Save and Continue' data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - SaveAndContinue
 */
class SaveAndContinue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SaveAndContinue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms form Save and Continue data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
