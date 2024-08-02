<?php
/**
 * Manipulates input data for field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

use GF_Field;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - AbstractFieldValueInput
 */
abstract class AbstractFieldValueInput {
	/**
	 * The GraphQL field input args provided to the mutation.
	 *
	 * @var mixed[]|string
	 */
	protected $args;

	/**
	 * The Gravity Forms entry object, if it exists.
	 *
	 * @var array<string|int,mixed>|null
	 */
	protected ?array $entry;

	/**
	 * The Gravity Forms field object.
	 *
	 * @var \GF_Field
	 */
	protected GF_Field $field;

	/**
	 * The GraphQL input field name used by the Field's value input.
	 *
	 * @var string
	 */
	protected string $field_name;

	/**
	 * The Gravity Forms form object.
	 *
	 * @var array<string,mixed>
	 */
	protected array $form;

	/**
	 * The GraphQL input args passed to `fieldValues`.
	 *
	 * @var array<string,mixed>
	 */
	protected array $input_args;

	/**
	 * Whether this input is for a draft mutation.
	 *
	 * @var bool
	 */
	protected bool $is_draft;

	/**
	 * The field value for submission.
	 *
	 * @var mixed[]|string
	 */
	public $value;

	/**
	 * The class constructor.
	 *
	 * @param array<string,mixed>          $input_args The GraphQL input args for the form field.
	 * @param array<string,mixed>          $form       The current Gravity Forms form object.
	 * @param bool                         $is_draft   Whether the mutation is handling a Draft Entry.
	 * @param \GF_Field                    $field      The current Gravity Forms field object.
	 * @param array<int|string,mixed>|null $entry      The current Gravity Forms entry object.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public function __construct( array $input_args, array $form, bool $is_draft, ?GF_Field $field = null, ?array $entry = null ) {
		$this->input_args = $input_args;
		$this->form       = $form;
		$this->is_draft   = $is_draft;
		$this->field      = null !== $field ? $field : GFUtils::get_field_by_id( $form, $input_args['id'] );
		$this->entry      = $entry ?: null;

		/**
		 * Filters the accepted GraphQL input value key for the form field.
		 *
		 * @param string                       $name The GraphQL input value name to use. E.g. `nameValues`.
		 * @param \GF_Field                    $field The current Gravity Forms field object.
		 * @param array<string,mixed>          $form The current Gravity Forms form object.
		 * @param array<int|string,mixed>|null $entry The current Gravity Forms entry object. Only available when using update (`gfUpdateEntry`, `gfUpdateDraftEntry`) mutations.
		 * @param bool                         $is_draft_mutation Whether the mutation is handling a Draft Entry (`gfUpdateDraftEntry`, or `gfSubmitForm` when `saveAsDraft` is `true`).
		 */
		$this->field_name = apply_filters(
			'graphql_gf_field_value_input_name',
			$this->get_field_name(),
			$this->field,
			$this->form,
			$this->entry,
			$this->is_draft
		);

		if ( ! $this->is_valid_input_type() ) {
			throw new UserError(
				sprintf(
					// translators: field ID, input key.
					esc_html__( 'Mutation not processed. Field %1$s requires the use of `%2$s`.', 'wp-graphql-gravity-forms' ),
					esc_html( $this->field->id ),
					esc_html( $this->field_name ),
				)
			);
		}

		/**
		 * Filters the GraphQL input args for the field value input.
		 *
		 * @param array<string,mixed>|string $args              Field value input args.
		 * @param \GF_Field                  $field             The current Gravity Forms field object.
		 * @param array<string,mixed>        $form              The current  Gravity Forms form object.
		 * @param ?array<int|string,mixed>   $entry             The current Gravity Forms entry object. Only available when using update (`gfUpdateEntry`, `gfUpdateDraftEntry`) mutations.
		 * @param bool                       $is_draft_mutation Whether the mutation is handling a Draft Entry (`gfUpdateDraftEntry`, or `gfSubmitForm` when `saveAsDraft` is `true`).
		 * @param string                     $field_name        The GraphQL input field name. E.g. `nameValues`.
		 */
		$this->args = apply_filters(
			'graphql_gf_field_value_input_args',
			$this->get_args(),
			$this->field,
			$this->form,
			$this->entry,
			$this->is_draft,
			$this->field_name,
		);

		/**
		 * Filters the prepared field value to be submitted to Gravity Forms.
		 *
		 * @param array|string               $prepared_field_value The field value formatted in a way Gravity Forms can understand.
		 * @param array<string,mixed>|string $args                 Field value input args.
		 * @param \GF_Field                  $field                The current Gravity Forms field object.
		 * @param array<string,mixed>        $form                 The current Gravity Forms form object.
		 * @param ?array<int|string,mixed>   $entry                The current Gravity Forms entry object. Only available when using update (`gfUpdateEntry`, `gfUpdateDraftEntry`) mutations.
		 * @param bool                       $is_draft_mutation    Whether the mutation is handling a Draft Entry (`gfUpdateDraftEntry`, or `gfSubmitForm` when `saveAsDraft` is `true`).
		 * @param string                     $field_name           The GraphQL input field name. E.g. `nameValues`.
		 */
		$this->value = apply_filters(
			'graphql_gf_field_value_input_prepared_value',
			$this->prepare_value(),
			$this->args,
			$this->field,
			$this->form,
			$this->entry,
			$this->is_draft,
			$this->field_name,
		);
	}

	/**
	 * Gets the key for the GraphQL field value input.
	 *
	 * E.g. `nameValues`.
	 */
	abstract protected function get_field_name(): string;

	/**
	 * Checks whether the input values submitted to the mutation are using the correct field value input for the Gravity Forms field type.
	 */
	protected function is_valid_input_type(): bool {
		$is_valid = false;

		$key = $this->field_name;

		if ( isset( $this->input_args[ $key ] ) ) {
			$is_valid = true;
		}

		return $is_valid;
	}

	/**
	 * Gets the input args for the specified field value input.
	 *
	 * @return string|mixed[]
	 */
	public function get_args() {
		$key = $this->field_name;

		return $this->input_args[ $key ];
	}

	/**
	 * Converts the field value args to a format GravityForms can understand.
	 *
	 * @return string|mixed[] the sanitized value.
	 */
	protected function prepare_value() {
		// You probably want to replace this.
		return $this->args;
	}

	/**
	 * Manually runs GF_Field::validate, and grabs any validation errors.
	 *
	 * @param array{id:int,message:string}[] $errors the array of validation errors.
	 *
	 * @param-out array{id:mixed,message:mixed}[] $errors
	 */
	public function validate_value( array &$errors ): void {
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
	 * @param array<int|string,mixed> $field_values the existing field values array.
	 */
	public function add_value_to_submission( array &$field_values ): void {
		$field_values[ $this->field->id ] = $this->value;
	}
}
