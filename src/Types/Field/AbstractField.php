<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Field;

/**
 * Class - AbstractField
 */
abstract class AbstractField extends AbstractFormField {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.6.4
	 */
	public function __construct() {
		_deprecated_function( 'AbstractField::__contstruct', '0.6.4', 'AbstractFormField::__construct' );
	}
}
