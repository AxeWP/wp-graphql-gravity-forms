<?php
/**
 * Adds support for GFQuiz.
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz;

use GF_Field;
use WPGraphQL\GF\Extensions\GFQuiz\Type\Enum;
use WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject;
use WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\FormField\FieldProperty\PropertyMapper;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - GFQuiz
 */
class GFQuiz {
	/**
	 * Hook extension into plugin.
	 */
	public static function register_hooks() : void {
		if ( ! self::is_gf_quiz_enabled() ) {
			return;
		}

		// Register Enums.
		add_filter( 'graphql_gf_registered_enum_classes', [ __CLASS__, 'enums' ] );
		// Register Objects.
		add_filter( 'graphql_gf_registered_object_classes', [ __CLASS__, 'objects' ] );

		// Register Child Field types.
		add_filter( 'graphql_gf_form_field_child_types', [ __CLASS__, 'field_child_types' ], 10, 2 );

		// Add quiz to Form Model.
		add_filter( 'graphql_model_prepare_fields', [ __CLASS__, 'form_model' ], 10, 3 );

		// // Register field_settings.
		add_filter( 'graphql_gf_form_field_setting_properties', [ __CLASS__, 'field_setting_properties' ], 10, 3 );
	}

	/**
	 * Returns whether Gravity Forms Quiz is enabled.
	 *
	 * @return boolean
	 */
	public static function is_gf_quiz_enabled() : bool {
		return class_exists( 'GFQuiz' );
	}

	/**
	 * Register enum classes.
	 *
	 * @param array $classes .
	 */
	public static function enums( array $classes ) : array {
		$classes[] = Enum\QuizFieldGradingTypeEnum::class;
		$classes[] = Enum\QuizFieldTypeEnum::class;

		return $classes;
	}

	/**
	 * Registers Signature field settings mapper.
	 *
	 * @param array    $properties .
	 * @param string   $setting .
	 * @param GF_Field $field .
	 */
	public static function field_setting_properties( array $properties, string $setting, GF_Field $field ) : array {
		if ( method_exists( PropertyMapper::class, $setting ) ) {
			PropertyMapper::$setting( $field, $properties );
		}

		return $properties;
	}

	/**
	 * Sets the Form Field child types.
	 *
	 * @param array  $child_types An array of GF_Field::$type => GraphQL type names.
	 * @param string $field_type The 'parent' GF_Field type.
	 */
	public static function field_child_types( array $child_types, string $field_type ) : array {
		if ( 'quiz' === $field_type ) {
			$prefix = Utils::get_safe_form_field_type_name( $field_type );

			$child_types = [
				'checkbox' => $prefix . 'CheckboxField',
				'radio'    => $prefix . 'RadioField',
				'select'   => $prefix . 'SelectField',
			];
		}

		return $child_types;
	}

	/**
	 * Adds quiz to model
	 *
	 * @param array  $fields .
	 * @param string $model_name .
	 * @param array  $data .
	 */
	public static function form_model( $fields, string $model_name, $data ) : array {
		if ( 'FormObject' === $model_name ) {
			$fields['quiz'] = fn() : ?array => ! empty( $data['gravityformsquiz'] ) ? $data['gravityformsquiz'] : null;
		}

		return $fields;
	}

	/**
	 * Register object classes.
	 *
	 * @param array $classes .
	 */
	public static function objects( array $classes ) : array {
		$classes[] = WPObject\Entry\EntryQuizResults::class;
		$classes[] = WPObject\Form\FormQuizGrades::class;
		$classes[] = WPObject\Form\FormQuizConfirmation::class;
		$classes[] = WPObject\Form\FormQuiz::class;
		$classes[] = WPObject\QuizResults\QuizResultsGradeCount::class;
		$classes[] = WPObject\QuizResults\QuizResultsScoreCount::class;
		$classes[] = WPObject\QuizResults\QuizResultsChoiceCount::class;
		$classes[] = WPObject\QuizResults\QuizResultsFieldCount::class;
		$classes[] = WPObject\QuizResults\QuizResults::class;

		return $classes;
	}

}
