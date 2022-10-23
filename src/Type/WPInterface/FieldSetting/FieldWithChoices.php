<?php
/**
 * GraphQL Interface for a FormField with the `choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FieldChoiceRegistry;

/**
 * Class - FieldWithChoices
 */
class FieldWithChoices extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
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
				'resolve'     => static fn ( $source ) => ! empty( $source->enableChoiceValue ),
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
		FieldChoiceRegistry::register( $field, $settings );
	}
}
