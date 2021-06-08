<?php
/**
 * GraphQL Object Type - Gravity Forms 'Save and Continue' data.
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - SaveAndContinue
 */
class SaveAndContinue extends AbstractType {
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
