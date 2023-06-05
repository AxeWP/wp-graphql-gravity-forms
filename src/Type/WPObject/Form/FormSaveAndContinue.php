<?php
/**
 * GraphQL Object Type - Gravity Forms 'Save and Continue' data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormSaveAndContinue
 */
class FormSaveAndContinue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormSaveAndContinue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form Save and Continue data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasSaveAndContinue' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the Save And Continue feature is enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['enabled'] ),
			],
			'buttonText'         => [
				'type'        => 'String',
				'description' => __( 'Contains the save button text.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source): string => ! empty( $source['button']['text'] ) ? $source['button']['text'] : null,
			],
		];
	}
}
