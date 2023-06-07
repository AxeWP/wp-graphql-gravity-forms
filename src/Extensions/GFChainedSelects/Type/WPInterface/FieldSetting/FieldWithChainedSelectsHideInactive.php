<?php
/**
 * GraphQL Interface for a FormField with the `chained_selects_hide_inactive_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithChainedSelectsHideInactive
 */
class FieldWithChainedSelectsHideInactive extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedSelectsHideInactiveSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_selects_hide_inactive_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'shouldHideInactiveChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether inactive dropdowns should be hidden.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source->chainedSelectsHideInactive ),
			],
		];
	}
}
