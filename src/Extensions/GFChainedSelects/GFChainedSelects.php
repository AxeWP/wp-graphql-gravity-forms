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
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldProperty\PropertyMapper;
use WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPObject\FormField\FieldValue\ValueProperty;

/**
 * Class - GFChainedSelects
 */
class GFChainedSelects {
	/**
	 * Hook extension into plugin.
	 */
	public static function register_hooks() : void {
		if ( ! self::is_gf_chained_selects_enabled() ) {
			return;
		}

		// Register Enums.
		add_filter( 'graphql_gf_registered_enum_classes', [ __CLASS__, 'enums' ] );

		// Register Inputs.
		add_filter( 'graphql_gf_registered_input_classes', [ __CLASS__, 'inputs' ] );

		// Register SignatureFieldValueInput.
		add_filter( 'graphql_gf_field_value_input_class', [ __CLASS__, 'field_value_input' ], 10, 3 );

		// Register field_settings.
		add_filter( 'graphql_gf_form_field_setting_properties', [ __CLASS__, 'field_setting_properties' ], 10, 3 );

		// Register field value property.
		add_filter( 'graphql_gf_form_field_value_properties', [ __CLASS__, 'field_value_properties' ], 10, 2 );

		// Register fieldValues input.
		add_filter( 'graphql_gf_form_field_values_input_fields', [ __CLASS__, 'field_values_input_fields' ] );

		// Map GF field name.
		add_filter( 'graphql_gf_form_fields_name_map', [ __CLASS__, 'form_field_name' ] );
	}

	/**
	 * Returns whether Gravity Forms Signature is enabled.
	 *
	 * @return boolean
	 */
	public static function is_gf_chained_selects_enabled() : bool {
		return class_exists( 'GFChainedSelects' );
	}

	/**
	 * Register enum classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function enums( array $registered_classes ) : array {
		$registered_classes[] = Enum\ChainedSelectFieldAlignmentEnum::class;
		return $registered_classes;
	}

	/**
	 * Register input classes.
	 *
	 * @param array $registered_classes .
	 */
	public static function inputs( array $registered_classes ) : array {
		$registered_classes[] = Input\ChainedSelectFieldInput::class;
		return $registered_classes;
	}

	/**
	 * Registers the SignatureValuesInput class.
	 *
	 * @param string   $input_class .
	 * @param array    $args .
	 * @param GF_Field $field .
	 */
	public static function field_value_input( string $input_class, array $args, GF_Field $field ) : string {
		if ( 'chainedselect' === $field->get_input_type() ) {
			$input_class = ChainedSelectValuesInput::class;
		}

		return $input_class;
	}

	/**
	 * Registers Signature field settings mapper.
	 *
	 * @param array    $properties .
	 * @param string   $setting .
	 * @param GF_Field $field .
	 */
	public static function field_setting_properties( array $properties, string $setting, GF_Field $field ) : array {
		if ( method_exists( PropertyMapper::class, $setting ) ) {
			PropertyMapper::$setting( $field, $properties );
		}

		return $properties;
	}

	/**
	 * Registers Signature field settings mapper.
	 *
	 * @param array    $properties .
	 * @param GF_Field $field .
	 */
	public static function field_value_properties( array $properties, GF_Field $field ) : array {
		if ( 'chainedselect' === $field->get_input_type() ) {
			$properties += ValueProperty::chained_select_values();
		}

		return $properties;
	}

	/**
	 * Registers `fieldValues` input fields.
	 *
	 * @param array $fields .
	 */
	public static function field_values_input_fields( array $fields ) : array {
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
	public static function form_field_name( array $fields_to_map ) : array {
		$fields_to_map['chainedselect'] = 'ChainedSelect';

		return $fields_to_map;
	}
}
