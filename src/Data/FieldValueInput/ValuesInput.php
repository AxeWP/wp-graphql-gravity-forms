<?php
/**
 * Manipulates input data for array field values.
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
	protected function get_field_name(): string {
		return 'values';
	}
}
