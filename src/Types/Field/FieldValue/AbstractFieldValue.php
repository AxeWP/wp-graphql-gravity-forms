<?php
/**
 * Abstract FieldValue Type
 *
 * @package WPGraphQLGravityForms\Types\FieldValue
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\AbstractObject;

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
