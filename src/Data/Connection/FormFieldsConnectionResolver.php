<?php
/**
 * ConnectionResolver - FormFields
 *
 * Resolves connections to FormFields.
 *
 * @package WPGraphQL\GF\Data\Connection
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;

/**
 * Class - FormFieldsConnectionResolver
 */
class FormFieldsConnectionResolver {
	/**
	 * @var array<string, null>
	 */
	private const EMPTY_CHOICES = [
		'text'       => null,
		'value'      => null,
		'isSelected' => null,
		'price'      => null,
	];

	/**
	 * Coerces form fields into a format GraphQL can understand.
	 *
	 * Instead of a Model.
	 *
	 * @param array $data array of form fields.
	 */
	public static function prepare_data( array $data ): array {
		foreach ( $data as &$field ) {
			// Set layoutGridColumnSpan to int.
			$field->layoutGridColumnSpan = empty( $field->layoutGridColumnSpan ) ? null : (int) $field->layoutGridColumnSpan;

			// Set empty values to null.
			foreach ( get_object_vars( $field ) as $key => $value ) {
				if ( '' !== $value ) {
					continue;
				}

				$field->$key = null;
			}

			if ( in_array( $field->type, [ 'address', 'name' ], true ) ) {
				foreach ( $field->inputs as $input_index => $input ) {
					// set isHidden to boolean.
					$field->inputs[ $input_index ]['isHidden'] = ! empty( $field->inputs[ $input_index ]['isHidden'] );

					$input_keys = 'address' === $field->type ? self::get_address_input_keys() : self::get_name_input_keys();

					$field->inputs[ $input_index ]['key'] = $input_keys[ $input_index ];
				}
			}

			// Set choices for single-column list fields, so we can use the same mutation for both.
			if ( 'list' === $field->type && empty( $field['choices'] ) ) {
				$field['choices'] = self::EMPTY_CHOICES;
			}
		}

		return $data;
	}

	/**
	 * The connection resolve method.
	 *
	 * @param mixed                                $source  The object the connection is coming from.
	 * @param array                                $args    Array of args to be passed down to the resolve method.
	 * @param \WPGraphQL\AppContext                $context The AppContext object to be passed down.
	 * @param \GraphQL\Type\Definition\ResolveInfo $info The ResolveInfo object.
	 *
	 * @return mixed|array|\WPGraphQL\GF\Data\Connection\Deferred
	 */
	public static function resolve( $source, array $args, AppContext $context, ResolveInfo $info ) {
		if ( ! is_array( $source ) || empty( $source ) ) {
			return null;
		}

		$fields = self::filter_form_fields_by_connection_args( $source, $args );

		$fields = self::prepare_data( $source );

		$connection = Relay::connectionFromArray( $fields, $args );

		$nodes = [];
		if ( ! empty( $connection['edges'] ) && is_array( $connection['edges'] ) ) {
			foreach ( $connection['edges'] as $edge ) {
				$nodes[] = empty( $edge['node'] ) ? null : $edge['node'];
			}
		}

		$connection['nodes'] = empty( $nodes ) ? null : $nodes;

		return $connection;
	}

	/**
	 * Returns input keys for Address field.
	 *
	 * @return array
	 */
	private static function get_address_input_keys(): array {
		return [
			'street',
			'lineTwo',
			'city',
			'state',
			'zip',
			'country',
		];
	}

	/**
	 * Returns input keys for Name field.
	 *
	 * @return array
	 */
	private static function get_name_input_keys(): array {
		return [
			'prefix',
			'first',
			'middle',
			'last',
			'suffix',
		];
	}

		/**
		 * Filters the form fields by the connection's where args.
		 *
		 * @param array $fields .
		 * @param array $args .
		 * @return array
		 */
	private static function filter_form_fields_by_connection_args( $fields, $args ): array {
		if ( isset( $args['where']['ids'] ) ) {
			if ( ! is_array( $args['where']['ids'] ) ) {
				$args['where']['ids'] = [ $args['where']['ids'] ];
			}

			$ids = array_map( 'absint', $args['where']['ids'] );

			$fields = array_filter( $fields, static fn ( $field ) => in_array( (int) $field['id'], $ids, true ) );
		}

		if ( isset( $args['where']['adminLabels'] ) ) {
			if ( ! is_array( $args['where']['adminLabels'] ) ) {
				$args['where']['adminLabels'] = [ $args['where']['adminLabels'] ];
			}

			$admin_labels = array_map( 'sanitize_text_field', $args['where']['adminLabels'] );

			$fields = array_filter( $fields, static fn ( $field ) => in_array( $field['adminLabel'], $admin_labels, true ) );
		}

		if ( isset( $args['where']['fieldTypes'] ) ) {
			if ( ! is_array( $args['where']['fieldTypes'] ) ) {
				$args['where']['fieldTypes'] = [ $args['where']['fieldTypes'] ];
			}

			$fields = array_filter( $fields, static fn ( $field ) => in_array( $field['type'], $args['where']['fieldTypes'], true ) );
		}

		if ( isset( $args['where']['pageNumber'] ) ) {
			$page = absint( $args['where']['pageNumber'] );

			$fields = array_filter( $fields, static fn ( $field ) => $page === (int) $field['pageNumber'] );
		}

		return $fields;
	}
}
