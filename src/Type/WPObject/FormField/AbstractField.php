<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

/**
 * Class - AbstractField
 */
abstract class AbstractField extends AbstractFormField {
	/**
	 * Constructor used to deprecate the class.
	 *
	 * @since 0.7.0
	 */
	public function __construct() {
		_deprecated_function( 'AbstractField::__contstruct', '0.7.0', 'AbstractFormField::__construct' );
	}
}
