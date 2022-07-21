<?php
/**
 * GraphQL Interface for a FormField with the `name_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldChoices;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithName
 */
class FieldWithName extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithName';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'name_setting';

	/**
	 * {@inheritDoc}
	 *
	 * There is no Field interface for the setting.
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [];
	}

	/**
	 * Register GraphQL fields to the FormField objects that implement this interface.
	 *
	 * @param GF_Field     $field The Gravity forms field.
	 * @param array        $settings The GF settings for the field.
	 * @param TypeRegistry $registry The WPGraphQL type registry.
	 */
	public static function register_object_fields( GF_Field $field, array $settings, TypeRegistry $registry ) : void {
		// Register the InputProperty for the object.
		FormFieldInputs::register( $field, $settings, $registry );
		// Register the FieldChoice for the object.
		FormFieldChoices::register( $field, $settings, $registry );
	}
}
