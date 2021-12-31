<?php
/**
 * Array configs for all field properties.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperties;

/**
 * Class - ValueProperty
 */
class ValueProperty extends ValueProperties {
	/**
	 * Get `values` property for Chained Select field.
	 */
	public static function chained_select_values() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'ChainedSelect field value.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function ( $source, array $args, AppContext $context ) {
					if ( ! self::is_field_and_entry( $source, $context ) ) {
						return null;
					}

					return array_map(
						function( $input ) use ( $context ) {
							return $context->gfEntry->entry[ $input['id'] ] ?: null;
						},
						$source->inputs
					);
				},
			],
		];
	}
}
