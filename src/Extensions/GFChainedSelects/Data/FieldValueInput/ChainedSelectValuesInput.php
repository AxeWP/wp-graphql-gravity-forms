<?php
/**
 * Manipulates input data for ChainedSelect field values.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Data\FieldValueInput;

use WPGraphQL\GF\Data\FieldValueInput\CheckboxValuesInput;

/**
 * Class - ChainedSelectValuesInput
 */
class ChainedSelectValuesInput extends CheckboxValuesInput {
	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'chainedSelectValues';
	}
}
