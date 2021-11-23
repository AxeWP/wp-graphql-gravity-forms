<?php
/**
 * Connection - EntryConnections
 *
 * Registers all connections TO Gravity Forms Field.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.8.0
 */

namespace WPGraphQL\GF\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\GF;
use WPGraphQL\GF\DataManipulators\FieldsDataManipulator;
use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\GF\Type\WPInterface\FormField;

use WPGraphQL\GF\Type\Enum\FormFieldsEnum;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - EntryConnections
 */
class FormFieldConnections extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// Form to Field.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => Form::$type,
					'toType'         => FormField::$type,
					'fromFieldName'  => 'formFields',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function( $root, array $args, AppContext $context, ResolveInfo $info ) {
							$fields              = static::filter_form_fields_by_connection_args( $root['fields'], $args );
							$fields              = FieldsDataManipulator::manipulate( $fields );
							$connection          = Relay::connectionFromArray( $fields, $args );
							$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
							$connection['nodes'] = $nodes ?: null;
							return $connection;
					},
				]
			)
		);

		// Entry to Field.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => Entry::$type,
					'toType'         => FormField::$type,
					'fromFieldName'  => 'formFields',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						$form = GFUtils::get_form( $root['formId'], false );

						$fields = self::filter_form_fields_by_connection_args( $form['fields'], $args );

						$fields = FieldsDataManipulator::manipulate( $fields );

						$connection = Relay::connectionFromArray( $fields, $args );

						// Add the entry to each edge with a key of 'source'. This is needed so that
						// the fieldValue edge field resolver has has access to the form entry.
						$connection['edges'] = array_map(
							function( $edge ) use ( $root ) {
								$edge['source'] = $root;
								return $edge;
							},
							$connection['edges']
						);

						$nodes               = array_map(
							function( $edge ) {
								$edge['node']           = $edge['node'] ?? null;
								$edge['node']['source'] = $edge['source'];
								return $edge['node'];
							},
							$connection['edges']
						);
						$connection['nodes'] = $nodes ?: null;
						return $connection;
					},
				]
			),
		);
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public static function get_connection_args() : array {
		return [
			'ids'         => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form field IDs to return.', 'wp-graphql-gravity-forms' ),
			],
			'adminLabels' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Array of form field adminLabels to return.', 'wp-graphql-gravity-forms' ),
			],
			'types'       => [
				'type'        => [ 'list_of' => FormFieldsEnum::$type ],
				'description' => __( 'Array of Gravity Forms Field types to return.', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Filters the form fields by the connection's where args.
	 *
	 * @param array $fields .
	 * @param array $args .
	 * @return array
	 */
	private static function filter_form_fields_by_connection_args( $fields, $args ) : array {
		if ( isset( $args['where']['ids'] ) ) {
			if ( ! is_array( $args['where']['ids'] ) ) {
				$args['where']['ids'] = [ $args['where']['ids'] ];
			}
			$ids = array_map( 'absint', $args['where']['ids'] );

			$fields = array_filter( $fields, fn( $field ) => in_array( (int) $field['id'], $ids, true ) );
		}

		if ( isset( $args['where']['adminLabels'] ) ) {
			if ( ! is_array( $args['where']['adminLabels'] ) ) {
				$args['where']['adminLabels'] = [ $args['where']['adminLabels'] ];
			}

			$admin_labels = array_map( 'sanitize_text_field', $args['where']['adminLabels'] );

			$fields = array_filter( $fields, fn( $field)  => in_array( $field['adminLabel'], $admin_labels, true ) );
		}

		if ( isset( $args['where']['types'] ) ) {
			if ( ! is_array( $args['where']['types'] ) ) {
				$args['where']['types'] = [ $args['where']['types'] ];
			}

			$fields = array_filter( $fields, fn( $field ) => in_array( $field['type'], $args['where']['types'], true ) );
		}

		return $fields;
	}
}
