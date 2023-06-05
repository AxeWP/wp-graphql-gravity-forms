<?php
/**
 * GraphQL Interface for a FormField with the `maxrows_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithMaxRows
 */
class FieldWithMaxRows extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithMaxRowsSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'maxrows_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'maxRows' => [
				'type'        => 'Int',
				'description' => __( 'The maximum number of rows the user can add to the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
