<?php
/**
 * Manipulates input data for Image field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GF_Field;
/**
 * Class - ImageValuesInput
 */
class ImageValuesInput extends FileUploadValuesInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var \GF_Field_Post_Image
	 */
	protected GF_Field $field;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'imageValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	public $value;

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError
	 */
	protected function prepare_value() {
		$value      = $this->args;
		$prev_value = $this->entry[ $this->field->id ] ?? '';
		if ( is_string( $prev_value ) ) {
			$prev_value = explode( '|:|', $prev_value );
		}

		// change input value for parent function.
		$this->args = isset( $value['image'] ) ? [ $value['image'] ] : [];

		/**
		 * We're force-uploading the image here because otherwise move_uploaded_file() fails in testing, with no way to mock.
		 *
		 * This is a crude workaround until AspectMock is updated to support PHP 8.
		 */
		parent::prepare_value();

		$url        = $this->field->get_single_file_value( $this->form['id'], 'input_' . $this->field->id );
		$url        = $url ?: $prev_value[0] ?? null;
		$this->args = $value;

		$title       = $value['title'] ?? $prev_value[1] ?? null;
		$caption     = $value['caption'] ?? $prev_value[2] ?? null;
		$description = $value['description'] ?? $prev_value[3] ?? null;
		$alt         = $value['altText'] ?? $prev_value[4] ?? null;

		if ( $this->is_draft ) {
			$_POST[ 'input_' . $this->field->id . '_0' ] = $url;
			$_POST[ 'input_' . $this->field->id . '_1' ] = $title;
			$_POST[ 'input_' . $this->field->id . '_4' ] = $caption;
			$_POST[ 'input_' . $this->field->id . '_7' ] = $description;
			$_POST[ 'input_' . $this->field->id . '_2' ] = $alt;
		}

		return [
			$this->field->id . '_0' => $url,
			$this->field->id . '_1' => $title,
			$this->field->id . '_4' => $caption,
			$this->field->id . '_7' => $description,
			$this->field->id . '_2' => $alt,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		if ( ! $this->is_draft && empty( $this->entry ) ) {
			$field_values += $this->value;
		} else {
			$field_values[ $this->field->id ] = implode( '|:|', array_values( $this->value ) );
		}
	}
}
