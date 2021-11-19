<?php
/**
 * Abstract property type.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - AbstractProperty
 */
abstract class AbstractProperty extends AbstractObject {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.7.0
	 */
	public function __construct() {
		_deprecated_function( 'AbstractProperty::__construct', '0.7.0', 'AbstractObject::__construct' );
	}
}
