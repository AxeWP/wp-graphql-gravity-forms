<?php
/**
 * GraphQL Object Type - QuizChoiceProperty
 * An individual property for the 'choices' Quiz field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Class - QuizChoiceProperty
 */
class QuizChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'QuizChoiceProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms Quiz field choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
			[
				'isCorrect' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates the choice item is the correct answer.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : ?string {
						return $root['gquizIsCorrect'] ?? null;
					},
				],
				'weight'    => [
					'type'        => 'Float',
					'description' => __( 'The weighted score awarded for the choice.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : ?float {
							return $root['gquizWeight'] ?? null;
					},
				],
			],
		);
	}
}
