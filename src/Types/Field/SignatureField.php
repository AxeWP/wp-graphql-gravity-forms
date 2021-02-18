<?php
/**
 * GraphQL Object Type - SignatureField
 *
 * @see https://www.gravityforms.com/add-ons/signature/
 * @see https://docs.gravityforms.com/category/add-ons-gravity-forms/signature-add-on/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - SignatureField
 */
class SignatureField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'SignatureField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'signature';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}


	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms Signature field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'penSize'         => [
							'type'        => 'Integer',
							'description' => __( 'Size of the pen cursor.', 'wp-graphql-gravity-forms' ),
						],
						'boxWidth'        => [
							'type'        => 'Integer',
							'description' => __( 'Width of the signature field in pixels.', 'wp-graphql-gravity-forms' ),
						],
						// @TODO: Convert to an enum.
						'borderWidth'     => [
							'type'        => 'String',
							'description' => __( 'Width of the border around the signature area. Possible values are: 0 (none), 1 (small), 2 (medium) or 3 (large).', 'wp-graphql-gravity-forms' ),
						],
						'backgroundColor' => [
							'type'        => 'String',
							'description' => __( 'Color to be used for the background of the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
						],
						'borderColor'     => [
							'type'        => 'String',
							'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
						],
						// @TODO: Convert to an enum.
						'borderStyle'     => [
							'type'        => 'String',
							'description' => __( 'Border style to be used around the signature area. Possible values: dotted, dashed, groove, ridge, inset, outset, double, solid.', 'wp-graphql-gravity-forms' ),
						],
						'penColor'        => [
							'type'        => 'String',
							'description' => __( 'Color of the pen to be used for the signature. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
