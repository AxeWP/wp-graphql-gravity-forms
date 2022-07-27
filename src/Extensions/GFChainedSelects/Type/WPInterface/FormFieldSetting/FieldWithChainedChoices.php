<?php
/**
 * GraphQL Interface for a FormField with the `chained_choices_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FormFieldChoiceRegistry;
use WPGraphQL\GF\Registry\FormFieldInputRegistry;
use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithChainedChoices
 */
class FieldWithChainedChoices extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_choices_setting';

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
	 */
	public static function get_fields() : array {
		return [
			'hasChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableChoiceValue ),
			],
		];
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
		FormFieldChoiceRegistry::register( $field, $settings );
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
