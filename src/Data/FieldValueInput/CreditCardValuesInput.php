<?php
/**
 * Manipulates input data for Credit Card field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

/**
 * Class - CreditCardValuesInput
 */
class CreditCardValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array{cardNumber?:string,expirationMonth?:string,expirationYear?:string,securityCode?:string,cardholderName?:string,cardType?:string}
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 *
	 * @var array<string,?string>
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'creditCardValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array<int|string,?string>
	 */
	protected function prepare_value() {
		$value = $this->args;

		return [
			$this->field->inputs[0]['id'] => $value['cardNumber'] ?? null,
			$this->field->inputs[1]['id'] => $value['expirationMonth'] ?? null,
			$this->field->inputs[2]['id'] => $value['expirationYear'] ?? null,
			$this->field->inputs[3]['id'] => $value['securityCode'] ?? null,
			$this->field->inputs[4]['id'] ?? $this->field->id . '.4' => $value['cardholderName'] ?? null,
			$this->field->inputs[5]['id'] ?? $this->field->id . '.5' => $value['cardType'] ?? null,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		$field_values += $this->value;
	}
}
