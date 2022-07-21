<?php
/**
 * GraphQL Interface for a FormField with the `other_choice_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldChoices;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithOtherChoice
 */
class FieldWithOtherChoice extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithOtherChoice';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'other_choice_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the \'Enable "other" choice\' option is checked in the editor.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableOtherChoice ),
			],
		];
	}
}
