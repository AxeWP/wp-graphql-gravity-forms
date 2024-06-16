<?php
/**
 * Manipulates input data for Radio field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFCommon;

/**
 * Class - RadioValueInput
 */
class RadioValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 *
	 * @var array<int|string,?string>
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
	 *
	 * @return array<int|string,?string>
	 */
	protected function prepare_value() {
		$value = $this->args;

		// Handle values with price.
		if ( ! empty( $this->field->enablePrice ) && false === strpos( $value, '|' ) ) {
			$value_key  = ! empty( $this->field->enablePrice ) || ! empty( $this->field->enableChoiceValue ) ? 'value' : 'text';
			$choice_key = array_search( $value, array_column( $this->field->choices, $value_key ), true );
			$choice     = $this->field->choices[ $choice_key ];
			$price      = rgempty( 'price', $choice ) ? 0 : GFCommon::to_number( rgar( $choice, 'price' ) );
			$value      = $value . '|' . $price;
		}

		if ( empty( $this->field->enableOtherChoice ) ) {
			return [
				$this->field->id => $value,
			];
		}

		$allowed_values = wp_list_pluck( $this->field->choices, 'value' );

		if ( ! in_array( $value, $allowed_values, true ) ) {
			$_POST[ $this->field->id . '_other' ] = $value;
			$_POST[ $this->field->id ]            = 'gf_other_choice';
			return [
				$this->field->id            => 'gf_other_choice',
				$this->field->id . '_other' => $value,
			];
		}

		return [
			$this->field->id => $value,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		$field_values += $this->value;
	}
}
