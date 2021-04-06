<?php
/**
 * GraphQL Object Type - EmailField
 *
 * @see https://docs.gravityforms.com/gf_field_email/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - EmailField
 */
class EmailField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EmailField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'email';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Email field.', 'wp-graphql-gravity-forms' );
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
				'emailConfirmEnabled' => [
					'type'        => 'Boolean',
					'description' => __( 'Determines whether the Confirm Email field is active.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
