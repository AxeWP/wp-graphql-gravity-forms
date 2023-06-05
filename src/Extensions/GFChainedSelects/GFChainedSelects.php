<?php
/**
 * Adds support for GFChainedSelects.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects,
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects;

use GF_Field;
use WPGraphQL\GF\Extensions\GFChainedSelects\Data\FieldValueInput\ChainedSelectValuesInput;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Input;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue\ValueProperty;
use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - GFChainedSelects
 */
class GFChainedSelects implements Hookable {
	/**
	 * Hook extension into plugin.
	 */
	public static function register_hooks(): void {
		if ( ! self::is_gf_chained_selects_enabled() ) {
			return;
		}

		// Register Enums.
		add_filter( 'graphql_gf_registered_enum_classes', [ self::class, 'enums' ] );

		// Register Inputs.
		add_filter( 'graphql_gf_registered_input_classes', [ self::class, 'inputs' ] );

		// Register Form Field Settings interfaces.
		add_filter( 'graphql_gf_registered_form_field_setting_classes', [ self::class, 'form_field_settings' ] );
		add_filter( 'graphql_gf_registered_form_field_setting_choice_classes', [ self::class, 'form_field_setting_choices' ] );
		add_filter( 'graphql_gf_registered_form_field_setting_input_classes', [ self::class, 'form_field_setting_inputs' ] );

		// Register FieldValueInput.
		add_filter( 'graphql_gf_field_value_input_class', [ self::class, 'field_value_input' ], 10, 3 );

		// Register field value property.
		add_filter( 'graphql_gf_form_field_value_fields', [ self::class, 'field_value_fields' ], 10, 2 );

		// Register fieldValues input.
		add_filter( 'graphql_gf_form_field_values_input_fields', [ self::class, 'field_values_input_fields' ] );

		// Map GF field name.
		add_filter( 'graphql_gf_form_fields_name_map', [ self::class, 'form_field_name' ] );
	}

	/**
	 * Returns whether Gravity Forms Signature is enabled.
	 */
	public static function is_gf_chained_selects_enabled(): bool {
		return class_exists( 'GFChainedSelects' );
	}

	/**
	 * Register enum classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function enums( array $registered_classes ): array {
		$registered_classes[] = Enum\ChainedSelectFieldAlignmentEnum::class;
		return $registered_classes;
	}

	/**
	 * Register input classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function inputs( array $registered_classes ): array {
		$registered_classes[] = Input\ChainedSelectFieldInput::class;
		return $registered_classes;
	}

	/**
	 * Registers the mapped list of GF form field settings to their interface classes.
	 *
	 * @param array $classes .
	 */
	public static function form_field_settings( array $classes ): array {
		$classes['chained_choices_setting']               = WPInterface\FieldSetting\FieldWithChainedChoices::class;
		$classes['chained_selects_alignment_setting']     = WPInterface\FieldSetting\FieldWithChainedSelectsAlignment::class;
		$classes['chained_selects_hide_inactive_setting'] = WPInterface\FieldSetting\FieldWithChainedSelectsHideInactive::class;

		return $classes;
	}

	/**
	 * Registers the mapped list of GF form field settings to their choice interface classes.
	 *
	 * @param array $classes .
	 */
	public static function form_field_setting_choices( array $classes ): array {
		$classes['chained_choices_setting'] = WPInterface\FieldChoiceSetting\ChoiceWithChainedChoices::class;

		return $classes;
	}

	/**
	 * Registers the mapped list of GF form field settings to their choice interface classes.
	 *
	 * @param array $classes .
	 */
	public static function form_field_setting_inputs( array $classes ): array {
		$classes['chained_choices_setting'] = WPInterface\FieldInputSetting\InputWithChainedChoices::class;

		return $classes;
	}

	/**
	 * Registers the SignatureValuesInput class.
	 *
	 * @param string    $input_class .
	 * @param array     $args .
	 * @param \GF_Field $field .
	 */
	public static function field_value_input( string $input_class, array $args, GF_Field $field ): string {
		if ( 'chainedselect' === $field->get_input_type() ) {
			$input_class = ChainedSelectValuesInput::class;
		}

		return $input_class;
	}

	/**
	 * Registers ChainedSelect field value.
	 *
	 * @param array     $fields .
	 * @param \GF_Field $field .
	 */
	public static function field_value_fields( array $fields, GF_Field $field ): array {
		if ( 'chainedselect' === $field->get_input_type() ) {
			$fields = array_merge( $fields, ValueProperty::chained_select_values() );
		}

		return $fields;
	}

	/**
	 * Registers `fieldValues` input fields.
	 *
	 * @param array $fields .
	 */
	public static function field_values_input_fields( array $fields ): array {
		if ( ! isset( $fields['chainedSelectValues'] ) ) {
			$fields['chainedSelectValues'] = [
				'type'        => [ 'list_of' => Input\ChainedSelectFieldInput::$type ],
				'description' => __( 'The form field values for ChainedSelect fields.', 'wp-graphql-gravity-forms' ),
			];
		}

		return $fields;
	}

	/**
	 * Maps the Gravity Forms Field type to a safe GraphQL type (in PascalCase ).
	 *
	 * @param array $fields_to_map .
	 */
	public static function form_field_name( array $fields_to_map ): array {
		$fields_to_map['chainedselect'] = 'ChainedSelect';

		return $fields_to_map;
	}
}
