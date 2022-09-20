<?php
/**
 * GraphQL Interface for a FormField with the `name_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FieldChoiceRegistry;
use WPGraphQL\GF\Registry\FieldInputRegistry;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithName
 */
class FieldWithName extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithNameSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'name_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_gf_register_form_field_inputs', [ __CLASS__, 'add_inputs' ], 11, 2 );
		add_action( 'graphql_gf_register_form_field_choices', [ __CLASS__, 'add_choices' ], 10, 2 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 *
	 * There is no Field interface for the setting.
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 */
	public static function add_choices( GF_Field $field, array $settings ) : void {
		if (
			! in_array( self::$field_setting, $settings, true )
		) {
			return;
		}

		// Register the FieldChoice for the object.
		FieldChoiceRegistry::register( $field, $settings );
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