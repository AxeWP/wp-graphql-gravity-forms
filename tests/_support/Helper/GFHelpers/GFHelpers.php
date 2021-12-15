<?php
/**
 * Abstract class - GFHelpers.
 *
 * @package Helper\GFHelpers
 */

namespace Helper\GFHelpers;

use Dummy;

/**
 * Abstract Class - GFHelpers.
 */
abstract class GFHelpers {
	/**
	 * Provides access to dummy functions.
	 *
	 * @var Dummy
	 */
	public $dummy;

	/**
	 * The list of field keys.
	 *
	 * @var array
	 */
	public $keys;
	/**
	 * The generated list of values.
	 *
	 * @var array
	 */
	public $values;

	/**
	 * Constructor
	 *
	 * @param array $keys default properties to include.
	 */
	public function __construct( array $keys ) {
		require_once __DIR__ . '/inc/Dummy.php';
		$this->dummy = new Dummy();

		$this->keys   = $this->get_keys( $keys );
		$this->values = $this->getAll( $keys );
	}

	/**
	 * Flattens key => value pairs to grab all the keys.
	 *
	 * @param array $keys
	 */
	public function get_keys( array $keys ) {
		$return_values = [];

		foreach ( $keys as $key ) {
			if ( is_array( $key ) ) {
				$return_values[] = array_key_first( $key );
				continue;
			}
			$return_values[] = $key;
		}
		return $return_values;
	}

	/**
	 * Gets the key => value pair for the given key name.
	 *
	 * @param string $key
	 * @param [type] $value
	 */
	public function get( string $key, $value = null ) {
		return [ $key => call_user_func( [ $this, $key ], $value ) ];
	}

	/**
	 * Gets all key => value pairs for the defined keys.
	 *
	 * @param array $keys
	 */
	public function getAll( array $keys ) {
		$return_values = [];

		foreach ( $keys as $key ) {
			if ( 'formId' === $key ) {
				continue;
			}

			if ( is_array( $key ) ) {
				$k = array_key_first( $key );

				if ( 'inputs' === $k && isset( $key['inputs']['fieldId'] ) ) {
					$new_value      = [ $k => $this->getFieldInputs( $key[ $k ]['fieldId'], $key[ $k ]['count'], $key[ $k ]['keys'] ) ];
					$return_values += $new_value;
					continue;
				}
				$return_values += $this->get( $k, $key[ $k ] );
				continue;
			}

			$return_values += $this->get( $key );
		}

		return $return_values;
	}

	public function getEnumKey( string $key ) {
		$string = null;
 
		switch( $key ){
			case 'addressType':
				$string = 'AddressFieldTypeEnum';
				break;
			case 'country':
			case 'defaultCountry':
				$string = 'AddressFieldCountryEnum';
				break;
			case 'badgePosition':
				$string = 'CaptchaFieldBadgePosition';
				break;
			case 'captchaTheme':
				$string = 'CaptchaFieldThemeEnum';
				break;
			case 'captchaType':
				$string = 'CaptchaFieldTypeEnum';
				break;
			case 'chainedSelectsAlignment':
				$string = 'ChainedSelectFieldAlignmentEnum';
				break;
			case 'dateType':
				$string = 'DateFieldTypeEnum';
				break;
			case 'dateFormat':
				$string = 'DateFieldFormatEnum';
				break;
			case 'gquizFieldType':
				$string = 'QuizFieldTypeEnum';
				break;
			case 'inputType':
				$string = 'FormFieldTypeEnum';
				break;
			case 'numberFormat':
				$string = 'NumberFieldFormatEnum';
				break;
			case 'simpleCaptchaSize':
				$string = 'FormFieldSizeEnum';
				break;
			case 'subLabelPlacement':
				$string = 'FormFieldLabelPlacementEnum';
				break;
			// Prepend FormField
			case 'calendarIconType':
			case 'descriptionPlacement':
			case 'labelPlacement':
			case 'requiredIndicator':
			case 'size':
			case 'type':
			case 'visibility':
				$string = 'FormField' . ucfirst( $key ) . 'Enum';
				break;
		}
		return $string;
	}

	/**
	 * Gets the actual object values for the provided key.
	 *
	 * @param string $key .
	 * @param mixed  $object .
	 */
	public function getActualValue( string $key, $object ) {
		switch ( $key ) {
			case 'copyValuesOptionDefault':
			case 'displayOnly':
			case 'enableCopyValuesOption':
			case 'enablePasswordInput':
				$value = (bool) $object->$key;
				break;
			case 'inputs':
				if ( ! empty( $object->$key ) ) {
					foreach ( $object->$key as $k => $val ) {
						$object->$key[ $k ]['id'] = (float) $object->$key[ $k ]['id'];
					}
				}
				$value = $object->$key;
				break;
			case 'maxLength':
				$value = (int) $object->$key;
				break;
			default:
				// Handle Enums.
				$string = $this->getEnumKey( $key );
				if( null !== $string ){
					$value = $this->get_enum_for_value( $string, $object->$key );
					break;
				}

				$value = isset( $object->$key ) ? $object->$key : null;
				break;
		}
		$return = [ $key => $value ];
		return $return;
	}

	/**
	 * Gets all actual object values.
	 *
	 * @param mixed $object
	 */
	public function getAllActualValues( $object, array $exclude = null ) {
		$return_values = [];
		foreach ( $this->keys as $key ) {
			if ( ! empty( $exclude ) && in_array( $key, $exclude, true ) ) {
				continue;
			}
			$return_values += $this->getActualValue( $key, $object );
		}
		return $return_values;
	}

	/**
	 * Converts a string value to its Enum equivalent
	 *
	 * @param string      $enumName Name of the Enum registered in GraphQL.
	 * @param string|null $value .
	 * @return string|null
	 */
	public function get_enum_for_value( string $enumName, $value ) {
		if ( null === $value ) {
			return null;
		}

		$typeRegistry = \WPGraphQL::get_type_registry();
		return $typeRegistry->get_type( $enumName )->serialize( $value );
	}

	public function get_field_values( $values ) {
		if ( is_array( $values ) ) {
			$values = [ $values ];
		}

		return array_map(
			function( $key, $value ) {
				return [ 'input_' . $key => $value ];
			},
			array_keys( $values ),
			$values
		);
	}

	public function getFieldInputs( int $fieldId, int $count, array $keys ) {
		$return_values = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$input_keys          = array_merge(
				$keys,
				[ [ 'id' => (string) $fieldId . '.' . ( $i + 1 ) ] ]
			);
			$return_values[ $i ] = $this->getAll( $input_keys );
		}
		return $return_values;
	}
}
