<?php
/**
 * GraphQL Interface for choice on a FormField with the `gquiz-setting-choices` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldChoiceSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPInterface\FieldChoiceSetting;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPInterface\FieldChoiceSetting\AbstractFieldChoiceSetting;

/**
 * Class - ChoiceWithQuizChoices
 */
class ChoiceWithQuizChoices extends AbstractFieldChoiceSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldChoiceWithQuizChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'gquiz-setting-choices';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isCorrect'     => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the choice item is the correct answer.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): bool => ! empty( $source['gquizIsCorrect'] ),
			],
			'weight'        => [
				'type'        => 'Float',
				'description' => __( 'The weighted score awarded for the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( isset( $context->gfField->gquizWeightedScoreEnabled ) && false === $context->gfField->gquizWeightedScoreEnabled ) {
						return isset( $source['gquizIsCorrect'] ) ? (float) $source['gquizIsCorrect'] : 0;
					}

					return is_numeric( $source['gquizWeight'] ) ? (float) $source['gquizWeight'] : null;
				},
			],
			'isOtherChoice' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the radio button item is the “Other” choice.', 'wp-graphql-gravity-forms' ),
			],
			'isSelected'    => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
