<?php
/**
 * GraphQL Interface for a FormField with the `date_input_type_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\DateFieldTypeEnum;
use WPGraphQL\GF\Type\Enum\FormFieldCalendarIconTypeEnum;

/**
 * Class - FieldWithDateInputType
 */
class FieldWithDateInputType extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDateInputTypeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'date_input_type_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'calendarIconType' => [
				'type'        => FormFieldCalendarIconTypeEnum::$type,
				'description' => static fn () => __( 'Determines how the date field displays it’s calendar icon.', 'wp-graphql-gravity-forms' ),
			],
			'calendarIconUrl'  => [
				'type'        => 'String',
				'description' => static fn () => __( 'Contains the URL to the custom calendar icon. Only applicable when calendarIconType is set to custom.', 'wp-graphql-gravity-forms' ),
			],
			'dateType'         => [
				'type'        => DateFieldTypeEnum::$type,
				'description' => static fn () => __( 'The type of date field to display.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
