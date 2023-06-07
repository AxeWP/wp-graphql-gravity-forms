<?php
/**
 * Utils
 *
 * Common utility functions
 *
 * @package WPGraphQL\GF\Utils
 * @since 0.2.0
 */

namespace WPGraphQL\GF\Utils;

use GF_Fields;
use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Type\WPObject\Entry\DraftEntry;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;

/**
 * Class - Utils
 */
class Utils {
	/**
	 * Adds deprecation reason to GraphQL field property.
	 *
	 * @param array  $property The field property to deprecate.
	 * @param string $reason The reason for the deprecation. Should be wrapped in __().
	 *
	 * @since 0.2.0
	 */
	public static function deprecate_property( array $property, string $reason ): array {
		$property_key = array_key_first( $property );

		// Add deprecation reason to property.
		if ( isset( $property_key ) ) {
			$property[ $property_key ]['deprecationReason'] = $reason;
		}

		return $property;
	}

	/**
	 * Converts a string to snake_case.
	 *
	 * @since 0.4.0
	 *
	 * @param string $string the original string.
	 */
	public static function to_snake_case( $string ): string {
		return strtolower( (string) preg_replace( [ '/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/' ], '$1_$2', $string ) );
	}

	/**
	 * Converts a string to PascalCase.
	 *
	 * @since 0.10.0
	 *
	 * @param string $string the original string.
	 */
	public static function get_safe_form_field_type_name( $string ): string {
		// Shim to map fields with existing PascalCase.
		$fields_to_map = [
			'chainedselect'     => 'ChainedSelect',
			'fileupload'        => 'FileUpload',
			'multiselect'       => 'MultiSelect',
			'post_custom_field' => 'PostCustom',
			'singleproduct'     => 'ProductSingle',
			'textarea'          => 'TextArea',
			// Regular mapping.
			'-'                 => ' ',
			'_'                 => ' ',
		];

		/**
		 * Filter to map the Gravity Forms Field type to a safe GraphQL type (in PascalCase ).
		 *
		 * @param array $fields_to_map An array of GF field types to GraphQL type names. E.g. ` 'fileupload' => 'FileUpload'`.
		 */
		$fields_to_map = apply_filters( 'graphql_gf_form_fields_name_map', $fields_to_map );

		return str_replace( ' ', '', ucwords( str_replace( array_keys( $fields_to_map ), array_values( $fields_to_map ), $string ) ) );
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::truncate() method.
	 *
	 * @param string $str Original string.
	 * @param int    $length The maximum length of the string.
	 *
	 * @return string The string, possibly truncated.
	 */
	public static function truncate( string $str, int $length ): string {
		if ( strlen( $str ) > $length ) {
			$str = substr( $str, 0, $length );
		}

		return $str;
	}

	/**
	 * Tries to decode json.
	 *
	 * @param mixed $value the value to try to decode.
	 *
	 * @since 0.7.0
	 *
	 * @return array|false
	 */
	public static function maybe_decode_json( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}

		if ( ! is_string( $value ) ) {
			return false;
		}

		$value_array = json_decode( $value, true );

		// If the value isnt JSON, then convert it to an array.
		if ( 0 !== json_last_error() ) {
			return [ $value ];
		}

		return $value_array;
	}

	/**
	 * Returns whether WPGraphQL Upload is enabled.
	 */
	public static function is_graphql_upload_enabled(): bool {
		return class_exists( 'WPGraphQL\Upload\Type\Upload' );
	}

	/**
	 * Gets an array of all GF type names paired with their GraphQL type names.
	 *
	 * E.g. `[ 'text' => 'TextField' ]`.
	 */
	public static function get_registered_form_field_types(): array {
		$types = [];

		$fields = GF_Fields::get_all();
		foreach ( $fields as $field ) {
			if ( ! in_array( $field->type, self::get_ignored_gf_field_types(), true ) ) {
				$types[ $field->type ] = self::get_safe_form_field_type_name( $field->type ) . 'Field';
			}
		}

		return $types;
	}

	/**
	 * Gets an array of GF entry types paired with their GraphQL type names.
	 *
	 * E.g. `[ 'draft_entry' => 'GfDraftEntry' ]`
	 */
	public static function get_registered_entry_types(): array {
		$types = [
			EntriesLoader::$name      => SubmittedEntry::$type,
			DraftEntriesLoader::$name => DraftEntry::$type,
		];

		/**
		 * Filter for modifying the Gravity Forms Entry types supported by WPGraphQL.
		 *
		 * @param array $entry_types An array of Data Loader names => GraphQL Types.
		 */
		return apply_filters( 'graphql_gf_registered_entry_types', $types );
	}

