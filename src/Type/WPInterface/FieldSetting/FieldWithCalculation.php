<?php
/**
 * GraphQL Interface for a FormField with the `calculation_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
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
	public static string $type = 'GfFieldWithCalculation';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'calculation_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'isCalculation'       => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the number field is a calculation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableCalculation ),
			],
			'calculationFormula'  => [
				'type'        => 'String',
				'description' => __( 'The formula used for the number field.', 'wp-graphql-gravity-forms' ),
			],
			'calculationRounding' => [
				'type'        => 'Int',
				'description' => __( 'Specifies to how many decimal places the number should be rounded. This is available when isCalculation is true, but is not available when the chosen format is “Currency”.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
