<?php
/**
 * GraphQL Object Type - ConsentField
 *
 * @see https://docs.gravityforms.com/gf_field_consent/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.3.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - ConsentField
 */
class ConsentField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConsentField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'consent';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Consent field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\DescriptionPlacementProperty::get(),
			[
				'checkboxLabel' => [
					'type'        => 'String',
					'description' => __( 'Text of the consent checkbox', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}
}
