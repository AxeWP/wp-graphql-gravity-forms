<?php
/**
 * GraphQL Object Type - SectionField
 *
 * @see https://docs.gravityforms.com/gf_field_section/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;
/**
 * Class - SectionField
 */
class SectionField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SectionField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'section';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Section field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			static::get_fields_from_gf_settings(),
		);
	}
}
