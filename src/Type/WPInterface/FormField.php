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
use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;
use WPGraphQL\GF\Type\Enum\FormFieldVisibilityEnum;
use WPGraphQL\GF\Type\WPInterface\AbstractInterface;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormField
 */
class FormField extends AbstractInterface {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormField';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );

		if ( null !== $type_registry ) {
			$config['resolveType'] = static::resolve_type( $type_registry );
		}

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'displayOnly'                => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the field is only displayed and its contents are not submitted with the form/saved with the entry. This is set to true.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): bool => ! empty( $source->displayOnly ),
			],
			'id'                         => [
				'type'              => [ 'non_null' => 'Int' ],
				'description'       => __( 'Field database ID.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'This field will be changing to return a Global ID in a future release. Future-proof your code and use databaseId instead.', 'wp-graphql-gravity-forms' ),
			],
			'databaseId'                 => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'Field database ID.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): int => absint( $source->id ),
			],
			'inputType'                  => [
				'type'        => FormFieldTypeEnum::$type,
				'description' => __( 'The base form field type used to display the input. A good example is the Post Custom Field that can be displayed as various different types of fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->inputType ) ? $source->inputType : null,
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
				'resolve'     => static fn ( $source ): string => ! empty( $source->visibility ) ? $source->visibility : ( ! empty( $source->adminOnly ) ? 'administrative' : 'visible' ),
			],
		];
	}

	/**
	 * Resolves the interface to the GraphQL type.
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry The WPGraphQL type registry.
	 */
	public static function resolve_type( TypeRegistry $type_registry ): callable {
		return static function ( $value ) use ( $type_registry ) {
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
		};
	}
}
