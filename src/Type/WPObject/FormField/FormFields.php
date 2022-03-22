<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use GF_Field;
use GF_Fields;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\PropertyMapper;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\FieldValues;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldDataPolicy;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormFields
 */
class FormFields implements Registrable {
	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var GF_Field
	 */
	public static $field;

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
		if ( null === $type_registry ) {
			return;
		}

		$fields = GF_Fields::get_all();

		foreach ( $fields as $field ) {
			if ( ! in_array(
				$field->type,
				array_merge(
					Utils::get_ignored_gf_field_types(),
					[
						// These fields are registered as child types of a field interface, and should always be skipped.
						'calculation',
						'hiddenproduct',
						'singleproduct',
						'singleshipping',
					]
				),
				true
			) ) {
				self::register_gf_field( $field, $type_registry );
			}
		}
	}

	/**
	 * Programmatically registers the Gravity Forms field to WPGraphQL
	 *
	 * @param GF_Field     $field .
	 * @param TypeRegistry $type_registry .
	 */
	public static function register_gf_field( GF_Field $field, TypeRegistry $type_registry ) : void {
		$name     = Utils::get_safe_form_field_type_name( $field->type ) . 'Field';
		$settings = self::get_field_settings( $field );

		$possible_types = Utils::get_possible_form_field_child_types( $field->type );

		if ( ! empty( $possible_types ) ) {
			$shared_settings = [];

			foreach ( $possible_types as $gf_type => $type ) {
				$inherited_field = GF_Fields::get( $gf_type );

				$shared_settings[ $gf_type ] = $inherited_field->get_form_editor_field_settings();
			}

			// Add fields to interface.
			// Flip the array and compare by keys for performance.
			$interface_settings = array_keys( array_intersect_key( ... array_map( 'array_flip', array_values( $shared_settings ) ) ) );

			$interface_settings = array_merge( $settings, $interface_settings );

			register_graphql_interface_type(
				$name,
				[
					'description'     => self::get_description( $field->type ),
					'eagerlyLoadType' => self::$should_load_eagerly,
					'interfaces'      => [ FormField::$type ],
					'fields'          => self::map_settings_to_field_properties( $field, $interface_settings ),
					'resolveType'     => function ( $value ) use ( $type_registry, $possible_types ) {
						if ( isset( $possible_types[ $value->inputType ] ) ) {
							return $type_registry->get_type( $possible_types[ $value->inputType ] );
						}

						throw new UserError(
							sprintf(
							/* translators: %s: GF field type */
								__( 'The "%1$1s" Gravity Forms field does not yet support the %2$2s input type.', 'wp-graphql-gravity-forms' ),
								$value->type,
								$value->inputType
							)
						);
					},
				]
			);

			foreach ( $possible_types as $gf_type => $type ) {
				$field->inputType = $gf_type;

				register_graphql_object_type(
					$type,
					[
						'description'     => self::get_description( (string) $gf_type, $field->type ),
						'interfaces'      => [ $name ],
						'fields'          => self::map_settings_to_field_properties( $field, array_diff( $shared_settings[ $gf_type ], $interface_settings ) ),
						'eagerlyLoadType' => self::$should_load_eagerly,
					]
				);
			}

			return;
		}

		// If it's a regular GF Field, register it normally.
		register_graphql_object_type(
			$name,
			[
				'description'     => self::get_description( $field->type ),
				'interfaces'      => [ FormField::$type ],
				'fields'          => self::map_settings_to_field_properties( $field, $settings ),
				'eagerlyLoadType' => self::$should_load_eagerly,
			]
		);
	}



	/**
	 * {@inheritDoc}
	 */
	public static function get_description( string $type, string $parent = null ) : string {
		$fieldname = $type . $parent ? ' ' . $parent : '';
		// translators: GF field type and possibly parent type.
		return sprintf( __( 'A Gravity Forms %s field.', 'wp-graphql-gravity-forms' ), $fieldname );
	}

	/**
	 * Gets the registered field settings, including those of input types.
	 *
	 * @param GF_Field $field .
	 */
	public static function get_field_settings( GF_Field $field ) : array {
		$settings           = $field->get_form_editor_field_settings();
		$inherited_settings = [];

		$input_type = $field->get_input_type();

		if ( $input_type !== $field->type ) {
			$inherited_field = GF_Fields::get( $input_type );

			$inherited_settings = $inherited_field->get_form_editor_field_settings();
		}

		return array_merge( $settings, $inherited_settings );
	}

	/**
	 * Grabs the GraphQL FormField property for for the corresponding GF field setting.
	 *
	 * @param GF_Field $field .
	 * @param array    $settings .
	 */
	private static function map_settings_to_field_properties( GF_Field $field, array $settings ) : array {
		$properties = [];
		$settings   = str_replace( '-', '_', $settings );

		foreach ( $settings as $setting ) {
			// Skip properties registered elsewhere.
			if ( in_array(
				$setting,
				[
					'conditional_logic_nextbutton_setting',
					'default_input_values_setting',
					'input_placeholders_setting',
					'name_prefix_choices_setting',
					'post_author_setting',
					'post_category_field_type_setting',
					'post_category_setting',
					'post_content_template_setting',
					'post_custom_field_type_setting',
					'post_format_setting',
					'post_status_setting',
					'post_tag_type_setting',
					'post_title_template_setting',
					'quantity_field_type_setting',
					'sub_labels_setting',
					'visibility_setting',
				],
				true
			) ) {
				continue;
			}

			if ( method_exists( PropertyMapper::class, $setting ) ) {
				PropertyMapper::$setting( $field, $properties );
			}

			// Add Personal data properties to relevant fields.
			$properties = self::map_personal_data_properties( $field, $properties );

			/**
			 * Filter to modify the Form Field GraphQL fields based on GF_Field::form_editor_field_settings().
			 *
			 * @param array $properties An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
			 * @param string $setting The `form_editor_field_settings()` key.
			 * @param GF_Field $field The Gravity Forms Field object.
			 */
			$properties = apply_filters( 'graphql_gf_form_field_setting_properties', $properties, $setting, $field );
		}

		// Add field values to properties.
		$properties = self::map_field_values_to_properties( $field, $properties );

		return $properties;
	}

	/**
	 * Adds the Gravity Forms field-specific entry value.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties .
	 */
	public static function map_field_values_to_properties( GF_Field $field, array $properties ) : array {
		if ( ! empty( $field->displayOnly ) || in_array( $field->type, [ 'html', 'page', 'section' ], true ) ) {
			return $properties;
		}

		$properties += FieldValues::value();

		$input_type = $field->get_input_type();

		switch ( $input_type ) {
			case 'address':
				$properties += FieldValues::address_values();
				break;
			case 'checkbox':
				$properties += FieldValues::checkbox_values();
				break;
			case 'consent':
				$properties += FieldValues::consent_value();
				break;
			case 'fileupload':
				// Deprecate values field.
				$values                                = FieldValues::values();
				$values['values']['deprecationReason'] = __( 'Use `fileUploadValues` instead.', 'wp-graphql-gravity-forms' );
				$properties                           += $values;

				$properties += FieldValues::file_upload_values();
				break;
			case 'list':
				$properties += FieldValues::list_values();
				break;
			case 'name':
				$properties += FieldValues::name_values();
				break;
			case 'post_image':
				$properties += FieldValues::image_values();
				break;
			case 'time':
				$properties += FieldValues::time_values();
				break;
			case 'multiselect':
				$properties += FieldValues::values();
				break;
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
			default:
				break;
		}

		/**
		 * Filter to modify the Form Field value GraphQL field.
		 *
		 * @param array $properties An array of GraphQL field configs. See https://www.wpgraphql.com/functions/register_graphql_fields/
		 * @param GF_Field $field The Gravity Forms Field object.
		 */
		$properties = apply_filters( 'graphql_gf_form_field_value_properties', $properties, $field );

		return $properties;
	}

	/**
	 * Adds the Gravity Forms field-specific personal data policies.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties .
	 */
	public static function map_personal_data_properties( GF_Field $field, array $properties ) : array {
		if ( ! empty( $field->displayOnly ) || in_array( $field->type, [ 'html', 'page', 'section', 'captcha' ], true ) ) {
			return $properties;
		}

		$properties['personalData'] = [
			'type'        => FormFieldDataPolicy::$type,
			'description' => __( 'The form field-specifc policies for exporting and erasing personal data.', 'wp-graphql-gravity-forms' ),
			'resolve'     => function( GF_Field $source, array $args, AppContext $context ) {
				if ( empty( $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] ) ) {
					return null;
				}

				return [
					'id'                    => $source->id ?? null,
					'isIdentificationField' => isset( $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] ) && $context->gfForm->personalData['dataPolicies']['identificationFieldDatabaseId'] === $source->id,
					'shouldErase'           => ! empty( $source->personalDataErase ),
					'shouldExport'          => ! empty( $source->personalDataExport ),
				];
			},
		];

		return $properties;
	}
}
