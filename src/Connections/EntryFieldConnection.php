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
use WPGraphQLGravityForms\Interfaces\FieldValue as FieldValueInterface;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Field\AbstractFormField;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;
use WPGraphQLGravityForms\Types\Union\ObjectFieldValueUnion;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - EntryFieldConnection.
 */
class EntryFieldConnection extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'formFields';

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
	 * GraphQL Connection from type.
	 */
	public function get_connection_from_type() : string {
		return Entry::$type;
	}

	/**
	 * GraphQL Connection to type.
	 */
	public function get_connection_to_type() : string {
		return FormFieldInterface::$type;
	}


	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public function get_connection_config_args() : array {
		return [
			'edgeFields' => [
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
			],
			'resolve'    => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
				$form = GFUtils::get_form( $root['formId'], false );

				$fields = ( new FieldsDataManipulator() )->manipulate( $form['fields'] );

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
		];
	}

	/**
	 * Get the WPGraphQL field for a Gravity Forms field type.
	 *
	 * @param string $gf_field_type The Gravity Forms field type.
	 *
	 * @return AbstractFormField|null The corresponding WPGraphQL field, or null if not found.
	 */
	private function get_field_by_gf_field_type( string $gf_field_type ) {
		$fields = array_filter( $this->instances, fn( $instance ) => $instance instanceof AbstractFormField );

		/**
		 * Filter for adding custom field class instances.
		 * Classes must extend the WPGraphQLGravityForms\Types\Field\AbstractFormField class and
		 * contain a "$gf_type" class variable specifying the Gravity Forms field type.
		 *
		 * @param array $fields Gravity Forms field class instances.
		 */
		$fields = apply_filters( 'wp_graphql_gf_form_field_instances', $fields );

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
	 * @return string|FieldValueInterface|null The field value class or null if not found.
	 */
	private function get_field_value_class( AbstractFormField $field ) {
		$field_values = array_filter( $this->instances, fn( $instance ) => $instance instanceof FieldValueInterface );

		$value_class_array = array_filter(
			$field_values,
			function( $instance ) use ( $field ) {
				return $instance instanceof AbstractObject && $instance instanceof FieldValueInterface && $instance::$type === $field::$type . 'Value';
			}
		);

		return $value_class_array ? array_values( $value_class_array )[0] : null;
	}
}
