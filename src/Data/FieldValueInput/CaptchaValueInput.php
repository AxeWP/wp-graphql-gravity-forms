<?php
/**
 * Manipulates input data for Captcha field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.11.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GF_Field;

/**
 * Class - CaptchaValueInput
 */
class CaptchaValueInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 */
	public function __construct( array $input_args, array $form, bool $is_draft, GF_Field $field = null, array $entry = null ) {
		parent::__construct( $input_args, $form, $is_draft, $field, $entry );

		// Set the $_POST object, since we use that instead of field vales.
		$captcha_type = $this->field->captchaType ?? null;
		if ( null === $captcha_type ) {
			return;
		}

		// SimpleCaptcha and Math use input_captcha_prefix.
		if ( 'simple_captcha' === $captcha_type || 'math' === $captcha_type ) {
			$_POST[ sprintf( 'input_captcha_prefix_%s', esc_attr( $this->field->id ) ) ] = $this->value;
		}

		// Recaptcha uses g-recaptcha-response.
		$_POST['g-recaptcha-response'] = $this->value;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'value';
	}

	/**
	 * {@inheritDoc}
	 *
	 * Recaptchas are validated using the $_POST object, not in the submission values.
	 */
	public function add_value_to_submission( array &$field_values ): void {
		// noop.
	}
}
