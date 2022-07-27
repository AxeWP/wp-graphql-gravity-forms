<?php
/**
 * GraphQL Interface for a FormField with the `name_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldChoiceSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldChoiceSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPInterface\FormFieldChoiceSetting\AbstractFormFieldChoiceSetting;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - ChoiceWithName
 */
class ChoiceWithName extends AbstractFormFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithName';

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
		add_action( 'graphql_gf_after_register_form_field', [ __CLASS__, 'add_choice_to_inputs' ], 11, 2 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'isSelected' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 */
	public static function add_choice_to_inputs( GF_Field $field, array $settings ) : void {
		if (
			! in_array( self::$field_setting, $settings, true )
		) {
			return;
		}

		$input_type = $field->get_input_type();

		$name = ( $field->type !== $input_type ? $field->type . '_' . $input_type : $field->type );

		$choice_name = Utils::get_safe_form_field_type_name( $name . 'FieldChoice' );

		$input_name = Utils::get_safe_form_field_type_name( $name . 'InputProperty' );

		$config = [
			'type'        => $choice_name,
			'description' => sprintf(
				// translators: The choice object GraphQL name.
				__( 'The nested %s choice.', 'wp-graphql-gravity-forms' ),
				ucfirst( $choice_name ),
			),
		];

		register_graphql_field( $input_name, 'choices', $config );
	}
}
