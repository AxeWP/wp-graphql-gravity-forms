<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - ValueInput
 */
class ValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name() : string {
		return 'value';
	}
}
