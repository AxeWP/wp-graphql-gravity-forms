<?php
/**
 * GraphQL Interface for a FormField with the `range_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithRange
 */
class FieldWithRange extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithRangeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'range_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'rangeMax' => [
				'type'        => 'Float',
				'description' => __( 'Maximum allowed value for a number field. Values higher than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?float {
					if ( ! isset( $source->rangeMax ) ) {
						return null;
					}

					$numeric_max = isset( $source->numberFormat ) && 'decimal_comma' === $source->numberFormat ? \GFCommon::clean_number( $source->rangeMax, 'decimal_comma' ) : $source->rangeMax;

					return is_numeric( $numeric_max ) ? (float) $numeric_max : null;
				},
			],
			'rangeMin' => [
				'type'        => 'Float',
				'description' => __( 'Minimum allowed value for a number field. Values lower than the number specified by this property will cause the field to fail validation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?float {
					if ( ! isset( $source->rangeMin ) ) {
						return null;
					}

					$numeric_min = isset( $source->numberFormat ) && 'decimal_comma' === $source->numberFormat ? \GFCommon::clean_number( $source->rangeMin, 'decimal_comma' ) : $source->rangeMin;

					return is_numeric( $numeric_min ) ? (float) $numeric_min : null;
				},
			],
		];
	}
}
