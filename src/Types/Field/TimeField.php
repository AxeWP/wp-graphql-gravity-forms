<?php
/**
 * GraphQL Object Type - TimeField
 *
 * @see https://docs.gravityforms.com/gf_field_time/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Enum\TimeFieldFormatEnum;
use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - TimeField
 */
class TimeField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'TimeField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'time';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Time field.', 'wp-graphql-gravity-forms' );
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
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\InputsProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'timeFormat' => [
					'type'        => TimeFieldFormatEnum::$type,
					'description' => __( 'Determines how the time is displayed.', 'wp-graphql-gravity-forms' ),
				],
			]
		);
	}
}
