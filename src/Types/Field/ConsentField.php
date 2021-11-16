<?php
/**
 * GraphQL Object Type - ConsentField
 *
 * @see https://docs.gravityforms.com/gf_field_consent/
 *
 * @package WPGraphQL\GF\Types\Field
 * @since   0.3.0
 */

namespace WPGraphQL\GF\Types\Field;

use WPGraphQL\GF\Types\Field\FieldProperty;

/**
 * Class - ConsentField
 */
class ConsentField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ConsentField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'consent';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms Consent field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'checkboxLabel' => [
					'type'        => 'String',
					'description' => __( 'Text of the consent checkbox', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
