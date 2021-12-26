<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - EmailValuesInput
 */
class EmailValuesInput extends AbstractFieldValueInput {
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
		return 'emailValues';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value() {
		$value = $this->input_value;

		$values_to_save   = [];
		$values_to_save[] = $value['value'] ?? null;

		if ( $this->field->emailConfirmEnabled ) {
			$values_to_save[] = $value['confirmationValue'] ?? null;
		}

		return $values_to_save;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		// Normal email fields are stored under their field id.
		$field_values[ $this->field->id ] = $this->value[0];

		// Confirmation values are stored in a subfield.
		if ( isset( $this->value[1] ) ) {
			$field_values[ $this->field->inputs[1]['id'] ] = $this->value[0];
		}
	}
}
