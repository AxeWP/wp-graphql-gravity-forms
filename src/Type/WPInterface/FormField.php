<?php
/**
 * Interface - Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;
use WPGraphQL\GF\Type\Enum\FormFieldVisibilityEnum;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormField
 */
class FormField implements Registrable, Type, TypeWithFields {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormField';

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// Bail early if no type registry.
		if ( null === $type_registry ) {
			return;
		}

		register_graphql_interface_type(
			static::$type,
			[
				'description'     => self::get_description(),
				'fields'          => self::get_fields(),
				'resolveType'     => function( $value ) use ( $type_registry ) {
					$possible_types    = Utils::get_registered_form_field_types();
					$possible_subtypes = Utils::get_possible_form_field_child_types( $value->type );

					if ( isset( $possible_subtypes[ $value->inputType ] ) ) {
						return $possible_subtypes[ $value->inputType ];
					}

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
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'displayOnly'                => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the field is only displayed and its contents are not submitted with the form/saved with the entry. This is set to true.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->displayOnly ),
			],
			'id'                         => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
			],
			'inputType'                  => [
				'type'        => FormFieldTypeEnum::$type,
				'description' => __( 'The base form field type used to display the input. A good example is the Post Custom Field that can be displayed as various different types of fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->inputType ) ? $source->inputType : null,
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
				'type'        => 'Int',
				'description' => __( 'The form page this field is located on. Default is 1.', 'wp-graphql-gravity-forms' ),
			],
			// @todo make non-null once gatsby-source-wordpress supports it: https://github.com/gatsbyjs/gatsby/issues/34489 .
			'type'                       => [
				'type'        => FormFieldTypeEnum::$type,
				'description' => __( 'The type of field to be displayed.', 'wp-graphql-gravity-forms' ),
			],
			'visibility'                 => [
				'type'        => FormFieldVisibilityEnum::$type,
				'description' => __( 'Field visibility.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : string => ! empty( $source->visibility ) ? $source->visibility : ( ! empty( $source->adminOnly ) ? 'administrative' : 'visible' ),
			],
		];
	}
}
