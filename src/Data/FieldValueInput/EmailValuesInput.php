<?php
/**
 * Manipulates input data for Email field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - EmailValuesInput
 */
class EmailValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array{value?:string,confirmationValue?:string}
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 *
	 * @var array<int,?string>
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'emailValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array<int,?string>
	 */
	protected function prepare_value() {
		$value = $this->args;

		$values_to_save   = [];
		$values_to_save[] = $value['value'] ?? null;

		if ( ! empty( $this->field->emailConfirmEnabled ) ) {
			$values_to_save[] = $value['confirmationValue'] ?? null;
		}

		return $values_to_save;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		// Normal email fields are stored under their field id.
		$field_values[ $this->field->id ] = $this->value[0];

		// Confirmation values are stored in a subfield.
		if ( isset( $this->value[1] ) && isset( $this->field->inputs[1]['id'] ) ) {
			$field_values[ $this->field->inputs[1]['id'] ] = $this->value[0];
		}
	}
}
