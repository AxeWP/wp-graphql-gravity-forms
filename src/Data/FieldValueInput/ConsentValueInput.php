<?php
/**
 * Manipulates input data for Consent field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFFormsModel;

/**
 * Class - ConsentValueInput
 */
class ConsentValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var string
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
		return 'value';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value() {
		$field = $this->field;

		return [
			$field->inputs[0]['id'] => (bool) $this->args,
			$field->inputs[1]['id'] => $field->checkboxLabel ?? null,
			$field->inputs[2]['id'] => GFFormsModel::get_latest_form_revisions_id( $this->form['id'] ),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		$field_values += $this->value;
	}
}
