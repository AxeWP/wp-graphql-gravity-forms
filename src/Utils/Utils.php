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

use WPGraphQL\GF\TypeRegistry;
use WPGraphQL\GF\Type\WPObject\FormField\AbstractFormField;

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
	 * @return array
	 * @since 0.2.0
	 */
	public static function deprecate_property( array $property, string $reason ) : array {
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
	 * @return string
	 */
	public static function to_snake_case( $string ) : string {
		return strtolower( (string) preg_replace( [ '/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/' ], '$1_$2', $string ) );
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::truncate() method.
	 *
	 * @param string $str Original string.
	 * @param int    $length The maximum length of the string.
	 *
	 * @return string The string, possibly truncated.
	 */
	public static function truncate( string $str, int $length ) : string {
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
			$value_array = [ $value ];
		}

		return $value_array;
	}

	/**
	 * Preprocessing for apply filters.
	 *
	 * Allows additional filters based on the object type to be defined easliy.
	 *
	 * @param array $filters .
	 * @param mixed $value .
	 *
	 * @return mixed
	 */
	public static function apply_filters( $filters, $value ) {
		$args = func_get_args();

		$modifiers = array_splice( $filters, 1, count( $filters ) );
		$filter    = $filters[0];
		$args      = array_slice( $args, 2 );

		// Add an empty modifier so the base filter will be applied as well.
		array_unshift( $modifiers, '' );

		$args = array_pad( $args, 10, null );

			// Apply modified versions of filter.
		foreach ( $modifiers as $modifier ) {
			$modifier = empty( $modifier ) ? '' : sprintf( '_%s', $modifier );
			$filter  .= $modifier;
			$value    = apply_filters( $filter, $value, ...$args ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		}

		return $value;
	}

	/**
	 * Returns whether WPGraphQL Upload is enabled.
	 *
	 * @return boolean
	 */
	public static function is_graphql_upload_enabled() : bool {
		return class_exists( 'WPGraphQL\Upload\Type\Upload' );
	}

	/**
	 * Gets an array of all GF type names paired with their GraphQL type names.
	 *
	 * E.g. `[ 'text' => 'TextField' ]`.
	 */
	public static function get_registered_form_field_types() : array {
		$fields = array_filter( TypeRegistry::get_registered_types(), fn( $class ) => is_a( $class, AbstractFormField::class, true ) );

		$types = [];
		foreach ( $fields as $field ) {
			$types[ $field::$gf_type ] = $field::$type;
		}

		return $types;
	}
}
