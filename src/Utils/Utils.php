<?php
/**
 * Utils
 *
 * Common utility functions
 *
 * @package WPGraphQL\GF\Utils
 * @since 0.2.0
 */

declare( strict_types = 1 );

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
	 * Converts a string to PascalCase.
	 *
	 * @since 0.10.0
	 *
	 * @param string $s the original string.
	 */
	public static function get_safe_form_field_type_name( $s ): string {
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

		return str_replace( ' ', '', ucwords( str_replace( array_keys( $fields_to_map ), array_values( $fields_to_map ), $s ) ) );
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
	 * @return array<mixed>|false
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
	 *
	 * @return array<string,string>
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
	 *
	 * @return array<string,string>
	 */
	public static function get_registered_entry_types(): array {
		$types = [
			EntriesLoader::$name      => SubmittedEntry::$type,
			DraftEntriesLoader::$name => DraftEntry::$type,
		];

		/**
		 * Filter for modifying the Gravity Forms Entry types supported by WPGraphQL.
		 *
		 * @param array<string,string> $entry_types An array of Data Loader names => GraphQL Types.
		 */
		return apply_filters( 'graphql_gf_registered_entry_types', $types );
	}

	/**
	 * Returns an array of possible form field input types for GraphQL object generation.
	 *
	 * @param string $type The current GF field type.
	 *
	 * @return array<string,string>
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
		 * @param array<string,string> $child_types An array of GF_Field::$type => GraphQL type names.
		 * @param string               $field_type  The 'parent' GF_Field type.
		 */
		return apply_filters( 'graphql_gf_form_field_child_types', $child_types, $type );
	}

	/**
	 * Gets a filterable list of Gravity Forms field types that should be disabled for this instance.
	 *
	 * @return string[]
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
		 * @param string[] $ignored_fields An array of GF_Field $type names to be ignored by WPGraphQL.
		 */
		$ignored_fields = apply_filters( 'graphql_gf_ignored_field_types', $ignored_fields );

		return $ignored_fields;
	}

	/**
	 * Returns an array of Gravity Forms field settings to ignore.
	 *
	 * @return string[]
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
			throw new UserError( esc_html__( 'The ID passed is not a valid Global ID.', 'wp-graphql-gravity-forms' ) );
		}

		if ( $type !== $id_parts['type'] ) {
			throw new UserError( esc_html__( 'The ID passed is not a valid Global ID for this type.', 'wp-graphql-gravity-forms' ) );
		}

		return absint( $id_parts['id'] );
	}

	/**
	 * Overloads the field type of an existing GraphQL field.
	 *
	 * This is necessary because register_graphql_field() doesn't have a way to check inheritance.
	 *
	 * @see https://github.com/wp-graphql/wp-graphql/issues/3096
	 *
	 * @param string                      $object_type The WPGraphQL object type name where the field is located.
	 * @param string                      $field_name  The field name to overload.
	 * @param string|array<string|string> $new_type_name The new GraphQL type name to use.
	 */
	public static function overload_graphql_field_type( string $object_type, string $field_name, $new_type_name ): void {
		add_filter(
			'graphql_' . $object_type . '_fields',
			static function ( array $fields ) use ( $field_name, $new_type_name ) {
				if ( isset( $fields[ $field_name ] ) ) {
					$fields[ $field_name ]['type'] = $new_type_name;
				}

				return $fields;
			},
			10,
			1
		);
	}
}
