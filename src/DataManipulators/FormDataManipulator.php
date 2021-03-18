<?php
/**
 * DataManipulators - FormData
 *
 * Manipulates Form data.
 *
 * @package WPGraphQLGravityForms\DataManipulators
 * @since 0.0.1
 * @since 0.3.1 `limitEntriesCount`, `scheduleEndHour` and `scheduleEndMinute` correctly return as type `Int`.
 */

namespace WPGraphQLGravityForms\DataManipulators;

use GraphQLRelay\Relay;
use WPGraphQLGravityForms\Interfaces\DataManipulator;
use WPGraphQLGravityForms\Types\Form\Form;

/**
 * Class - FormDataManipulator
 */
class FormDataManipulator implements DataManipulator {
	/**
	 * FieldsDataManipulator instance.
	 *
	 * @var FieldsDataManipulator
	 */
	private $fields_data_manipulator;

	/**
	 * Constructor
	 *
	 * @param FieldsDataManipulator $fields_data_manipulator .
	 */
	public function __construct( FieldsDataManipulator $fields_data_manipulator ) {
		$this->fields_data_manipulator = $fields_data_manipulator;
	}

	/**
	 * Manipulate form data.
	 *
	 * @param array $data The form data to be manipulated.
	 *
	 * @return array Manipulated form data.
	 */
	public function manipulate( array $data ) : array {
		$data = $this->set_global_and_form_ids( $data );
		$data = $this->set_css_class_list( $data );
		$data = $this->convert_form_keys_to_camelcase( $data );
		$data = $this->prevent_missing_values( $data );

		$data['fields'] = $this->fields_data_manipulator->manipulate( $data['fields'] );

		return $data;
	}

	/**
	 * Set 'formId' to be the form ID and 'id' to be the global Relay ID.
	 *
	 * @param array $form Form meta array.
	 *
	 * @return array $form Form meta array with the form ID and global Relay ID set.
	 */
	private function set_global_and_form_ids( array $form ) : array {
		$form['formId'] = $form['id'];
		$form['id']     = Relay::toGlobalId( Form::TYPE, $form['formId'] );

		return $form;
	}

	/**
	 * Returns form meta array with the cssClassList value set.
	 *
	 * @param array $form Form meta array.
	 *
	 * @return array
	 */
	private function set_css_class_list( array $form ) : array {
		if ( empty( $form['cssClass'] ) ) {
			$form['cssClassList'] = null;
			return $form;
		}

		$form['cssClassList'] = array_filter(
			explode( ' ', $form['cssClass'] ),
			function( $css_class ) {
				return '' !== $css_class;
			}
		);

		return $form;
	}

	/**
	 * Ensure that keys are present and set to the appropriate values. This is especially
	 * important for Integer fields, since graphql-php does not coerce then to int values,
	 * and an error will be thrown otherwise.
	 *
	 * @param array $form Form meta array.
	 *
	 * @return array $form Form meta array with some values converted to null.
	 */
	private function prevent_missing_values( array $form ) : array {
		$form['limitEntriesCount']   = isset( $form['limitEntriesCount'] ) ? (int) $form['limitEntriesCount'] : false;
		$form['scheduleStartHour']   = isset( $form['scheduleStartHour'] ) ? (int) $form['scheduleStartHour'] : null;
		$form['scheduleStartMinute'] = isset( $form['scheduleStartMinute'] ) ? (int) $form['scheduleStartMinute'] : null;
		$form['scheduleEndHour']     = isset( $form['scheduleEndHour'] ) ? (int) $form['scheduleEndHour'] : null;
		$form['scheduleEndMinute']   = isset( $form['scheduleEndMinute'] ) ? (int) $form['scheduleEndMinute'] : null;

		if ( ! empty( $form['confirmations'] ) ) {
			$form['confirmations'] = $this->nullify_confirmation_page_id_empty_strings( $form['confirmations'] );
		}

		return $form;
	}

	/**
	 * Replace empty confirmation pageId string values with null.
	 *
	 * @param array $confirmations Form confirmations.
	 *
	 * @return array Form confirmations with empty string pageId values converted to null.
	 */
	private function nullify_confirmation_page_id_empty_strings( array $confirmations ) : array {
		return array_map(
			function( $confirmation ) {
				$confirmation['pageId'] = $confirmation['pageId'] ?: null;
				return $confirmation;
			},
			$confirmations
		);
	}

	/**
	 * Returns Form meta array with keys converted to camelCase.
	 *
	 * @param array $form Form meta array.
	 *
	 * @return array
	 */
	private function convert_form_keys_to_camelcase( array $form ) : array {
		$form['isActive']    = $form['is_active'];
		$form['dateCreated'] = $form['date_created'];
		$form['isTrash']     = $form['is_trash'];

		if ( isset( $form['pagination']['display_progressbar_on_confirmation'] ) ) {
			$form['pagination']['displayProgressbarOnConfirmation'] = $form['pagination']['display_progressbar_on_confirmation'];
		}

		if ( isset( $form['pagination']['progressbar_completion_text'] ) ) {
			$form['pagination']['progressbarCompletionText'] = $form['pagination']['progressbar_completion_text'];
		}

		unset(
			$form['is_active'],
			$form['date_created'],
			$form['is_trash'],
			$form['pagination']['display_progressbar_on_confirmation'],
			$form['pagination']['progressbar_completion_text']
		);

		return $form;
	}
}
