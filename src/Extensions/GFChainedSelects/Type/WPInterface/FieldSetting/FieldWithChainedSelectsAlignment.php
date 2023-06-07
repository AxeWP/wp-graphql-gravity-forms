<?php
/**
 * GraphQL Interface for a FormField with the `chained_selects_alignment_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum\ChainedSelectFieldAlignmentEnum;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithChainedSelectsAlignment
 */
class FieldWithChainedSelectsAlignment extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedSelectsAlignmentSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_selects_alignment_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'chainedSelectsAlignment' => [
				'type'        => ChainedSelectFieldAlignmentEnum::$type,
				'description' => __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
