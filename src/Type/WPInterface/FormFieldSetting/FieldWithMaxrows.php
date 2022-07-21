<?php
/**
 * GraphQL Interface for a FormField with the `maxrows_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithMaxrows
 */
class FieldWithMaxrows extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithMaxrows';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'maxrows_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'maxRows' => [
				'type'        => 'Int',
				'description' => __( 'The maximum number of rows the user can add to the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
