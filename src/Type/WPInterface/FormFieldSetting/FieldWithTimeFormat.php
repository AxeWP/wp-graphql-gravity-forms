<?php
/**
 * GraphQL Interface for a FormField with the `time_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FormFieldInputRegistry;
use WPGraphQL\GF\Type\Enum\TimeFieldFormatEnum;

/**
 * Class - FieldWithTimeFormat
 */
class FieldWithTimeFormat extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithTimeFormat';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'time_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_gf_register_form_field_inputs', [ __CLASS__, 'add_inputs' ], 11, 2 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'timeFormat' => [
				'type'        => TimeFieldFormatEnum::$type,
				'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 */
	public static function add_inputs( GF_Field $field, array $settings ) : void {
		if (
			! in_array( self::$field_setting, $settings, true )
		) {
			return;
		}

		// Register the FieldChoice for the object.
		FormFieldInputRegistry::register( $field, $settings );
	}
}
