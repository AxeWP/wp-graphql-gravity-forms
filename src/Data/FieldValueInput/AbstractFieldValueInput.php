<?php
/**
 * Manipulates input data for field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GF_Field;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - AbstractFieldValueInput
 */
abstract class AbstractFieldValueInput {
	/**
	 * The Gravity Forms entry object, if it exists.
	 *
	 * @var array|null
	 */
	protected ?array $entry;

	/**
	 * The Gravity Forms form object.
	 *
	 * @var array
	 */
	protected array $form;

	/**
	 * The Gravity Forms field object.
	 *
	 * @var GF_Field
	 */
	protected GF_Field $field;

	/**
	 * The GraphQL object key used by the Field's value input.
	 *
	 * @var string
	 */
	protected string $value_key;

	/**
	 * The GraphQL field input value provided to the mutation.
	 *
	 * @var array|string
	 */
	protected $input_value;

	/**
	 * The field value for submission.
	 *
	 * @var array|string
	 */
	public $value;

	/**
	 * The class constructor.
	 *
	 * @param array      $input_values .
	 * @param array      $form .
	 * @param GF_Field   $field .
	 * @param array|null $entry .
	 *
	 * @throws UserError .
	 */
	public function __construct( array $input_values, array $form, GF_Field $field = null, array $entry = null ) {
		$this->form  = $form;
		$this->field = null !== $field ? $field : GFUtils::get_field_by_id( $form, $input_values['id'] );

		$this->value_key = $this->get_value_key();

		if ( ! $this->is_valid_input_type( $input_values ) ) {
			throw new UserError(
				sprintf(
					// translators: field ID, input key.
					__( 'Mutation not processed. Field %1$s requires the use of `%2$s`.', 'wp-graphql-gravity-forms' ),
					$this->field->id,
					$this->value_key,
				)
			);
		}

		$this->input_value = $this->get_value_from_input( $input_values );
		$this->entry       = $entry ?: null;

		$this->value = $this->prepare_value();
	}

	/**
	 * Gets the key for the GraphQL field value input.
	 *
	 * E.g. `nameValues`.
	 */
	abstract public function get_value_key() : string;

	/**
	 * Checks whether the input values submitted to the mutation are using the correct field value input for the Gravity Forms field type.
	 *
	 * @param array $input_values The input values for the Gravity Forms field.
	 */
	protected function is_valid_input_type( $input_values ) : bool {
		$is_valid = false;

		$key = $this->get_value_key();

		if ( isset( $input_values[ $key ] ) ) {
			$is_valid = true;
		}

		return $is_valid;
	}

	/**
	 * Gets the specific input value, based on the field-specific input value key.
	 *
	 * @param array $input_values .
	 * @return string|array
	 */
	public function get_value_from_input( array $input_values ) {
		return $input_values[ $this->get_value_key() ];
	}

	/**
	 * Sanitizes the input value and converts it to a format GravityForms can understand.
	 *
	 * @return string|array the sanitized value.
	 */
	protected function prepare_value() {
		return $this->input_value;
	}

	/**
	 * Manually runs GF_Field::validate, and grabs any validation errors.
	 *
	 * @param array $errors .
	 */
	public function validate_value( array &$errors ) : void {
		$this->field->validate( $this->value, $this->form );

		if ( ! empty( $this->field->failed_validation ) ) {
			$errors[] = [
				'id'      => $this->field->id,
				'message' => $this->field->validation_message,
			];
		}
	}

	/**
	 * Adds the prepared value to the field values array for processing by Gravity Forms.
	 *
	 * @param array $field_values the existing field values array.
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		$field_values[ $this->field->id ] = $this->value;
	}
}
