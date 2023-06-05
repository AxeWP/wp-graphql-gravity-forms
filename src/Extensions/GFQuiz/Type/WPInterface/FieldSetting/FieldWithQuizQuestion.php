<?php
/**
 * GraphQL Interface for a FormField with the `gquiz-setting-question` setting.
 *
 * It's an alias of FieldWithLabel, to make it easier for the targeted addition of GraphQL fields.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\FieldWithLabel;

/**
 * Class - FieldWithQuizQuestion
 */
class FieldWithQuizQuestion extends FieldWithLabel {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithQuizQuestionSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-question';
}
