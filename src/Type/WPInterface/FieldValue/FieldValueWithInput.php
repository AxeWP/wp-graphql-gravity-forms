<?php
/**
 * Interface - Gravity Forms field value with a connected input.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldValue
 * @since   @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldValue;

use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Type\WPInterface\FieldInputProperty;

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
	public static function get_description() : string {
		return __( 'Gravity Forms field value with connected input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'connectedInput' => [
				'type'        => FieldInputProperty::$type,
				'description' => __( 'The selected Gravity Forms field input object.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
