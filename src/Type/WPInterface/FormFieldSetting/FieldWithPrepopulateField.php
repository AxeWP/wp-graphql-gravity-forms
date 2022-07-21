<?php
/**
 * GraphQL Interface for a FormField with the `prepopulate_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;

/**
 * Class - FieldWithPrepopulateField
 */
class FieldWithPrepopulateField extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPrepopulateField';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'prepopulate_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_filter( 'graphql_gf_form_field_setting_fields', [ __CLASS__, 'add_fields_to_child_type' ], 10, 4 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'canPrepopulate' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field’s value can be pre-populated dynamically.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->allowsPrepopulate ),
			],
		];
	}

	/**
	 * Registers a GraphQL field to the GraphQL type that implements this interface.
	 *
	 * @param array    $fields An array of GraphQL field configs.
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 * @param array    $interfaces The list of interfaces for the GraphQL type.
	 */
	public static function add_fields_to_child_type( array $fields, GF_Field $field, array $settings, array $interfaces ) : array {
		// Bail early.
		if (
			! in_array( self::$type, $interfaces, true ) ||
			'checkbox' !== $field->type &&
			( ! empty( $field->inputs ) || in_array( $field->inputType, [ 'email', 'time', 'password' ], true ) )
		) {
			return $fields;
		}

		$fields['inputName'] = [
			'type'        => 'String',
			'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
		];

		return $fields;
	}
}
