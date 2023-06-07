<?php
/**
 * GraphQL Interface for a FormField with the `gquiz-setting-randomize-quiz-choices` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithQuizRandomizeQuizChoices
 */
class FieldWithQuizRandomizeQuizChoices extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithQuizRandomizeQuizChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-randomize-quiz-choices';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'shouldRandomizeQuizChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to randomize the order in which the answers are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): bool => ! empty( $source->gquizEnableRandomizeQuizChoices ),
			],
		];
	}
}
