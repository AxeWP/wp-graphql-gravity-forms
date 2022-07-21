<?php
/**
 * GraphQL Interface for a FormField with the `date_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\Enum\DateFieldFormatEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithDateFormat
 */
class FieldWithDateFormat extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDateFormat';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'date_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'dateFormat' => [
				'type'        => DateFieldFormatEnum::$type,
				'description' => __( 'Determines how the date is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
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
	}
}
