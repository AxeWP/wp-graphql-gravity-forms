<?php
/**
 * GraphQL Interface for a FormField with the `select_all_choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FieldInputRegistry;

/**
 * Class - FieldWithSelectAllChoices
 */
class FieldWithSelectAllChoices extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSelectAllChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'select_all_choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_gf_after_register_form_field_object', [ __CLASS__, 'add_inputs' ], 10, 2 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the \"select all\" choice should be displayed.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) : bool => ! empty( $source->enableSelectAll ),
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
		FieldInputRegistry::register( $field, $settings );
	}
}
