<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - ImageValuesInput
 */
class ImageValuesInput extends FileUploadValuesInput {
	/**
	 * {@inheritDoc}
	 */
	public function get_value_key() : string {
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
	 * @throws UserError
	 */
	protected function prepare_value() {
		$value      = $this->input_value;
		$prev_value = $this->entry[ $this->field->id ] ?? [];

		$url         = parent::prepare_value() ?: $prev_value[0] ?? null;
		$title       = $value['title'] ?? $prev_value[1] ?? null;
		$caption     = $value['caption'] ?? $prev_value[2] ?? null;
		$description = $value['description'] ?? $prev_value[3] ?? null;
		$alt         = $value['alt'] ?? $prev_value[4] ?? null;

		$_POST[ 'input_' . $this->field->id . '_0' ] = $url;
		$_POST[ 'input_' . $this->field->id . '_1' ] = $title;
		$_POST[ 'input_' . $this->field->id . '_4' ] = $caption;
		$_POST[ 'input_' . $this->field->id . '_7' ] = $description;
		$_POST[ 'input_' . $this->field->id . '_2' ] = $alt;

		$values_to_return = [
			$this->field->id . '_0' => $url,
			$this->field->id . '_1' => $title,
			$this->field->id . '_4' => $caption,
			$this->field->id . '_7' => $description,
			$this->field->id . '_2' => $alt,
		];

		/**
		 * Entry updates need a formatted string.
		 *
		 * Follows pattern: `$url |:| $title |:| $caption |:|$description |:| $alt`.
		 */
		if ( ! empty( $this->entry ) ) {
			return [ (string) $this->field->id => implode( '|:|', array_values( $values_to_return ) ) ];
		}

		return $values_to_return;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		$field_values += $this->value;
	}
}
