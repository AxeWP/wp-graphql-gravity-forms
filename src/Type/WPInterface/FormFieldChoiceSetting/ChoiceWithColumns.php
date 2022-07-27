<?php
/**
 * GraphQL Interface for a FormField with the `columns_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldChoiceSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldChoiceSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPInterface\FormFieldChoice;
use WPGraphQL\GF\Type\WPInterface\FormFieldChoiceSetting\AbstractFormFieldChoiceSetting;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - ChoiceWithColumns
 */
class ChoiceWithColumns extends AbstractFormFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithColumns';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'columns_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return FormFieldChoice::get_fields();
	}
}
