<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Model\Entry;

/**
 * Class - ValueProperties
 */
class ValueProperties {
	/**
	 * Get `value` property.
	 */
	public static function value() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The string-formatted entry value for the `formField`. For complex fields this might be a JSON-encoded or serialized array.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! $source instanceof GF_Field ||
						! isset( $context->gfEntry )
						|| ! isset( $context->gfEntry->entry )
					) {
						return null;
					}

					return $source->get_value_export( $context->gfEntry->entry, $source->id ) ?: null;
				},
			],
		];
	}
}
