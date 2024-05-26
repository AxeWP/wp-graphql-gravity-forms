<?php
/**
 * DataLoader - Forms
 *
 * Loads Models for Gravity Forms Forms.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Loader;

use WPGraphQL;
use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Model\FormField;

/**
 * Class - FormFieldsLoader
 */
class FormFieldsLoader extends AbstractDataLoader {
	/**
	 * Loader name. Same as the GraphQL Object.
	 *
	 * @var string
	 */
	public static string $name = 'gf_form_field';

	/**
	 * {@inheritDoc}
	 *
	 * @param array{field:\GF_Field,form:\WPGraphQL\GF\Model\Form} $entry The entry to be modeled.
	 */
	protected function get_model( $entry, $key ): FormField {
		return new FormField( $entry['field'], $entry['form']->form );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		// We'll store the forms here.
		$loaded_forms = [];

		$loaded_fields = [];
		foreach ( $keys as $key ) {
			if ( empty( $key ) ) {
				continue;
			}

			// The key consists of the {form_id}:{field_id}.
			$key_parts = explode( ':', (string) $key );

			// Bail if no key parts.
			if ( empty( $key_parts ) || count( $key_parts ) < 2 ) {
				continue;
			}

			$form_id  = (int) $key_parts[0];
			$field_id = (int) $key_parts[1];

			// Get the form from the dataloader.
			if ( ! isset( $loaded_forms[ $form_id ] ) ) {
				$app_context = WPGraphQL::get_app_context();

				$form = $app_context->get_loader( FormsLoader::$name )->load( $key_parts[0] );

				$loaded_forms[ $form_id ] = $form ?? null;
			}

			if ( empty( $loaded_forms[ $form_id ] ) ) {
				continue;
			}

			/**
			 * Match the id to the form field.
			 *
			 * @var array<int,\GF_Field> $model_form_fields The form fields from the model.
			 */
			$model_form_fields = $loaded_forms[ $form_id ]->formFields;

			if ( empty( $model_form_fields ) ) {
				$loaded_fields[ $field_id ] = null;
				continue;
			}

			// Get the Field with the ID matching the key.
			$form_field = null;
			foreach ( $model_form_fields as $field ) {
				if ( (int) $field->id === $field_id ) {
					$form_field = $field;
					break;
				}
			}

			$loaded_fields[ $key ] = [
				'field' => $form_field,
				'form'  => $loaded_forms[ $form_id ],
			];
		}

		return $loaded_fields;
	}
}
