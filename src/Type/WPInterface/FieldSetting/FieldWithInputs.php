<?php
/**
 * GraphQL Interface for a FormField with the `choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FormField;

/**
 * Class - FieldWithInputs
 */
class FieldWithInputs extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithInputsSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		// Interfaces cant be empty, so lets use the ID field.
		$fields = FormField::get_fields();

		return [ 'id' => $fields['id'] ];
	}
}
