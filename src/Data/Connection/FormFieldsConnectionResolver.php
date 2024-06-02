<?php
/**
 * ConnectionResolver - FormFields
 *
 * Resolves connections to FormFields.
 *
 * @package WPGraphQL\GF\Data\Connection
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;
use WPGraphQL\GF\Model\Form;
use WPGraphQL\GF\Model\FormField;

/**
 * Class - FormFieldsConnectionResolver
 *
 * @extends \WPGraphQL\Data\Connection\AbstractConnectionResolver<array<int,\GF_Field>>
 */
class FormFieldsConnectionResolver extends AbstractConnectionResolver {
	/**
	 * The form model.
	 *
	 * @var \WPGraphQL\GF\Model\Form
	 */
	protected Form $form;

	/**
	 * The unmodified array of Form Fields.
	 *
	 * @var array<int,\GF_Field>
	 */
	protected array $form_fields;

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception If the Form model is not set on the AppContext.
	 */
	public function __construct( $source, array $args, AppContext $context, ResolveInfo $info ) {
		if ( ! isset( $context->gfForm ) ) {
			throw new \Exception( 'The FormFieldsConnectionResolver requires a Form to be set on the AppContext.' );
		}

		$this->form        = $context->gfForm;
		$this->form_fields = ! empty( $source->formFields ) ? $source->formFields : [];

		parent::__construct( $source, $args, $context, $info );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loader_name(): string {
		return FormFieldsLoader::$name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_valid_offset( $offset ) {
		foreach ( $this->form_fields as $field ) {
			if ( (int) $field->id === (int) $offset ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_ids_from_query() {
		$queried = $this->get_query() ?: [];

		if ( empty( $queried ) ) {
			return [];
		}

		$ids = [];
		foreach ( $queried as $item ) {
			$ids[ (int) $item->id ] = $item->id;
		}

		return $ids;
	}

	/**
	 * {@inheritDoc}
	 */
	public function apply_cursors_to_ids( array $ids ) {
		if ( empty( $ids ) ) {
			return [];
		}
		$args = $this->get_args();

		// First we slice the array from the front.
		if ( ! empty( $args['after'] ) ) {
			$offset = $this->get_offset_for_cursor( $args['after'] );
			$index  = $this->get_array_index_for_offset( $offset, $ids );

			if ( false !== $index ) {
				// We want to start with the first id after the index.
				$ids = array_slice( $ids, $index + 1, null, true );
			}
		}

		// Then we slice the array from the back.
		if ( ! empty( $args['before'] ) ) {
			$offset = $this->get_offset_for_cursor( $args['before'] );
			$index  = $this->get_array_index_for_offset( $offset, $ids );

			if ( false !== $index ) {
				// We want to end with the id before the index.
				$ids = array_slice( $ids, 0, $index, true );
			}
		}

		return $ids;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_args( array $args ): array {
			// Ensure that the ids are an array of ints.
		if ( isset( $args['where']['ids'] ) ) {
			if ( ! is_array( $args['where']['ids'] ) ) {
				$args['where']['ids'] = [ $args['where']['ids'] ];
			}

			// Sanitize the IDs.
			$args['where']['ids'] = array_map( 'absint', $args['where']['ids'] );
		}

		// Ensure that Admin labels are an array of strings.
		if ( isset( $args['where']['adminLabels'] ) ) {
			if ( ! is_array( $args['where']['adminLabels'] ) ) {
				$args['where']['adminLabels'] = [ $args['where']['adminLabels'] ];
			}

			// Sanitize the Admin labels.
			$args['where']['adminLabels'] = array_map( 'sanitize_text_field', $args['where']['adminLabels'] );
		}

		// Ensure that Field types are an array of strings.
		if ( isset( $args['where']['fieldTypes'] ) ) {
			if ( ! is_array( $args['where']['fieldTypes'] ) ) {
				$args['where']['fieldTypes'] = [ $args['where']['fieldTypes'] ];
			}

			// Sanitize the Field types.
			$args['where']['fieldTypes'] = array_map( 'sanitize_text_field', $args['where']['fieldTypes'] );
		}

		// Ensure that Page number is an integer.
		if ( isset( $args['where']['pageNumber'] ) ) {
			$args['where']['pageNumber'] = absint( $args['where']['pageNumber'] );
		}

		return $args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_query_args( array $args ): array {
		return [
			'ids'         => $args['where']['ids'] ?? null,
			'adminLabels' => $args['where']['adminLabels'] ?? null,
			'fieldTypes'  => $args['where']['fieldTypes'] ?? null,
			'pageNumber'  => $args['where']['pageNumber'] ?? null,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function query( array $query_args ): array {
		$fields = $this->form_fields;

		// Filter by IDs.
		if ( ! empty( $query_args['ids'] ) ) {
			$fields = array_filter( $fields, static fn ( \GF_Field $field ) => isset( $field->id ) && in_array( (int) $field->id, $query_args['ids'], true ) );
		}

		// Filter by Admin labels.
		if ( ! empty( $query_args['adminLabels'] ) ) {
			$fields = array_filter( $fields, static fn ( \GF_Field $field ) => isset( $field->adminLabel ) && in_array( $field->adminLabel, $query_args['adminLabels'], true ) );
		}

		// Filter by Field types.
		if ( ! empty( $query_args['fieldTypes'] ) ) {
			$fields = array_filter( $fields, static fn ( \GF_Field $field ) => isset( $field->type ) && in_array( $field->type, $query_args['fieldTypes'], true ) );
		}

		// Filter by Page number.
		$has_page_number = false;
		if ( ! empty( $query_args['pageNumber'] ) ) {
			$filtered_fields = array_filter(
				$fields,
				static function ( \GF_Field $field ) use ( $query_args, &$has_page_number ) {
					if ( ! isset( $field->pageNumber ) ) {
						return false;
					}

					// Set the flag to true if the page number is found.
					$has_page_number = true;

					return $query_args['pageNumber'] === (int) $field->pageNumber;
				}
			);

			// Dont use filtered fileds if the form isnt paged.
			if ( $has_page_number || 1 < $query_args['pageNumber'] ) {
				$fields = $filtered_fields;
			}
		}

		return $fields;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_valid_model( $model ) {
		return $model instanceof FormField;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_node_by_id( $id ) {
		// The id needs to include the form.
		$id_for_loader = FormFieldsLoader::prepare_loader_id( $this->form->databaseId, (int) $id );

		return parent::get_node_by_id( $id_for_loader );
	}
}
