<?php
/**
 * GraphQL Object Type - TimeField
 *
 * @see https://docs.gravityforms.com/gf_field_time/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\TimeFieldFormatEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - TimeField
 */
class TimeField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'TimeField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'time';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Time field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\InputsProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'timeFormat' => [
					'type'        => TimeFieldFormatEnum::$type,
					'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
				],
			],
			... static::get_fields_from_gf_settings(),
		);
	}
}
