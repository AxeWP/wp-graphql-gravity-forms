<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_date/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\CalendarIconTypeEnum;
use WPGraphQL\GF\Type\Enum\DateFieldFormatEnum;
use WPGraphQL\GF\Type\Enum\DateTypeEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - DateField
 */
class DateField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'DateField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'date';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Date field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'calendarIconType' => [
					'type'        => CalendarIconTypeEnum::$type,
					'description' => __( 'Determines how the date field displays it’s calendar icon.', 'wp-graphql-gravity-forms' ),
				],
				'calendarIconUrl'  => [
					'type'        => 'String',
					'description' => __( 'Contains the URL to the custom calendar icon. Only applicable when calendarIconType is set to custom.', 'wp-graphql-gravity-forms' ),
				],
				'dateFormat'       => [
					'type'        => DateFieldFormatEnum::$type,
					'description' => __( 'Determines how the date is displayed.', 'wp-graphql-gravity-forms' ),
				],
				'dateType'         => [
					'type'        => DateTypeEnum::$type,
					'description' => __( 'The type of date field to display.', 'wp-graphql-gravity-forms' ),
				],
				'inputs'           => [
					'type'        => [ 'list_of' => FieldProperty\DateInputProperty::$type ],
					'description' => __( 'An array containing the the individual properties for each element of the date field.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}

}
