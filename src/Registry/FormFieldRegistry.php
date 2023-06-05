<?php
/**
 * Registers a Gravity Forms form field to the WPGraphQL schema.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Registry
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Registry;

use GF_Field;
use GF_Fields;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Registry\TypeRegistry as GFTypeRegistry;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithAddress;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithChoices;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithColumns;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithDateFormat;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithEmailConfirmation;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithName;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithOtherChoice;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithPassword;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithSelectAllChoices;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithSingleProductInputs;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithTimeFormat;
use WPGraphQL\GF\Type\WPInterface\FieldWithPersonalData;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\FieldValues;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormFieldRegisty
 */
class FormFieldRegistry {
	/**
	 * Register the Form Field type to the WPGraphQL schema.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 */
	public static function register( GF_Field $field ): void {
		$field->graphql_single_name = Utils::get_safe_form_field_type_name( $field->type ) . 'Field';

		$field_settings = self::get_field_settings( $field );

		$possible_types = Utils::get_possible_form_field_child_types( $field->type );

		// If there are no possible types, then this can be registered directly as a GraphQL object.
		if ( empty( $possible_types ) ) {
			$config = self::get_config_from_settings( $field, $field_settings );

			// Add description.
			$config['description']     = self::get_description( $field->type );
			$config['eagerlyLoadType'] = true;

			self::register_object_type( $field, $field_settings, $config );

			// Register any choices and inputs to the type.
			self::maybe_register_choices_and_inputs( $field, $field_settings );
		} else {
			// If there are possible types, then we need to register the parent interface and all the child types as objects.
			self::register_interface_and_types( $field, $field_settings, $possible_types );
		}

		/**
		 * Fires after the Gravity Forms field has been hooked to be registered WPGraphQL schema.
		 *
		 * The fields themselves will only be registered on the next get_graphql_register_action() call.
		 *
		 * @param \GF_Field $field The Gravity Forms field object.
		 * @param array    $field_settings The field settings.
		 */
		do_action( 'graphql_gf_after_register_form_field', $field, $field_settings );
		do_action( 'graphql_gf_after_register_form_field_' . $field->graphql_single_name, $field, $field_settings );
	}

	/**
	 * Registers the GF Form Field as a GraphQL object, wrapped in actions to keep things DRY.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $field_settings The Gravity Forms field settings.
	 * @param array     $config The config array as expected by WPGraphQL.
	 */
	protected static function register_object_type( GF_Field $field, array $field_settings, array $config ): void {
		add_action(
			get_graphql_register_action(),
			static function ( TypeRegistry $type_registry ) use ( $field, $config, $field_settings ) {
				if ( $type_registry->has_type( $field->graphql_single_name ) ) {
					return;
				}

				// Register the FormField to the schema.
				register_graphql_object_type( $field->graphql_single_name, $config );


				/**
				 * Fires after the Gravity Forms field object has been registered to WPGraphQL schema.
				 *
				 * @param \GF_Field $field The Gravity Forms field object.
				 * @param array    $field_settings The field settings.
				 * @param array    $config The config array as expected by WPGraphQL.
				 */
				do_action( 'graphql_gf_after_register_form_field_object', $field, $field_settings, $config );
				do_action( 'graphql_gf_after_register_form_field_object_' . $field->graphql_single_name, $field, $field_settings, $config );
			}
		);
	}

