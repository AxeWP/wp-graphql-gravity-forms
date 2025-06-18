<?php
/**
 * Array configs for the Value property.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue
 * @since   0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Model\FormField;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\FieldValues;

/**
 * Class - ValueProperty
 */
class ValueProperty extends FieldValues {
	/**
	 * Get `values` property for Chained Select field.
	 *
	 * @return array{values:array<string,mixed>}
	 */
	public static function chained_select_values(): array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => static fn () => __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ): ?array {
					if ( ! $source instanceof FormField || ! isset( $context->gfEntry ) ) {
						return null;
					}

					return array_map(
						static function ( $input ) use ( $context ) {
							return $context->gfEntry->entry[ $input['id'] ] ?: null;
						},
						$source->inputs
					);
				},
			],
		];
	}
}
