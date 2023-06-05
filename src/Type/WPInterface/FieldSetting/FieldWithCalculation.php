<?php
/**
 * GraphQL Interface for a FormField with the `calculation_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithCalculation
 */
class FieldWithCalculation extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCalculationSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'calculation_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isCalculation'       => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the number field is a calculation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->enableCalculation ),
			],
			'calculationFormula'  => [
				'type'        => 'String',
				'description' => __( 'The formula used for the number field.', 'wp-graphql-gravity-forms' ),
			],
			'calculationRounding' => [
				'type'        => 'Int',
				'description' => __( 'Specifies to how many decimal places the number should be rounded. This is available when `isCalculation` is true, but will return null if the number format is `CURRENCY` or if the calculation is set to `Do not round`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					// Bail if the field doesn't have a calculationRounding property.
					if ( empty( $source->enableCalculation ) || ! isset( $source->calculationRounding ) ) {
						return null;
					}

					// Bail if the numberFormat is currency.
					if ( ! empty( $source->numberFormat ) && 'currency' === $source->numberFormat ) {
						return null;
					}

					// Bail if rounding is disabled.
					if ( 'norounding' === $source->calculationRounding ) {
						return null;
					}

					return isset( $source->calculationRounding ) ? (int) $source->calculationRounding : null;
				},
			],
		];
	}
}