	/**
	 * Returns an array of possible form field input types for GraphQL object generation.
	 *
	 * @param string $type The current GF field type.
	 */
	public static function get_possible_form_field_child_types( string $type ): array {
		$prefix = self::get_safe_form_field_type_name( $type );

		switch ( $type ) {
			case 'post_category':
				$child_types = [
					'checkbox'    => $prefix . 'CheckboxField',
					'multiselect' => $prefix . 'MultiSelectField',
					'radio'       => $prefix . 'RadioField',
					'select'      => $prefix . 'SelectField',
				];
				break;
			case 'post_custom_field':
				$child_types = [
					'checkbox'    => $prefix . 'CheckboxField',
					'date'        => $prefix . 'DateField',
					'email'       => $prefix . 'EmailField',
					'fileupload'  => $prefix . 'FileuploadField',
					'hidden'      => $prefix . 'HiddenField',
					'list'        => $prefix . 'ListField',
					'multiselect' => $prefix . 'MultiSelectField',
					'number'      => $prefix . 'NumberField',
					'phone'       => $prefix . 'PhoneField',
					'radio'       => $prefix . 'RadioField',
					'select'      => $prefix . 'SelectField',
					'text'        => $prefix . 'TextField',
					'textarea'    => $prefix . 'TextAreaField',
					'time'        => $prefix . 'TimeField',
					'website'     => $prefix . 'WebsiteField',
				];
				break;
			case 'post_tags':
				$child_types = [
					'checkbox'    => $prefix . 'CheckboxField',
					'multiselect' => $prefix . 'MultiSelectField',
					'radio'       => $prefix . 'RadioField',
					'select'      => $prefix . 'SelectField',
					'text'        => $prefix . 'TextField',
				];
				break;
			case 'product':
				$child_types = [
					'calculation'   => $prefix . 'CalculationField',
					'hiddenproduct' => $prefix . 'HiddenField',
					'price'         => $prefix . 'PriceField',
					'radio'         => $prefix . 'RadioField',
					'select'        => $prefix . 'SelectField',
					'singleproduct' => $prefix . 'SingleField',
				];
				break;
			case 'shipping':
				$child_types = [
					'radio'          => $prefix . 'RadioField',
					'select'         => $prefix . 'SelectField',
					'singleshipping' => $prefix . 'SingleField',
				];
				break;
			case 'option':
				$child_types = [
					'checkbox' => $prefix . 'CheckboxField',
					'radio'    => $prefix . 'RadioField',
					'select'   => $prefix . 'SelectField',
				];
				break;
			case 'donation':
				$child_types = [
					'donation' => $prefix . 'DonationField',
					'radio'    => $prefix . 'RadioField',
					'select'   => $prefix . 'SelectField',
				];
				break;
			case 'quantity':
				$child_types = [
					'number' => $prefix . 'NumberField',
					'hidden' => $prefix . 'HiddenField',
					'select' => $prefix . 'SelectField',
				];
				break;
			default:
				$child_types = [];
				break;
		}

		/**
		 * Filter for altering the child types of a specific GF_Field.
		 *
		 * @param array $child_types An array of GF_Field::$type => GraphQL type names.
		 * @param string $field_type The 'parent' GF_Field type.
		 */
		return apply_filters( 'graphql_gf_form_field_child_types', $child_types, $type );
	}

	/**
	 * Gets a filterable list of Gravity Forms field types that should be disabled for this instance.
	 */
	public static function get_ignored_gf_field_types(): array {
		$ignored_fields = [
			'donation', // These fields are no longer supported by GF.
			'repeater', // This still in beta.
			'submit', // This is not technically a form field.
		];

		// These fields are experimental, and don't have unit testing in place.
		if ( ! defined( 'WPGRAPHQL_GF_EXPERIMENTAL_FIELDS' ) || false === WPGRAPHQL_GF_EXPERIMENTAL_FIELDS ) {
			$ignored_fields[] = 'creditcard';
		}

		/**
		 * Filters the list of ignored field types.
		 *
		 * Useful for adding/removing support for a specific Gravity Forms field.
		 *
		 * @param array $ignored_fields An array of GF_Field $type names to be ignored by WPGraphQL.
		 */
		$ignored_fields = apply_filters( 'graphql_gf_ignored_field_types', $ignored_fields );

		return $ignored_fields;
	}

	/**
	 * Returns an array of Gravity Forms field settings to ignore.
	 */
	public static function get_ignored_gf_settings(): array {
		return [
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
		];
	}

	/**
	 * Gets the entry databaseId from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @throws \GraphQL\Error\UserError .
	 *
	 * @since 0.12.2
	 */
	public static function get_entry_id_from_id( $id ): int {
		return self::get_database_id_from_id( $id, EntriesLoader::$name );
	}

	/**
	 * Gets the entry databaseId from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @throws \GraphQL\Error\UserError .
	 *
	 * @since 0.12.2
	 */
	public static function get_form_id_from_id( $id ): int {
		return self::get_database_id_from_id( $id, FormsLoader::$name );
	}

	/**
	 * Gets the databaseId from an indeterminate GraphQL ID, ensuring it's the correct type.
	 *
	 * @since 0.12.2
	 *
	 * @param int|string $id The provided ID.
	 * @param string     $type The expected dataloader type.
	 *
	 * @throws \GraphQL\Error\UserError If the ID is not a valid Global ID.
	 */
	protected static function get_database_id_from_id( $id, $type ): int {
		// If we already have the database ID, send it back as an integer.
		if ( is_numeric( $id ) ) {
			return absint( $id );
		}

		$id_parts = Relay::fromGlobalId( $id );

		if ( empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
			throw new UserError( __( 'The ID passed is not a valid Global ID.', 'wp-graphql-gravity-forms' ) );
		}

		if ( $type !== $id_parts['type'] ) {
			throw new UserError( __( 'The ID passed is not a valid Global ID for this type.', 'wp-graphql-gravity-forms' ) );
		}

		return absint( $id_parts['id'] );
	}
}
