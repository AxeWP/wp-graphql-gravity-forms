<?php
/**
 * GraphQL Interface for a FormField with the `copy_values_option` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithCopyValuesOption
 */
class FieldWithCopyValuesOption extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCopyValuesOption';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'copy_values_option';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'shouldCopyValuesOption'  => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the copy values option can be used. This option allows users to skip filling out the field and use the same values as another. For example, if the mailing and billing address are the same.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableCopyValuesOption ),
			],
			'copyValuesOptionFieldId' => [
				'type'        => 'Int',
				'description' => __( 'The field id of the field being used as the copy source.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->copyValuesOptionField ) ? $source->copyValuesOptionField : null,
			],
			'copyValuesOptionLabel'   => [
				'type'        => 'String',
				'description' => __( 'The label that appears next to the copy values option when the form is displayed. The default value is \“Same as previous\”.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
