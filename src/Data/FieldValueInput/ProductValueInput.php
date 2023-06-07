<?php
/**
 * Manipulates input data for Product field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFCommon;
use GraphQL\Error\UserError;

/**
 * Class - ProductValueInput
 */
class ProductValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'productValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected function is_valid_input_type(): bool {
		$is_valid = false;

		// Calculation fields need a quantity and price.
		if ( 'calculation' === $this->field->get_input_type() && ( ! isset( $this->input_args[ $this->field_name ]['quantity'] ) || ! isset( $this->input_args[ $this->field_name ]['price'] ) ) ) {
			return $is_valid;
		}

		// Fields using `productValues` need a quantity.
		if ( isset( $this->input_args[ $this->field_name ] ) && isset( $this->input_args[ $this->field_name ]['quantity'] ) ) {
			return true;
		}

		// Fields with a separate quantity are valid, and filled out automatically.
		if ( ! empty( $this->field->disableQuantity ) ) {
			return true;
		}

		// Fields using `value` must use it as the quantity.
		if ( isset( $this->input_args['value'] ) ) {
			if ( ! floatval( $this->input_args['value'] ) ) {
				throw new UserError(
					sprintf(
						// translators: field ID, input key.
						__( 'Mutation not processed. Field %1$s requires the use of `%2$s` as a valid quantity.', 'wp-graphql-gravity-forms' ),
						$this->field->id,
						'value',
					)
				);
			}

			return true;
		}

		return $is_valid;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array
	 */
	public function get_args() {
		return $this->input_args[ $this->field_name ] ?? [
			'quantity' => $this->input_args['value'],
			'price'    => null,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value() {
		$field = $this->field;

		$prepared_value = [
			(string) $field->inputs[0]['id'] => $field->label,
			(string) $field->inputs[1]['id'] => empty( $this->args['price'] ) ? $field->basePrice : GFCommon::format_number( $this->args['price'], 'currency' ),
		];

		// Only add quantity if the field supports it.
		if ( empty( $field->disableQuantity ) ) {
			$prepared_value[ (string) $field->inputs[2]['id'] ] = (float) $this->args['quantity'];
		}

		return $prepared_value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		$field_values += $this->value;
	}
}
