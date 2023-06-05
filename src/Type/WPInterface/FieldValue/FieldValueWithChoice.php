<?php
/**
 * Interface - Gravity Forms field value with a connected choice.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldValue
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldValue;

use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Type\WPInterface\FieldChoice;

/**
 * Class - FieldValueWithChoice
 */
class FieldValueWithChoice extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldValueWithChoice';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms field value with connected choice.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'connectedChoice' => [
				'type'        => FieldChoice::$type,
				'description' => __( 'The selected Gravity Forms field choice object.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
