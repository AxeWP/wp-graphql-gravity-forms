<?php
/**
 * GraphQL Interface for a FormField with the `autocomplete_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;

/**
 * Class - FieldWithAutocomplete
 */
class FieldWithAutocomplete extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAutocompleteSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'autocomplete_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_filter( 'graphql_gf_form_field_setting_fields', [ self::class, 'add_fields_to_child_type' ], 10, 4 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasAutocomplete' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether autocomplete should be enabled for this field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->enableAutocomplete ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param array     $fields An array of GraphQL field configs.
	 * @param \GF_Field $field The Gravity Forms Field object.
	 * @param array     $settings The `form_editor_field_settings()` key.
	 * @param array     $interfaces The list of interfaces for the GraphQL type.
	 */
	public static function add_fields_to_child_type( array $fields, GF_Field $field, array $settings, array $interfaces ): array {
		// Bail early.
		if (
			! in_array( self::$type, $interfaces, true ) ||
			in_array( $field->type, [ 'address', 'email', 'name' ], true )
		) {
			return $fields;
		}

		$fields['autocompleteAttribute'] = [
			'type'        => 'String',
			'description' => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
		];

		return $fields;
	}
}
