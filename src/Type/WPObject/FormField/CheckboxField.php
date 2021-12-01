<?php
/**
 * GraphQL Object Type - CheckboxField
 *
 * @see https://docs.gravityforms.com/gf_field_checkbox/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - CheckboxField
 */
class CheckboxField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'checkbox';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Checkbox field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnablePriceProperty::get(),
			FieldProperty\EnableSelectAllProperty::get(),
			[
				'inputs' => [
					'type'        => [ 'list_of' => FieldProperty\CheckboxInputProperty::$type ],
					'description' => __( 'List of inputs. Checkboxes are treated as multi-input fields, since each checkbox item is stored separately.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}
}
