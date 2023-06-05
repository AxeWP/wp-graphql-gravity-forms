<?php
/**
 * GraphQL Interface for a FormField with the `columns_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldChoiceSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting;

use WPGraphQL\GF\Type\WPInterface\FieldChoice;
use WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting\AbstractFieldChoiceSetting;

/**
 * Class - ChoiceWithColumns
 */
class ChoiceWithColumns extends AbstractFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithColumnsSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'columns_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		// All types need to have fields, so we explicitly list the interface fields.
		return FieldChoice::get_fields();
	}
}
