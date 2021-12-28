<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - ValuesInput
 */
class ValuesInput extends AbstractFieldValueInput {

	/**
	 * {@inheritDoc}
	 */
	public function get_value_key() : string {
		return 'values';
	}
}