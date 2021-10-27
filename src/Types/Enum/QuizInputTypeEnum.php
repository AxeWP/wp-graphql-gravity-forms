<?php
/**
 * Enum Type - QuizInputTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.9.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - QuizInputTypeEnum
 */
class QuizInputTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'QuizInputTypeEnum';

	// Individual elements.
	const CHECKBOX = 'checkbox';
	const RADIO    = 'radio';
	const SELECT   = 'select';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The Gravity Forms field type used to display the current Quiz Field', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'CHECKBOX' => [
				'description' => __( 'Gravity Forms `CheckboxField`. ', 'wp-graphql-gravity-forms' ),
				'value'       => self::CHECKBOX,
			],
			'RADIO'    => [
				'description' => __( 'Gravity Forms `RadioField`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RADIO,
			],
			'SELECT'   => [
				'description' => __( 'Gravity Forms `SelectField`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SELECT,
			],
		];
	}
}