	/**
	 * Registers the GF Form Field as a GraphQL interface before registering it's child types as objects.
	 *
	 * @uses FormFields::register_gf_field_object() to register the child types.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $settings       The field settings.
	 * @param array     $possible_types The possible child types of the field. Null if no child types.
	 */
	protected static function register_interface_and_types( GF_Field $field, array $settings, array $possible_types ): void {
		// Store these for later use.
		$possible_settings = [];

		/**
		 * To get the interface settings, we want the shared settings for each of the possible types.
		 */
		foreach ( $possible_types as $gf_type => $graphql_type ) {
			$child_field = GF_Fields::get( $gf_type );
			if ( ! $child_field instanceof GF_Field ) {
				continue;
			}

			$possible_settings[ $gf_type ] = self::get_field_settings( $child_field );
		}

		// We flip the arrays and compare the keys for performance.
		$interface_settings = array_keys(
			array_intersect_key(
				... array_map( 'array_flip', array_values( $possible_settings ) )
			)
		);

		$interface_settings = array_merge( $settings, $interface_settings );

		// Register the interface.
		add_action(
			get_graphql_register_action(),
			static function ( TypeRegistry $type_registry ) use ( $field, $interface_settings, $possible_types ) {
				// Bail early if type exists.
				if ( $type_registry->has_type( $field->graphql_single_name ) ) {
					return;
				}

				$config = self::get_config_from_settings( $field, $interface_settings );

				$config['description'] = self::get_description( $field->type );

				$config['resolveType'] = static function ( $value ) use ( $type_registry, $possible_types ) {
					$input_type = $value->get_input_type();
					if ( isset( $possible_types[ $input_type ] ) ) {
						$type = $type_registry->get_type( $possible_types[ $value->$input_type ] );
						if ( null !== $type ) {
							return $type;
						}
					}

					throw new UserError(
						sprintf(
						/* translators: %s: GF field type */
							__( 'The "%1$1s" Gravity Forms field does not yet support the %2$2s input type.', 'wp-graphql-gravity-forms' ),
							$value->type,
							$value->inputType
						)
					);
				};

				register_graphql_interface_type( $field->graphql_single_name, $config );
			}
		);

		// Register any choices and inputs to the type.
		self::maybe_register_choices_and_inputs( $field, $interface_settings, true );

		// Loop through the child fields and register each one.
		foreach ( $possible_types as $gf_type => $graphql_type ) {
			if ( in_array( $gf_type, [ 'calculation', 'hiddenproduct', 'singleproduct', 'singleshipping' ], true ) ) {
				// These possible types are actually their own GF_Field classes that were skipped in the register() loop.
				$field_to_register = GF_Fields::get( $gf_type );
			} else {
				$field_to_register = clone( $field );
			}

			// Override the field config from the inherited GF field with those from the child type.
			$field_to_register->inputType           = $gf_type;
			$field_to_register->graphql_single_name = $graphql_type;

			$field_settings = array_diff( $possible_settings[ $gf_type ], $interface_settings );

			$config                    = self::get_config_from_settings( $field_to_register, $field_settings );
			$config['description']     = self::get_description( $gf_type . ' ' . $field_to_register->type );
			$config['interfaces']      = array_merge( $config['interfaces'], [ $field->graphql_single_name ] );
			$config['eagerlyLoadType'] = true;

			self::register_object_type( $field_to_register, $field_settings, $config );

			// Register any choices and inputs to the type.
			self::maybe_register_choices_and_inputs( $field_to_register, array_merge( $field_settings, $interface_settings ) );
		}
	}

	/**
	 * Gets the registered field settings, including those of input types.
	 *
	 * @param \GF_Field $field .
	 */
	public static function get_field_settings( GF_Field $field ): array {
		$settings = $field->get_form_editor_field_settings();

		// Product inputs are not stored as a setting, so we're going to fake it.
		if ( 'singleproduct' === $field->type ) {
			$settings[] = 'single_product_inputs';
		}

		$input_type = $field->get_input_type();

		// Bail early if the types are the same.
		if ( $input_type === $field->type ) {
			return $settings;
		}

		// Get the settings from the inherited field.
		$inherited_field    = GF_Fields::get( $input_type );
		$inherited_settings = $inherited_field->get_form_editor_field_settings();

		return array_merge( $settings, $inherited_settings );
	}

	/**
	 * Returns the config array used to register the form field.
	 *
	 * @param \GF_Field $field .
	 * @param array     $settings .
	 */
	public static function get_config_from_settings( GF_Field $field, array $settings ): array {
		// Every GF_field is a FormField.
		$interfaces = [
			FormField::$type,
		];

		$fields = [];

		// Maybe add personal data interface.
		if ( empty( $field->displayOnly ) && ! in_array( $field->type, [ 'html', 'page', 'section', 'captcha' ], true ) ) {
			$interfaces[] = FieldWithPersonalData::$type;
		}

		// Loop through the individual settings.
		foreach ( $settings as $setting ) {
			// Skip settings registered elsewhere.
			if ( in_array( $setting, Utils::get_ignored_gf_settings(), true ) ) {
				continue;
			}

			// Get the class for the GraphQL Interface class for the setting.
			$interface_class = self::get_setting_interface_class( $field, $setting );

			// Skip if no interface, or if interface was already added.
			if ( empty( $interface_class ) || in_array( $interface_class::$type, $interfaces, true ) ) {
				continue;
			}

			$interfaces[] = $interface_class::$type;
		}

		// Get any GraphQL fields fields specific to the settings.
		$fields = self::get_fields( $field, $settings, $interfaces );

		// Add field values.
		$fields = array_merge( $fields, self::get_field_value_fields( $field ) );

		return [
			'interfaces' => $interfaces,
			'fields'     => $fields,
		];
	}

