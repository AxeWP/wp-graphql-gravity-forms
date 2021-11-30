<?php
/**
 * Interface - Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\Interface
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use WPGraphQL\Registry\TypeRegistry;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FormField
 */
class FormField extends AbstractType implements TypeWithFields {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormField';

	/**
	 * {@inheritDoc}
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
			static::prepare_config(
				[
					'description'     => self::get_description(),
					'fields'          => self::get_fields(),
					'resolveType'     => function( $value ) use ( $type_registry ) {
						$possible_types = Utils::get_registered_form_field_types();

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
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms field', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'cssClass'                   => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
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
				'type'        => 'Int',
				'description' => __( 'The form page this field is located on. Default is 1.', 'wp-graphql-gravity-forms' ),
			],
			'type'                       => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'The type of field to be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

}
