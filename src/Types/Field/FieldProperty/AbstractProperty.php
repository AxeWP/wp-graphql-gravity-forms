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

use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - AbstractProperty
 */
abstract class AbstractProperty extends AbstractType {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.6.4
	 */
	public function __construct() {
		_deprecated_function( __FUNCTION__, '0.6.4' );
	}
}
