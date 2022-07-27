<?php
/**
 * GraphQL Interface for a FormField with the `gquiz-setting-choices` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithQuizChoices
 */
class FieldWithQuizChoices extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithQuizChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-choices';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasWeightedScore' => [
				'type'        => 'Boolean',
				'description' => __( 'If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizWeightedScoreEnabled ),
			],
		];
	}
}
