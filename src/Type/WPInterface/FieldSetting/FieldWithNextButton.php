<?php
/**
 * GraphQL Interface for a FormField with the `next_button_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPObject\Button\FormButton;

/**
 * Class - FieldWithNextButton
 */
class FieldWithNextButton extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithNextButtonSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'next_button_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'nextButton' => [
				'type'        => FormButton::$type,
				'description' => __( 'An array containing the the individual properties for the "Next" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
