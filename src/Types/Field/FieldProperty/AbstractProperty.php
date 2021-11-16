<?php
/**
 * Abstract property type.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Types\Field
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;

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
