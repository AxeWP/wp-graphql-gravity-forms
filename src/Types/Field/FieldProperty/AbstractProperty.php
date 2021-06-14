<?php
/**
 * Abstract property type.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - AbstractProperty
 */
abstract class AbstractProperty extends AbstractObject {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.6.4
	 */
	public function __construct() {
		_deprecated_function( 'AbstractProperty::__construct', '0.6.4', 'AbstractObject::__construct' );
	}
}
