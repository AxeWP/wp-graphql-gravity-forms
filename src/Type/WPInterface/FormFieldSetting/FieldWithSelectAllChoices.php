<?php
/**
 * GraphQL Interface for a FormField with the `select_all_choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithSelectAllChoices
 */
class FieldWithSelectAllChoices extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSelectAllChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'select_all_choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the \"select all\" choice should be displayed.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->enableSelectAll ),
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
