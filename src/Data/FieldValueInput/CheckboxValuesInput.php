<?php
/**
 * Manipulates input data for Checkbox field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GraphQL\Error\UserError;

/**
 * Class - CheckboxValuesInput
 */
class CheckboxValuesInput extends AbstractFieldValueInput {
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
	protected function get_field_name() : string {
		return 'checkboxValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	protected function prepare_value() {
		$values_to_save = array_reduce(
			$this->field->inputs,
			function( array $values_to_save, array $input ) : array {
				$values_to_save[ $input['id'] ] = ''; // Initialize all inputs to an empty string.
				return $values_to_save;
			},
			[]
		);

		foreach ( $this->args as $single_value ) {
			// Make sure the input ID passed in exists.
			if ( ! isset( $values_to_save[ (string) $single_value['inputId'] ] ) ) {
				throw new UserError(
					sprintf(
						// translators: field ID, input ID.
						__( 'Field %1$s has no input with an id of %2$s.', 'wp-graphql-gravity-forms' ),
						$this->field->id,
						$single_value['inputId']
					)
				);
			}

			// Overwrite initial empty string with the value passed in.
			$values_to_save[ (string) $single_value['inputId'] ] = $single_value['value'];
		}

		return $values_to_save;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		$field_values += $this->value;
	}
}
