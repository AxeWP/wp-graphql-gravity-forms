<?php
/**
 * GraphQL Object Type - PostCustomField
 *
 * @see https://docs.gravityforms.com/post-custom/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - PostCustomField
 */
class PostCustomField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PostCustomField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'post_custom_field';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Post Custom Field field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\InputTypeProperty::get(),
			FieldProperty\MaxLengthProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'postCustomFieldName' => [
					'type'        => 'String',
					'description' => __( 'The name of the Post Custom Field that the submitted value should be assigned to.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}
}
