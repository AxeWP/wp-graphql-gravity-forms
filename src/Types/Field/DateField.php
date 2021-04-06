<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_date/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Enum\CalendarIconTypeEnum;
use WPGraphQLGravityForms\Types\Enum\DateFieldFormatEnum;
use WPGraphQLGravityForms\Types\Enum\DateTypeEnum;
use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - DateField
 */
class DateField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'DateField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'date';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Date field.', 'wp-graphql-gravity-forms' );
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
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\InputsProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
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
			]
		);
	}

}
