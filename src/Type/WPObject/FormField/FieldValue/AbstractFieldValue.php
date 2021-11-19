<?php
/**
 * Abstract FieldValue Type
 *
 * @package WPGraphQL\GF\Type\WPObject\FormFieldValue
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - AbstractFieldValue
 */
abstract class AbstractFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.7.0
	 */
	public function __construct() {
		_deprecated_function( 'AbstractFieldValue::__construct', '0.7.0', 'AbstractObject::__construct' );
	}
}
