<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.7.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use GF_Fields;
use GF_Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractFormField
 */
abstract class AbstractFormField extends AbstractObject {
	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type;

	/**
	 * {@inheritDoc}
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = true;

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			self::prepare_config(
				[
					'description'     => static::get_description(),
					'interfaces'      => [ FormField::$type ],
					'fields'          => static::get_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}

	/**
	 * Converts the Gravity Forms Setting groups into field properties.
	 *
	 * Used to autoregister FormField fields.
	 */
	protected static function get_fields_from_gf_settings() : array {
		$fields = GF_Fields::get_all();

		if ( empty( $fields[ static::$gf_type ] ) ) {
			return [];
		}

		$settings = $fields[ static::$gf_type ]->get_form_editor_field_settings();

		if ( ! empty( $fields[ static::$gf_type ]->inputType ) ) {
			$input_type = $fields[ static::$gf_type ]->inputType;

			$additional_settings = $fields[ $input_type ]->get_form_editor_field_settings();

			if ( ! empty( $additional_settings ) ) {
				$settings += $additional_settings;
			}
		}

		return static::get_field_properties_from_settings( $settings, $fields[ static::$gf_type ] );
	}

	/**
	 * Grabs the GraphQL FormField property for for the corresponding GF field setting.
	 *
	 * @param array    $settings .
	 * @param GF_Field $field .
	 */
	private static function get_field_properties_from_settings( array $settings, GF_Field $field ) : array {
		$properties = [];

		foreach ( $settings as $setting ) {
			switch ( $setting ) {
				case 'admin_label_setting':
					$properties[] = FieldProperty\AdminLabelProperty::get();
					break;
				case 'autocomplete_setting':
					$properties[] = FieldProperty\EnableAutocompleteProperty::get();
					if ( empty( $field->inputs ) ) {
						$properties[] = FieldProperty\AutocompleteAttributeProperty::get();
					}
					break;
				case 'conditional_logic_field_setting':
					$properties[] = FieldProperty\ConditionalLogicProperty::get();
					break;
				case 'css_class_setting':
					$properties[] = FieldProperty\CssClassProperty::get();
					break;
				case 'default_value_setting':
				case 'default_value_textarea_setting':
					$properties[] = FieldProperty\DefaultValueProperty::get();
					break;
				case 'description_setting':
					$properties[] = FieldProperty\DescriptionProperty::get();
					break;
				case 'duplicate_setting':
					$properties[] = FieldProperty\NoDuplicatesProperty::get();
					break;
				case 'error_message_setting':
					$properties[] = FieldProperty\ErrorMessageProperty::get();
					break;
				case 'label_setting':
					$properties[] = FieldProperty\LabelProperty::get();
					break;
				case 'label_placement_setting':
					$properties[] = FieldProperty\LabelPlacementProperty::get();
					break;
				case 'placeholder_setting':
				case 'placeholder_textarea_setting':
					$properties[] = FieldProperty\PlaceholderProperty::get();
					break;
				case 'prepopulate_field_setting':
					$properties[] = FieldProperty\AllowsPrepopulateProperty::get();
					if ( empty( $field->inputs ) || 'checkbox' === $field->type || ! in_array( $field->type, [ 'date', 'email', 'time', 'password' ], true ) ) {
						$properties[] = FieldProperty\InputNameProperty::get();
					}
					break;
				case 'rules_setting':
					$properties[] = FieldProperty\IsRequiredProperty::get();
					break;
				case 'size_setting':
					$properties[] = FieldProperty\SizeProperty::get();
					break;
				case 'sub_label_placement_setting':
					$properties[] = FieldProperty\SubLabelPlacementProperty::get();
					break;
			}
		}
		return $properties;
	}
}
