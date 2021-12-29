<?php
/**
 * Helper functions for mutations handling form submissions
 *
 * @package WPGraphQL\GF\Data
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data;

use GF_Field;
use GFCommon;
use GFFormsModel;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Data\FieldValueInput;
use WPGraphQL\GF\Data\FieldValueInput\AbstractFieldValueInput;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - EntryObjectMutation
 */
class EntryObjectMutation {
	/**
	 * Disables validation for unsupported fields when submitting a form.
	 * Applied using the 'gform_field_validation' filter.
	 * Currently unsupported fields: captcha
	 *
	 * @param array    $result .
	 * @param mixed    $value .
	 * @param array    $form .
	 * @param GF_Field $field .
	 */
	public static function disable_validation_for_unsupported_fields( array $result, $value, array $form, GF_Field $field ) : array {
		if ( in_array( $field->get_input_type(), [ 'captcha' ], true ) ) {
			$result = [
				'is_valid' => true,
				'message'  => __( 'This field type is not (yet) supported.', 'wp-graphql-gravity-forms' ),
			];
		}
		return $result;
	}

	/**
	 * Returns the FieldValueInput object relative to the field type.
	 *
	 * @param array $args The GraphQL mutation input args for the field.
	 * @param array $form The GF form object.
	 * @param bool  $is_draft If the mutation is for a draft entry.
	 * @param array $entry The GF entry object. Used when updating.
	 */
	public static function get_field_value_input( array $args, array $form, bool $is_draft, array $entry = null ) : FieldValueInput\AbstractFieldValueInput {
		$field = GFUtils::get_field_by_id( $form, $args['id'] );

		$input_type = $field->get_input_type();

		switch ( $input_type ) {
			case 'address':
				$field_value_input = new FieldValueInput\AddressValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'checkbox':
				$field_value_input = new FieldValueInput\CheckboxValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'chainedselect':
				$field_value_input = new FieldValueInput\ChainedSelectValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'consent':
				$field_value_input = new FieldValueInput\ConsentValueInput( $args, $form, $is_draft, $field );
				break;
			case 'email':
				$field_value_input = new FieldValueInput\EmailValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'fileupload':
				$field_value_input = new FieldValueInput\FileUploadValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'list':
				$field_value_input = new FieldValueInput\ListValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'multiselect':
				$field_value_input = new FieldValueInput\ValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'name':
				$field_value_input = new FieldValueInput\NameValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'post_image':
				$field_value_input = new FieldValueInput\ImageValuesInput( $args, $form, $is_draft, $field );
				break;
			case 'signature':
				$field_value_input = new FieldValueInput\SignatureValuesInput( $args, $form, $is_draft, $field, $entry );
				break;
			case 'date':
			case 'hidden':
			case 'number':
			case 'phone':
			case 'post_content':
			case 'post_excerpt':
			case 'post_title':
			case 'radio':
			case 'select':
			case 'text':
			case 'textarea':
			case 'time':
			case 'website':
			default:
				$field_value_input = new FieldValueInput\ValueInput( $args, $form, $is_draft, $field );
		}

		/**
		 * Filters the FieldValueInput instance used to process form field submissions.
		 *
		 * Useful for adding mutation support for custom fields.
		 *
		 * @param AbstractFieldValueInput $field_value_input  The instantianted FieldValueInput class. Must extend AbstractFieldValueInput.
		 * @param array    $args The GraphQL input args for the form field.
		 * @param GF_Field $field The current Gravity Forms field object.
		 * @param array $form The current Gravity Forms form object.
		 * @param array|null $entry The current Gravity Forms entry object. Only available when using update (`gfUpdateEntry`, `gfUpdateDraftEntry`) mutations.
		 * @param bool $is_draft_mutation Whether the mutation is handling a Draft Entry (`gfUpdateDraftEntry`, or `gfSubmitForm` when `saveAsDraft` is `true`).
		 */
		return apply_filters( 'graphql_gf_field_value_input', $field_value_input, $args, $field, $form, $entry, $is_draft );
	}

	/**
	 * Generates array of field errors from the submission.
	 *
	 * @param array $messages The Gravity Forms submission validation messages.
	 */
	public static function get_submission_errors( array $messages ) : array {
		return array_map(
			function( $id, $message ) {
				return [
					'id'      => $id,
					'message' => $message,
				];
			},
			array_keys( $messages ),
			$messages
		);
	}

	/**
	 * Renames $field_value keys to input_{id}_{sub_id}, so Gravity Forms can read them.
	 *
	 * @param array $field_values .
	 * */
	public static function rename_field_names_for_submission( array $field_values ) : array {
		$formatted = [];

		foreach ( $field_values as $key => $value ) {
			$formatted[ 'input_' . str_replace( '.', '_', $key ) ] = $value;
		}
		return $formatted;
	}
}
