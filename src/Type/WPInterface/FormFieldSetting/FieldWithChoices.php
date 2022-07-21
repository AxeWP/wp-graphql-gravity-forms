<?php
/**
 * GraphQL Interface for a FormField with the `choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldChoices;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithChoices
 */
class FieldWithChoices extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableChoiceValue ),
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
		FormFieldChoices::register( $field, $settings, $registry );
	}
}
