<?php
/**
 * GraphQL Interface for a FormField with the `choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldChoiceSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting\AbstractFieldChoiceSetting;

/**
 * Class - ChoiceWithChoices
 */
class ChoiceWithChoices extends AbstractFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithChoicesSetting';

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
		add_filter( 'graphql_gf_form_field_setting_choice_fields', [ self::class, 'add_fields_to_child_type' ], 10, 5 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
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
	 * @param array     $fields An array of GraphQL field configs.
	 * @param string    $choice_name The name of the choice type.
	 * @param \GF_Field $field The Gravity Forms Field object.
	 * @param array     $settings The `form_editor_field_settings()` key.
	 * @param array     $interfaces The list of interfaces for the GraphQL type.
	 */
	public static function add_fields_to_child_type( array $fields, string $choice_name, GF_Field $field, array $settings, array $interfaces ): array {
		if (
			! in_array( self::$type, $interfaces, true ) ||
			( empty( $field->enablePrice ) && ! in_array( $field->type, [ 'product', 'quantity', 'option', 'shipping' ], true ) )
		) {
			return $fields;
		}

		$fields['price']          = [
			'type'        => 'Float',
			'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
			'resolve'     => static fn ( $source ) => ! empty( $source['price'] ) ? floatval( preg_replace( '/[^\d\.]/', '', $source['price'] ) ) : null,
		];
		$fields['formattedPrice'] = [
			'type'        => 'String',
			'description' => __( 'The price associated with the choice.', 'wp-graphql-gravity-forms' ),
			'resolve'     => static fn ( $source ) => ! empty( $source['price'] ) ? $source['price'] : null,
		];

		return $fields;
	}
}
