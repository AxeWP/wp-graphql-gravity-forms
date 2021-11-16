<?php
/**
 * Connection - EntryConnections
 *
 * Registers all connections TO Gravity Forms Field.
 *
 * @package WPGraphQL\GF\Connections
 * @since 0.8.0
 */

namespace WPGraphQL\GF\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\GF\GF;
use WPGraphQL\GF\DataManipulators\FieldsDataManipulator;
use WPGraphQL\GF\Types\Entry\Entry;
use WPGraphQL\GF\Types\Form\Form;
use WPGraphQL\GF\Types\GraphQLInterface\FormFieldInterface;
use WPGraphQL\GF\Types\Union\ObjectFieldValueUnion;
use WPGraphQL\GF\Interfaces\FieldValue as FieldValueInterface;
use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Enum\FormFieldsEnum;
use WPGraphQL\GF\Types\Field\AbstractFormField;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - EntryConnections
 */
class FieldConnections extends AbstractConnection {

	/**
	 * {@inheritDoc}
	 */
	public function register_connections() : void {
		// Form to Field.
		register_graphql_connection(
			$this->prepare_connection_config(
				[
					'fromType'       => Form::$type,
					'toType'         => FormFieldInterface::$type,
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
			$this->prepare_connection_config(
				[
					'fromType'       => Entry::$type,
					'toType'         => FormFieldInterface::$type,
					'fromFieldName'  => 'formFields',
					'connectionArgs' => self::get_connection_args(),
					'edgeFields'     => $this->get_edge_fields(),
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
		 * Gets edge fields.
		 *
		 * @return array
		 */
	public function get_edge_fields() :array {
		return [
			'fieldValue' => [
				'type'              => ObjectFieldValueUnion::$type,
				'description'       => __( 'Field value.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `formFields.nodes.value` instead.', 'wp-graphql-gravity-forms' ),
				'resolve'           => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
					$field = $this->get_field_by_gf_field_type( $root['node']['type'] );
					if ( ! $field ) {
						return null;
					}
					$value_class = $this->get_field_value_class( $field );

					// Account for fields that do not have a value class.
					if ( ! $value_class ) {
						return null;
					}

					return array_merge(
					// 'value_class' is included here to pass it through to the "resolveType"
					// callback function in ObjectFieldValueUnion.
						[ 'value_class' => $value_class ],
						$value_class::get( $root['source'], $root['node'] )
					);
				},
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

		/**
		 * Get the WPGraphQL field for a Gravity Forms field type.
		 *
		 * @param string $gf_field_type The Gravity Forms field type.
		 *
		 * @return AbstractFormField|null The corresponding WPGraphQL field, or null if not found.
		 */
	private function get_field_by_gf_field_type( string $gf_field_type ) {
		$fields = array_filter( GF::instances(), fn( $instance ) => $instance instanceof AbstractFormField );

		/**
		 * Deprecated filter for modifying the instances.
		 *
		 * @since 0.7.0
		 */
		$fields = apply_filters_deprecated( 'wp_graphql_gf_form_field_instances', [ $fields ], '0.7.0', 'wp_graphql_gf_instances' );

		$field_array = array_filter(
			$fields,
			function( $instance ) use ( $gf_field_type ) {
				return $instance instanceof AbstractFormField && $instance::$gf_type === $gf_field_type;
			}
		);

		return $field_array ? array_values( $field_array )[0] : null;
	}

		/**
		 * Get the field value class associated with a form field.
		 *
		 * @param  AbstractFormField $field The field class.
		 *
		 * @return FieldValueInterface|null The field value class or null if not found.
		 */
	private function get_field_value_class( AbstractFormField $field ) {
		$field_values = array_filter( GF::instances(), fn( $instance ) => $instance instanceof FieldValueInterface );

		$value_class_array = array_filter(
			$field_values,
			function( $instance ) use ( $field ) {
				return $instance instanceof AbstractObject && $instance instanceof FieldValueInterface && $instance::$type === $field::$type . 'Value';
			}
		);

		return $value_class_array ? array_values( $value_class_array )[0] : null;
	}
}
