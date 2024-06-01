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

use GraphQL\Deferred;
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
	 * Formatted ID for the DataLoader.
	 *
	 * @param int $form_id The ID of the form.
	 * @param int $field_id The ID of the field.
	 */
	public static function prepare_loader_id( int $form_id, int $field_id ): string {
		return "{$form_id}:{$field_id}";
	}

	/**
	 * Parses the ID for the DataLoader.
	 *
	 * @param string $id The ID of the DataLoader.
	 * @return ?array{form_id:int,field_id:int}
	 */
	public static function parse_loader_id( string $id ): ?array {
		$exploded = explode( ':', $id );

		if ( empty( $exploded ) || count( $exploded ) < 2 ) {
			return null;
		}

		return [
			'form_id'  => (int) $exploded[0],
			'field_id' => (int) $exploded[1],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function load_deferred( $id ) {
		$id = sanitize_text_field( $id );

		if ( empty( $id ) ) {
			return null;
		}

		$this->buffer( [ $id ] );

		return new Deferred(
			function () use ( $id ) {
				return $this->load( $id );
			}
		);
	}

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

			$parsed_id = self::parse_loader_id( (string) $key );

			if ( null === $parsed_id ) {
				continue;
			}

			// Get the form from the dataloader.
			if ( ! isset( $loaded_forms[ $parsed_id['form_id'] ] ) ) {
				$app_context = WPGraphQL::get_app_context();

				$form = $app_context->get_loader( FormsLoader::$name )->load( $parsed_id['form_id'] );

				$loaded_forms[ $parsed_id['form_id'] ] = $form ?? null;
			}

			if ( empty( $loaded_forms[ $parsed_id['form_id'] ] ) ) {
				continue;
			}

			/**
			 * Match the id to the form field.
			 *
			 * @var array<int,\GF_Field> $model_form_fields The form fields from the model.
			 */
			$model_form_fields = $loaded_forms[ $parsed_id['form_id'] ]->formFields;

			if ( empty( $model_form_fields ) ) {
				$loaded_fields[ $parsed_id['field_id'] ] = null;
				continue;
			}

			// Get the Field with the ID matching the key.
			$form_field = null;
			foreach ( $model_form_fields as $field ) {
				if ( (int) $field->id === $parsed_id['field_id'] ) {
					$form_field = $field;
					break;
				}
			}

			$loaded_fields[ $key ] = [
				'field' => $form_field,
				'form'  => $loaded_forms[ $parsed_id['form_id'] ],
			];
		}

		return $loaded_fields;
	}
}
