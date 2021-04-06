<?php
/**
 * GraphQL Object Type - SignatureField
 *
 * @see https://www.gravityforms.com/add-ons/signature/
 * @see https://docs.gravityforms.com/category/add-ons-gravity-forms/signature-add-on/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Enum\SignatureBorderStyleEnum;
use WPGraphQLGravityForms\Types\Enum\SignatureBorderWidthEnum;
use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - SignatureField
 */
class SignatureField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SignatureField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'signature';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Signature field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
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
				'borderWidth'     => [
					'type'        => SignatureBorderWidthEnum::$type,
					'description' => __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' ),
				],
				'backgroundColor' => [
					'type'        => 'String',
					'description' => __( 'Color to be used for the background of the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
				],
				'borderColor'     => [
					'type'        => 'String',
					'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
				],
				'borderStyle'     => [
					'type'        => SignatureBorderStyleEnum::$type,
					'description' => __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' ),
				],
				'penColor'        => [
					'type'        => 'String',
					'description' => __( 'Color of the pen to be used for the signature. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
				],
			]
		);
	}
}
