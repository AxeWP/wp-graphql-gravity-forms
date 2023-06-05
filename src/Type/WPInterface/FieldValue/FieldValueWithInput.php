<?php
/**
 * Interface - Gravity Forms field value with a connected input.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldValue
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldValue;

use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Type\WPInterface\FieldInput;

/**
 * Class - FieldValueWithInput
 */
class FieldValueWithInput extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldValueWithInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms field value with connected input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'connectedInput' => [
				'type'        => FieldInput::$type,
				'description' => __( 'The selected Gravity Forms field input object.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
