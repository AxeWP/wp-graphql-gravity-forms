<?php
/**
 * Connection - EntryField
 *
 * Registers connections from GravityFormsEntry.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Field\AbstractField;
use WPGraphQLGravityForms\Types\Field\FieldValue\AbstractFieldValue;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;
use WPGraphQLGravityForms\Types\Union\ObjectFieldValueUnion;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - EntryFieldConnection.
 */
class EntryFieldConnection implements Hookable, Connection {
	/**
	 * WPGraphQL for Gravity Forms plugin's class instances.
	 *
	 * @var array
	 */
	private $instances;

	/**
	 * Constructor.
	 *
	 * @param array $instances WPGraphQL for Gravity Forms plugin's class instances.
	 */
	public function __construct( array $instances ) {
		$this->instances = $instances;
	}

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'init', [ $this, 'register_connection' ] );
	}

	/**
	 * Register connection from GravityFormsEntry type to other types.
	 */
	public function register_connection() : void {
		register_graphql_connection(
			[
				'fromType'      => Entry::TYPE,
				'toType'        => FormFieldInterface::TYPE,
				'fromFieldName' => 'formFields',
				'edgeFields'    => [
					'fieldValue' => [
						'type'        => ObjectFieldValueUnion::TYPE,
						'description' => __( 'Field value.', 'wp-graphql-gravity-forms' ),
						'resolve'     => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
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
				],
				'resolve'       => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
					$form = GFUtils::get_form( $root['formId'], false );

					$fields     = ( new FieldsDataManipulator() )->manipulate( $form['fields'] );
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

					$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
					$connection['nodes'] = $nodes ?: null;
					return $connection;
				},
			]
		);

		/**
		 * Deprecated `fields`.
		 *
		 * @since 0.4.0
		 */
		register_graphql_connection(
			[
				'deprecationReason' => __( 'Deprecated in favor of `formFields`.', 'wp-graphql-gravity-forms' ),
				'fromType'          => Entry::TYPE,
				'toType'            => FormFieldInterface::TYPE,
				'fromFieldName'     => 'fields',
				'edgeFields'        => [
					'fieldValue' => [
						'type'        => ObjectFieldValueUnion::TYPE,
						'description' => __( 'Field value.', 'wp-graphql-gravity-forms' ),
						'resolve'     => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
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
				],
				'resolve'           => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
					$form = GFUtils::get_form( $root['formId'], false );

					$fields     = ( new FieldsDataManipulator() )->manipulate( $form['fields'] );
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

					$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
					$connection['nodes'] = $nodes ?: null;
					return $connection;
				},
			]
		);
	}

	/**
	 * Get the WPGraphQL field for a Gravity Forms field type.
	 *
	 * @param string $gf_field_type The Gravity Forms field type.
	 *
	 * @return AbstractField|null The corresponding WPGraphQL field, or null if not found.
	 */
	private function get_field_by_gf_field_type( string $gf_field_type ) {
		$fields = array_filter( $this->instances, fn( $instance ) => $instance instanceof AbstractField );

		/**
		 * Filter for adding custom field class instances.
		 * Classes must extend the WPGraphQLGravityForms\Types\Field\AbstractField class and
		 * contain a "$gf_type" class variable specifying the Gravity Forms field type.
		 *
		 * @param array $fields Gravity Forms field class instances.
		 */
		$fields = apply_filters( 'wp_graphql_gf_form_field_instances', $fields );

		$field_array = array_filter(
			$fields,
			function( $instance ) use ( $gf_field_type ) {
				return $instance instanceof AbstractField && $instance::$gf_type === $gf_field_type;
			}
		);

		return $field_array ? array_values( $field_array )[0] : null;
	}

	/**
	 * Get the field value class associated with a form field.
	 *
	 * @param  AbstractField $field The field class.
	 *
	 * @return string|AbstractFieldValue|null The field value class or null if not found.
	 */
	private function get_field_value_class( AbstractField $field ) {
		$field_values = array_filter( $this->instances, fn( $instance ) => $instance instanceof AbstractFieldValue );

		/**
		 * Filter for adding custom field value class instances.
		 * Classes must implement the WPGraphQLGravityForms\Interfaces\FieldValue interface
		 * and contain a "TYPE" class constant string in this format: "<field_name>Value".
		 *
		 * @param array $field_values Field value class instances.
		 */
		$field_values = apply_filters( 'wp_graphql_gf_field_value_instances', $field_values );

		$value_class_array = array_filter(
			$field_values,
			function( $instance ) use ( $field ) {
				return $instance instanceof AbstractFieldValue && $instance::$type === $field::$type . 'Value';
			}
		);

		return $value_class_array ? array_values( $value_class_array )[0] : null;
	}
}
