<?php
/**
 * Array configs for the Value property.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\FieldValues;

/**
 * Class - ValueProperty
 */
class ValueProperty extends FieldValues {
	/**
	 * Get `values` property for Chained Select field.
	 */
	public static function chained_select_values(): array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ): ?array {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
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
