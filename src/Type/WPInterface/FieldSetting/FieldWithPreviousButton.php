<?php
/**
 * GraphQL Interface for a FormField with the `previous_button` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPObject\Button\FormButton;

/**
 * Class - FieldWithPreviousButton
 */
class FieldWithPreviousButton extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPreviousButton';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'previous_button';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'previousButton' => [
				'type'        => FormButton::$type,
				'description' => __( 'An array containing the the individual properties for the "Previous" button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
