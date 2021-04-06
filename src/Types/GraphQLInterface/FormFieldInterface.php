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

use GraphQL\Error\UserError;
use WPGraphQL\Registry\TypeRegistry;
use WPGraphQLGravityForms\WPGraphQLGravityForms;
use WPGraphQLGravityForms\Types\ConditionalLogic\ConditionalLogic;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
/**
 * Class - FormFieldInterface
 */
class FormFieldInterface implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'FormField';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry  Instance of the WPGraphQL TypeRegistry.
	 */
	public function register_type( TypeRegistry $type_registry ) : void {
		register_graphql_interface_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms field', 'wp-graphql-gravity-forms' ),
				'fields'      => self::get_properties(),
				'resolveType' => function( $value ) use ( $type_registry ) {
					$possible_types = WPGraphQLGravityForms::get_enabled_field_types();
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
			],
		);
	}

	/**
	 * Defines Gravity Forms field properties. All child types must have these fields as well.
	 *
	 * @return array
	 */
	public static function get_properties() : array {
		return apply_filters(
			'wp_graphql_gf_global_properties',
			[
				'conditionalLogic' => [
					'type'        => ConditionalLogic::TYPE,
					'description' => __( 'Controls the visibility of the field based on values selected by the user.', 'wp-graphql-gravity-forms' ),
				],
				'cssClass'         => [
					'type'        => 'String',
					'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
				],
				'cssClassList'     => [
					'type'              => [ 'list_of' => 'String' ],
					'description'       => __( 'Array of the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
					'deprecationReason' => __( 'Please use `cssClass` instead.', 'wp-graphql-gravity-forms' ),
				],
				'formId'           => [
					'type'        => [ 'non_null' => 'Int' ],
					'description' => __( 'The ID of the form this field belongs to.', 'wp-graphql-gravity-forms' ),
				],
				'id'               => [
					'type'        => [ 'non_null' => 'Int' ],
					'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
				],
				'type'             => [
					'type'        => [ 'non_null' => 'String' ],
					'description' => __( 'The type of field to be displayed.', 'wp-graphql-gravity-forms' ),
				],
			]
		);
	}
}
