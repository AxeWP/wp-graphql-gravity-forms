<?php
/**
 * GraphQL Interface for a FormField with the `chained_selects_hide_inactive_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithChainedSelectsHideInactive
 */
class FieldWithChainedSelectsHideInactive extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithChainedSelectsHideInactive';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_selects_hide_inactive_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'shouldHideInactiveChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether inactive dropdowns should be hidden.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->chainedSelectsHideInactive ),
			],
		];
	}
}
