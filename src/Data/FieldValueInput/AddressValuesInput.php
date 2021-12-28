<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - AddressValuesInput
 */
class AddressValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	protected $input_value;

	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 */
	public function get_value_key() : string {
		return 'addressValues';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value() {
		$value = $this->input_value;

		return [
			$this->field->inputs[0]['id'] => $value['street'] ?? null,
			$this->field->inputs[1]['id'] => $value['lineTwo'] ?? null,
			$this->field->inputs[2]['id'] => $value['city'] ?? null,
			$this->field->inputs[3]['id'] => $value['state'] ?? null,
			$this->field->inputs[4]['id'] => $value['zip'] ?? null,
			$this->field->inputs[5]['id'] => $value['country'] ?? null,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		$field_values += $this->value;
	}
}