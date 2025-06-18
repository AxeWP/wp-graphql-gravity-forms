<?php
/**
 * GraphQL Interface for choice on a FormField with the `chained_choices_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldChoiceSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldChoiceSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting\AbstractFieldChoiceSetting;

/**
 * Class - ChoiceWithChainedChoices
 */
class ChoiceWithChainedChoices extends AbstractFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithChainedChoices';

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
		add_filter( 'graphql_gf_form_field_setting_choice_fields', [ self::class, 'add_fields_to_child_type' ], 10, 4 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isSelected' => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param array<string,array<string,mixed>> $fields An array of GraphQL field configs.
	 * @param string                            $choice_name The name of the choice type.
	 * @param \GF_Field                         $field The Gravity Forms Field object.
	 * @param string[]                          $settings The `form_editor_field_settings()` keys.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function add_fields_to_child_type( array $fields, string $choice_name, GF_Field $field, array $settings ): array {
		if (
			! in_array( self::$field_setting, $settings, true )
		) {
			return $fields;
		}

		$fields['choices'] = [
			'type'        => [ 'list_of' => $choice_name ],
			'description' => static fn () => sprintf(
				// translators: The choice object GraphQL name.
				__( 'The nested %s choice.', 'wp-graphql-gravity-forms' ),
				ucfirst( $choice_name ),
			),
		];
		return $fields;
	}
}
