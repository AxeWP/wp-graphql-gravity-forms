<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFCommon;
/**
 * Class - ValueInput
 */
class ValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'value';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value(): string {
		// Handle choices with price.
		if ( ! empty( $this->field->enablePrice ) && false === strpos( $this->args, '|' ) ) {
			$value_key  = ! empty( $this->field->enablePrice ) || ! empty( $this->field->enableChoiceValue ) ? 'value' : 'text';
			$choice_key = array_search( $this->args, array_column( $this->field->choices, $value_key ), true );
			$choice     = $this->field->choices[ $choice_key ];
			$price      = rgempty( 'price', $choice ) ? 0 : GFCommon::to_number( rgar( $choice, 'price' ) );
			return $this->args . '|' . $price;
		}

		if ( 'total' === $this->field->type ) {
			// Convert to number so draft updates dont return the currency.
			return GFCommon::to_number( $this->args );
		}

		return $this->args;
	}
}
