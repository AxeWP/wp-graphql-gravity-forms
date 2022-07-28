<?php
/**
 * GraphQL Interface for a FormField with the `rich_text_editor_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithRichTextEditor
 */
class FieldWithRichTextEditor extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithRichTextEditor';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'rich_text_editor_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasRichTextEditor' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field uses the rich text editor interface.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->useRichTextEditor ),
			],
		];
	}
}
