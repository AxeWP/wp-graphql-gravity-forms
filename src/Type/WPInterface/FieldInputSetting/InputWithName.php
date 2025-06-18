<?php
/**
 * GraphQL Interface for a FormField with the `name_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldInputSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FieldInputSetting\AbstractFieldInputSetting;

/**
 * Class - InputWithName
 */
class InputWithName extends AbstractFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithNameSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'name_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'autocompleteAttribute' => [
				'type'        => 'String',
				'description' => static fn () => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
			],
			'defaultValue'          => [
				'type'        => 'String',
				'description' => static fn () => __( 'Contains the default value for the field. When specified, the field\'s value will be populated with the contents of this property when the form is displayed.', 'wp-graphql-gravity-forms' ),
			],
			'hasChoiceValue'        => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->enableChoiceValue ),
			],
			'customLabel'           => [
				'type'        => 'String',
				'description' => static fn () => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
			'isHidden'              => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
			'key'                   => [
				'type'        => 'String',
				'description' => static fn () => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
			],
			'name'                  => [
				'type'        => 'String',
				'description' => static fn () => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
			'placeholder'           => [
				'type'        => 'String',
				'description' => static fn () => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
