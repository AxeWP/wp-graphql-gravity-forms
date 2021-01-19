<?php

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\NameInput;

/**
 * Update a Gravity Forms draft entry name field value.
 */
class UpdateDraftEntryNameFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryNameFieldValue';

	/**
	 * @return array The input field value.
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => NameInput::TYPE,
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * @param string The field value.
	 *
	 * @return array The sanitized field value.
	 */
	protected function prepare_field_value( array $value ) : array {
		return [ $this->field->id => 
			[
				'prefix' => array_key_exists( 'prefix', $value ) ? sanitize_text_field( $value['prefix'] ) : null,
				'first' => array_key_exists( 'first', $value ) ? sanitize_text_field( $value['first'] ) : null,
				'middle' => array_key_exists( 'middle', $value ) ? sanitize_text_field( $value['middle'] ) : null,
				'last' => array_key_exists( 'last', $value ) ? sanitize_text_field( $value['last'] ) : null,
				'suffix' => array_key_exists( 'suffix', $value ) ? sanitize_text_field( $value['suffix'] ) : null,
			]
		];
	}
}