	/**
	 * Gets the PHP class for the interface.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param string    $setting The Gravity Forms field setting key.
	 */
	public static function get_setting_interface_class( GF_Field $field, string $setting ): ?string {
		$class = null;

		$classes = GFTypeRegistry::form_field_settings();

		// Not all settings have a unique counterpart.
		switch ( $setting ) {
			case 'conditional_logic_field_setting':
			case 'conditional_logic_page_setting':
			case 'conditional_logic_nextbutton_setting':
				$class = isset( $classes['conditional_logic_setting'] ) ? $classes['conditional_logic_setting'] : null;
				break;
			case 'default_value_setting':
			case 'default_value_textarea_setting':
				$class = 'email' !== $field->type && isset( $classes['default_value_setting'] ) ? $classes['default_value_setting'] : null;
				break;
			case 'placeholder_textarea_setting':
				$class = isset( $classes['placeholder_setting'] ) ? $classes['placeholder_setting'] : null;
				break;
			default:
				$class = isset( $classes[ $setting ] ) ? $classes[ $setting ] : null;
				break;
		}

		if ( empty( $class ) || ! property_exists( $class, 'type' ) ) {
			return null;
		}

		return $class;
	}

	/**
	 * Gets the type desciption for the field.
	 *
	 * @param string $field_name The name of the field.
	 */
	public static function get_description( string $field_name ): string {
		// translators: GF field type and possibly parent type.
		return sprintf( __( 'A Gravity Forms %s field.', 'wp-graphql-gravity-forms' ), $field_name );
	}

