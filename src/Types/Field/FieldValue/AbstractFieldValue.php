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
	 * @since 0.6.4
	 */
	public function __construct() {
		_deprecated_function( __FUNCTION__, '0.6.4' );
	}
}
