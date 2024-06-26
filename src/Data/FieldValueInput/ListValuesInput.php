<?php
/**
 * Manipulates input data for List field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

use GraphQL\Error\UserError;

/**
 * Class - ListValueInput
 */
class ListValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array{rowValues:string[]}[]
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 *
	 * @var string[]|string
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'listValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string[]|string
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected function prepare_value() {
		$value = $this->args;

		$values_to_save = [];
		foreach ( $value as $row ) {
			foreach ( $row as $row_values ) {
				foreach ( $row_values as $single_value ) {
					$values_to_save[] = $single_value;
				}
			}
		}

		if ( $this->field->is_administrative() && $this->field->allowsPrepopulate ) {
			$values_to_save = wp_json_encode( $values_to_save );
			if ( false === $values_to_save ) {
				throw new UserError(
					sprintf(
						// translators: field id.
						esc_html__( 'Mutation failed. Unable to encode the list field values for field %s.', 'wp-graphql-gravity-forms' ),
						esc_html( $this->field->id ),
					)
				);
			}
		}

		return $values_to_save;
	}
}
