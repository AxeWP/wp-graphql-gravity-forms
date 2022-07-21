<?php
/**
 * GraphQL Interface for a FormField with the `chained_selects_alignment_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum\ChainedSelectFieldAlignmentEnum;
use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithChainedSelectsAlignment
 */
class FieldWithChainedSelectsAlignment extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedSelectsAlignment';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_selects_alignment_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'chainedSelectsAlignment' => [
				'type'        => ChainedSelectFieldAlignmentEnum::$type,
				'description' => __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
