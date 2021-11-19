<?php
/**
 * GraphQL Object Type - HtmlField
 *
 * @see https://docs.gravityforms.com/gf_field_html/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - HtmlField
 */
class HtmlField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'HtmlField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'html';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms HTML field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			static::get_global_properties(),
			static::get_custom_properties(),
			FieldProperty\DisplayOnlyProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
			[
				'content'        => [
					'type'        => 'String',
					'description' => __( 'Content of an HTML block field to be displayed on the form.', 'wp-graphql-gravity-forms' ),
				],
				'disableMargins' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the default margins are turned on to align the HTML content with other fields.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