	/**
	 * Gets the GraphQL fields config for the form field.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $settings The Gravity Forms field settings.
	 * @param array     $interfaces The list of interfaces to add to the field.
	 */
	public static function get_fields( GF_Field $field, array $settings, $interfaces ): array {
		$fields = [];

		if ( has_filter( 'graphql_gf_form_field_setting_properties' ) ) {
			foreach ( $settings as $setting_key ) {
				/**
				 * Filter to modify the Form Field GraphQL fields based on GF_Field::form_editor_field_settings().
				 *
				 * @deprecated 0.12.0 Use `graphql_gf_form_field_setting_fields` instead.
				 *
				 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
				 * @param string    $setting_key   The `form_editor_field_settings()` key.
				 * @param \GF_Field $field The Gravity Forms Field object.
				 * @param array    $interfaces The list of interfaces for the GraphQL type.
				 */
				$fields = apply_filters_deprecated( 'graphql_gf_form_field_setting_properties', [ $fields, $setting_key, $field ], '0.12.0', 'graphql_gf_form_field_setting_fields' );
			}
		}

		/**
		 * Filter to modify the Form Field GraphQL fields.
		 *
		 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param \GF_Field $field The Gravity Forms Field object.
		 * @param array    $settings The `form_editor_field_settings()` key.
		 * @param array    $interfaces The list of interfaces for the GraphQL type.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_setting_fields', $fields, $field, $settings, $interfaces );

		return apply_filters( 'graphql_gf_form_field_setting_fields_' . $field->graphql_single_name, $fields, $field, $settings, $interfaces );
	}

	/**
	 * Gets the Field Value field config.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 */
	public static function get_field_value_fields( GF_Field $field ): array {
		$fields = [];
		if ( ! empty( $field->displayOnly ) || in_array( $field->type, [ 'html', 'page', 'section' ], true ) ) {
			return $fields;
		}

		$fields += FieldValues::value();

		$input_type = $field->get_input_type();

		switch ( $input_type ) {
			case 'address':
				$fields += FieldValues::address_values();
				break;
			case 'checkbox':
				$fields += FieldValues::checkbox_values();
				break;
			case 'consent':
				$fields += FieldValues::consent_value();
				break;
			case 'fileupload':
				// Deprecate values field.
				$values                                = FieldValues::values();
				$values['values']['deprecationReason'] = __( 'Use `fileUploadValues` instead.', 'wp-graphql-gravity-forms' );
				$fields                               += $values;

				$fields += FieldValues::file_upload_values();
				break;
			case 'list':
				$fields += FieldValues::list_values();
				break;
			case 'name':
				$fields += FieldValues::name_values();
				break;
			case 'post_image':
				$fields += FieldValues::image_values();
				break;
			case 'singleproduct':
			case 'product':
				$fields += FieldValues::product_values();
				break;
			case 'time':
				$fields += FieldValues::time_values();
				break;
			case 'multiselect':
				$fields += FieldValues::values();
				break;
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
			default:
				break;
		}

		/**
		 * Filter to modify the Form Field value GraphQL fields.
		 *
		 * @deprecated 0.12.0 Use `graphql_gf_form_field_value_fields` instead.
		 *
		 * @param array $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param \GF_Field $field The Gravity Forms Field object.
		 */
		$fields = apply_filters_deprecated( 'graphql_gf_form_field_value_properties', [ $fields, $field ], '0.12.0', 'graphql_gf_form_field_value_fields' );

		/**
		 * Filter to modify the Form Field Value GraphQL fields.
		 *
		 * @param array    $fields An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param \GF_Field $field The Gravity Forms Field object.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_value_fields', $fields, $field );

		return apply_filters( 'graphql_gf_form_field_value_fields_' . $field->graphql_single_name, $fields, $field );
	}

	/**
	 * Calls the actions to register the choices and inputs to the type.
	 *
	 * @param \GF_Field $field The Gravity Forms field object.
	 * @param array     $field_settings The Gravity Forms field settings.
	 * @param bool      $as_interface   Whether to register the choices and inputs as an interface.
	 */
	protected static function maybe_register_choices_and_inputs( GF_Field $field, array $field_settings, bool $as_interface = false ): void {
		/**
		 * Filters the Gravity Forms field settings that should have a `choices` GraphQL Field.
		 *
		 * @param array    $settings_with_choices The field settings that should have a `choices` GraphQL Field.
		 * @param array    $field_settings The Gravity Forms field settings.
		 * @param \GF_Field $field The Gravity Forms field object.
		 * @param bool     $as_interface Whether to register the choice as an interface.
		 */
		$settings_with_choices = apply_filters(
			'graphql_gf_form_field_settings_with_choices',
			[
				FieldWithChoices::$field_setting,
				FieldWithColumns::$field_setting,
				FieldWithName::$field_setting,
				FieldWithSelectAllChoices::$field_setting,
				FieldWithOtherChoice::$field_setting,
			],
			$field_settings,
			$field,
			$as_interface
		);

		$has_choices = array_intersect( $field_settings, $settings_with_choices );


		if ( ! empty( $has_choices ) ) {
			FieldChoiceRegistry::register( $field, $field_settings, $as_interface );
		}

		/**
		 * Filters the Gravity Forms field settings that should have an `inputs` GraphQL Field.
		 *
		 * @param array    $settings_with_inputs The field settings that should have an `inputs` GraphQL Field.
		 * @param array    $field_settings The Gravity Forms field settings.
		 * @param \GF_Field $field The Gravity Forms field object.
		 * @param bool     $as_interface Whether to register the inputs as an interface.
		 */
		$settings_with_inputs = apply_filters(
			'graphql_gf_form_field_settings_with_inputs',
			[
				FieldWithAddress::$field_setting,
				FieldWithDateFormat::$field_setting,
				FieldWithEmailConfirmation::$field_setting,
				FieldWithName::$field_setting,
				FieldWithPassword::$field_setting,
				FieldWithSingleProductInputs::$field_setting,
				FieldWithTimeFormat::$field_setting,
				FieldWithSelectAllChoices::$field_setting,
			],
			$field_settings,
			$field,
			$as_interface
		);

		$has_inputs = array_intersect( $field_settings, $settings_with_inputs );

		if ( ! empty( $has_inputs ) ) {
			FieldInputRegistry::register( $field, $field_settings, $as_interface );
		}
	}
}
