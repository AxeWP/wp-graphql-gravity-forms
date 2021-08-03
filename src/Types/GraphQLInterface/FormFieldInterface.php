<?php
/**
 * Interface - Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Interface
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\GraphQLInterface;

use WPGraphQL\Registry\TypeRegistry;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\WPGraphQLGravityForms;
use WPGraphQLGravityForms\Types\ConditionalLogic\ConditionalLogic;
use WPGraphQLGravityForms\Types\Field\AbstractFormField;

/**
 * Class - FormFieldInterface
 */
class FormFieldInterface implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormField';

	/**
	 * {@inheritDoc}
	 *
	 * @var boolean
	 */
	public static $should_load_eagerly = false;
	/**
	 * WPGraphQL for Gravity Forms plugin's class instances.
	 *
	 * @var array
	 */
	private $instances;

	/**
	 * {@inheritDoc}.
	 */
	public function register_hooks() : void {
		add_action( get_graphql_register_action(), [ $this, 'register_type' ] );
	}

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms field', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public static function get_type_fields() : array {
		return [
			'conditionalLogic'           => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls the visibility of the field based on values selected by the user.', 'wp-graphql-gravity-forms' ),
			],
			'cssClass'                   => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
			],
			'cssClassList'               => [
				'type'              => [ 'list_of' => 'String' ],
				'description'       => __( 'Array of the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `cssClass` instead.', 'wp-graphql-gravity-forms' ),
			],
			'formId'                     => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The ID of the form this field belongs to.', 'wp-graphql-gravity-forms' ),
			],
			'id'                         => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
			],
			'layoutGridColumnSpan'       => [
				'type'        => 'Int',
				'description' => __( 'The number of CSS grid columns the field should span.', 'wp-graphql-gravity-forms' ),
			],
			'layoutSpacerGridColumnSpan' => [
				'type'        => 'Int',
				'description' => __( 'The number of CSS grid columns the spacer field following this one should span.', 'wp-graphql-gravity-forms' ),
			],
			'pageNumber'                 => [
				'type'        => 'Integer',
				'description' => __( 'The form page this field is located on. Default is 1.', 'wp-graphql-gravity-forms' ),
			],
			'type'                       => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'The type of field to be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}


	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public function register_type( TypeRegistry $type_registry ) : void {
		register_graphql_interface_type(
			self::$type,
			$this->get_type_config(
				[
					'description'     => $this->get_type_description(),
					'fields'          => self::get_type_fields(),
					'resolveType'     => function( $value ) use ( $type_registry ) {
						$possible_types = $this->get_registered_form_field_types();
						if ( isset( $possible_types[ $value->type ] ) ) {
							return $type_registry->get_type( $possible_types[ $value->type ] );
						}
						throw new UserError(
							sprintf(
							/* translators: %s: GF field type */
								__( 'The "%s" Gravity Forms field type is not (yet) supported by the schema.', 'wp-graphql-gravity-forms' ),
								$value->type
							)
						);
					},
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 *
	 * @return array
	 */
	public function get_type_config( array $config ) : array {
		/**
		 * Deprecated filter for modifying the the type fields.
		 *
		 * @since 0.7.0
		 */
		$config['fields'] = apply_filters_deprecated( 'wp_graphql_gf_global_properties', [ $config['fields'] ], '0.7.0', 'wp_graphql_gf_' . static::$type . '_type_config' );

		/**
		 * Filter for modifying the GraphQL type $config array used to register the type in WPGraphQL.
		 *
		 * @param array  $config The config array.
		 * @param string $type The GraphQL type name.
		 */
		$config = apply_filters( 'wp_graphql_gf_type_config', $config, static::$type );
		$config = apply_filters( 'wp_graphql_gf_' . static::$type . '_type_config', $config );

		return $config;
	}

	/**
	 * Returns Gravity Forms Field types to be exposed to the GraphQL schema.
	 *
	 * @return array field types.
	 */
	public function get_registered_form_field_types() : array {
		$fields = array_filter( WPGraphQLGravityForms::instances(), fn( $instance ) => $instance instanceof AbstractFormField );

		$types = [];

		foreach ( $fields as $field ) {
			$types[ $field::$gf_type ] = $field::$type;
		}

		/**
		 * Filter to add custom Gravity Forms field types to the GraphQL schema.
		 *
		 * @param array $type The field types.
		 */
		$types = apply_filters_deprecated( 'wp_graphql_gf_field_types', [ $types ], '0.7.0', 'wp_graphql_gf_instances', __( 'Please remove your code, and use `wp_graphql_gf_instances` hook to register your custom fields instead', 'wp-graphql-gravity-forms' ) );

		return $types;
	}

}
