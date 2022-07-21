<?php
/**
 * GraphQL Interface for a FormField with the `gquiz-setting-randomize-quiz-choices` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithQuizRandomizeQuizChoices
 */
class FieldWithQuizRandomizeQuizChoices extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithQuizRandomizeQuizChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-randomize-quiz-choices';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'shouldRandomizeQuizChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to randomize the order in which the answers are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizEnableRandomizeQuizChoices ),
			],
		];
	}
}
