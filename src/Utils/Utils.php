<?php
/**
 * Utils
 *
 * Common utility functions
 *
 * @package WPGraphQLGravityForms\Utils
 * @since 0.2.0
 */

namespace WPGraphQLGravityForms\Utils;

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
}
