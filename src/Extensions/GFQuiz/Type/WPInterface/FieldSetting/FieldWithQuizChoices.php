<?php
/**
 * GraphQL Interface for a FormField with the `gquiz-setting-choices` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithQuizChoices
 */
class FieldWithQuizChoices extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithQuizChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-choices';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_filter( 'graphql_gf_form_field_settings_with_choices', [ self::class, 'add_setting' ], 10 );

		parent::register_hooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasWeightedScore' => [
				'type'        => 'Boolean',
				'description' => __( 'If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): bool => ! empty( $source->gquizWeightedScoreEnabled ),
			],
		];
	}

	/**
	 * Adds the `chained_choices_setting` setting to the list of settings that have the GraphQL choices field.
	 *
	 * @param array $settings the GF Field settings.
	 */
	public static function add_setting( array $settings ): array {
		if ( ! in_array( self::$field_setting, $settings, true ) ) {
			$settings[] = self::$field_setting;
		}

		return $settings;
	}
}
