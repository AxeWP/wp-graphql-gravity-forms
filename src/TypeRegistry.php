<?php
/**
 * Registers GF types to the GraphQL schema.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

namespace WPGraphQL\GF;

use Exception;
use WPGraphQL\GF\Connection;
use WPGraphQL\GF\Mutation;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\GF\Type\Enum;
use WPGraphQL\GF\Type\Input;
use WPGraphQL\GF\Type\WPInterface;
use WPGraphQL\GF\Type\WPObject;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry as GraphQLRegistry;

/**
 * Class - TypeRegistry
 */
class TypeRegistry {

	/**
	 * The local registry of registered types.
	 *
	 * @var array
	 */
	public static array $registry = [];

	/**
	 * Gets an array of all the registered GraphQL types along with their class name.
	 */
	public static function get_registered_types() : array {
		if ( empty( self::$registry ) ) {
			self::initialize_registry();
		}

		return self::$registry;
	}

	/**
	 * Registers types, connections, unions, and mutations to GraphQL schema.
	 *
	 * @param GraphQLRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public static function init( GraphQLRegistry $type_registry ) : void {
		/**
		 * Fires before all types have been registered.
		 *
		 * @param GraphQLRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
		 */
		do_action( 'graphql_gf_before_register_types', $type_registry );

		self::initialize_registry( $type_registry );

		/**
		 * Fires after all types have been registered.
		 *
		 * @param GraphQLRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
		 */
		do_action( 'graphql_gf_after_register_types', $type_registry );
	}

	/**
	 * Initializes the GF type registry. If $type_registry is nulled, these fields wont be (re)-registered to WPGraphQL.
	 *
	 * @param GraphQLRegistry $type_registry .
	 */
	private static function initialize_registry( GraphQLRegistry $type_registry = null ) : void {
		$classes_to_register = array_merge(
			self::enums(),
			self::inputs(),
			self::interfaces(),
			self::objects(),
			self::fields(),
			self::connections(),
			self::mutations(),
		);

		self::register_types( $classes_to_register, $type_registry );
	}



	/**
	 * List of Enum classes to register.
	 */
	private static function enums() : array {
		// Enums to register.
		$classes_to_register = [
			Enum\AddressTypeEnum::class,
			Enum\ButtonTypeEnum::class,
			Enum\CalendarIconTypeEnum::class,
			Enum\CaptchaThemeEnum::class,
			Enum\CaptchaTypeEnum::class,
			Enum\ChainedSelectsAlignmentEnum::class,
			Enum\ConditionalLogicActionTypeEnum::class,
			Enum\ConditionalLogicLogicTypeEnum::class,
			Enum\ConfirmationTypeEnum::class,
			Enum\DateFieldFormatEnum::class,
			Enum\DateTypeEnum::class,
			Enum\DescriptionPlacementPropertyEnum::class,
			Enum\EntryStatusEnum::class,
			Enum\FieldFiltersModeEnum::class,
			Enum\FieldFiltersOperatorInputEnum::class,
			Enum\FormDescriptionPlacementEnum::class,
			Enum\FormFieldsEnum::class,
			Enum\FormLabelPlacementEnum::class,
			Enum\FormLimitEntriesPeriodEnum::class,
			Enum\FormStatusEnum::class,
			Enum\FormSubLabelPlacementEnum::class,
			Enum\IdTypeEnum::class,
			Enum\LabelPlacementPropertyEnum::class,
			Enum\MinPasswordStrengthEnum::class,
			Enum\NotificationToTypeEnum::class,
			Enum\NumberFieldFormatEnum::class,
			Enum\PageProgressStyleEnum::class,
			Enum\PageProgressTypeEnum::class,
			Enum\PhoneFieldFormatEnum::class,
			Enum\QuizFieldTypeEnum::class,
			Enum\QuizGradingTypeEnum::class,
			Enum\RequiredIndicatorEnum::class,
			Enum\RuleOperatorEnum::class,
			Enum\SignatureBorderStyleEnum::class,
			Enum\SignatureBorderWidthEnum::class,
			Enum\SizePropertyEnum::class,
			Enum\SortingInputEnum::class,
			Enum\TimeFieldFormatEnum::class,
			Enum\VisibilityPropertyEnum::class,
		];

		/**
		 * Filters the list of enum classes to register.
		 *
		 * Useful for adding/removing GF specific enums to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_enums_to_register', $classes_to_register );
	}

	/**
	 * List of Input classes to register.
	 */
	private static function inputs() : array {
		$classes_to_register = [
			Input\AddressInput::class,
			Input\ChainedSelectInput::class,
			Input\CheckboxInput::class,
			Input\EmailInput::class,
			Input\EntriesDateFiltersInput::class,
			Input\EntriesFieldFiltersInput::class,
			Input\EntriesSortingInput::class,
			Input\FormsSortingInput::class,
			Input\ListInput::class,
			Input\NameInput::class,
		];

		if ( Utils::is_graphql_upload_enabled() ) {
			$classes_to_register[] = Input\PostImageInput::class;
		}

		// Register late, since it depends on above inputs.
		$classes_to_register[] = Input\FieldValuesInput::class;

		/**
		 * Filters the list of input classes to register.
		 *
		 * Useful for adding/removing GF specific inputs to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_inputs_to_register', $classes_to_register );
	}

	/**
	 * List of Interface classes to register.
	 */
	public static function interfaces() : array {
		$classes_to_register = [
			WPInterface\FormField::class,
		];

		/**
		 * Filters the list of interfaces classes to register.
		 *
		 * Useful for adding/removing GF specific interfaces to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_interfaces_to_register', $classes_to_register );
	}

	/**
	 * List of Object classes to register.
	 */
	public static function objects() : array {
		$classes_to_register = [
			// Buttons.
			WPObject\Button\Button::class,
			WPObject\Button\LastPageButton::class,
			// Conditional Logic.
			WPObject\ConditionalLogic\ConditionalLogic::class,
			WPObject\ConditionalLogic\ConditionalLogicRule::class,
			// Entries.
			WPObject\Entry\Entry::class,
			WPObject\Entry\EntryForm::class,
			WPObject\Entry\EntryQuizResults::class,
			WPObject\Entry\EntryUser::class,
			// Forms.
			WPObject\Form\Form::class,
			WPObject\Form\FormConfirmation::class,
			WPObject\Form\FormNotification::class,
			WPObject\Form\FormNotificationRouting::class,
			WPObject\Form\FormPagination::class,
			WPObject\Form\QuizGrades::class,
			WPObject\Form\QuizSettings::class,
			WPObject\Form\SaveAndContinue::class,
			// FormField Properties.
			WPObject\FormField\FieldProperty\AddressInputProperty::class,
			WPObject\FormField\FieldProperty\ChainedSelectChoiceProperty::class,
			WPObject\FormField\FieldProperty\ChainedSelectInputProperty::class,
			WPObject\FormField\FieldProperty\CheckboxInputProperty::class,
			WPObject\FormField\FieldProperty\ChoiceProperty::class,
			WPObject\FormField\FieldProperty\DateInputProperty::class,
			WPObject\FormField\FieldProperty\EmailInputProperty::class,
			WPObject\FormField\FieldProperty\InputProperty::class,
			WPObject\FormField\FieldProperty\ListChoiceProperty::class,
			WPObject\FormField\FieldProperty\NameInputProperty::class,
			WPObject\FormField\FieldProperty\PasswordInputProperty::class,
			WPObject\FormField\FieldProperty\QuizChoiceProperty::class,
			WPObject\FormField\FieldProperty\RadioChoiceProperty::class,
			// Form Field Value properties.
			WPObject\FormField\FieldValue\ValueProperty\AddressValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\CheckboxValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\ListValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\NameValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\PostImageValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\PostImageValueProperty::class,
			WPObject\FormField\FieldValue\ValueProperty\TimeValueProperty::class,
			// Form Field Values.
			WPObject\FormField\FieldValue\AddressFieldValue::class,
			WPObject\FormField\FieldValue\ChainedSelectFieldValue::class,
			WPObject\FormField\FieldValue\CheckboxFieldValue::class,
			WPObject\FormField\FieldValue\ConsentFieldValue::class,
			WPObject\FormField\FieldValue\DateFieldValue::class,
			WPObject\FormField\FieldValue\EmailFieldValue::class,
			WPObject\FormField\FieldValue\FileUploadFieldValue::class,
			WPObject\FormField\FieldValue\HiddenFieldValue::class,
			WPObject\FormField\FieldValue\ListFieldValue::class,
			WPObject\FormField\FieldValue\MultiSelectFieldValue::class,
			WPObject\FormField\FieldValue\NameFieldValue::class,
			WPObject\FormField\FieldValue\NumberFieldValue::class,
			WPObject\FormField\FieldValue\PhoneFieldValue::class,
			WPObject\FormField\FieldValue\PostCategoryFieldValue::class,
			WPObject\FormField\FieldValue\PostContentFieldValue::class,
			WPObject\FormField\FieldValue\PostCustomFieldValue::class,
			WPObject\FormField\FieldValue\PostExcerptFieldValue::class,
			WPObject\FormField\FieldValue\PostTagsFieldValue::class,
			WPObject\FormField\FieldValue\PostTitleFieldValue::class,
			WPObject\FormField\FieldValue\QuizFieldValue::class,
			WPObject\FormField\FieldValue\RadioFieldValue::class,
			WPObject\FormField\FieldValue\SelectFieldValue::class,
			WPObject\FormField\FieldValue\SignatureFieldValue::class,
			WPObject\FormField\FieldValue\TextAreaFieldValue::class,
			WPObject\FormField\FieldValue\TextFieldValue::class,
			WPObject\FormField\FieldValue\TimeFieldValue::class,
			WPObject\FormField\FieldValue\WebsiteFieldValue::class,
			// Form Fields.
			WPObject\FormField\AddressField::class,
			WPObject\FormField\CaptchaField::class,
			WPObject\FormField\ChainedSelectField::class,
			WPObject\FormField\CheckboxField::class,
			WPObject\FormField\ConsentField::class,
			WPObject\FormField\ConsentField::class,
			WPObject\FormField\DateField::class,
			WPObject\FormField\EmailField::class,
			WPObject\FormField\FileUploadField::class,
			WPObject\FormField\HiddenField::class,
			WPObject\FormField\HtmlField::class,
			WPObject\FormField\HtmlField::class,
			WPObject\FormField\ListField::class,
			WPObject\FormField\MultiSelectField::class,
			WPObject\FormField\NameField::class,
			WPObject\FormField\NumberField::class,
			WPObject\FormField\NumberField::class,
			WPObject\FormField\PageField::class,
			WPObject\FormField\PasswordField::class,
			WPObject\FormField\PhoneField::class,
			WPObject\FormField\PostCategoryField::class,
			WPObject\FormField\PostContentField::class,
			WPObject\FormField\PostCustomField::class,
			WPObject\FormField\PostExcerptField::class,
			WPObject\FormField\PostImageField::class,
			WPObject\FormField\PostTagsField::class,
			WPObject\FormField\PostTitleField::class,
			WPObject\FormField\QuizField::class,
			WPObject\FormField\RadioField::class,
			WPObject\FormField\SectionField::class,
			WPObject\FormField\SelectField::class,
			WPObject\FormField\SignatureField::class,
			WPObject\FormField\TextAreaField::class,
			WPObject\FormField\TextField::class,
			WPObject\FormField\TimeField::class,
			WPObject\FormField\WebsiteField::class,
			// Field Error.
			WPObject\FieldError::class,
		];

		/**
		 * Filters the list of object classes to register.
		 *
		 * Useful for adding/removing GF specific objects to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_objects_to_register', $classes_to_register );
	}

	/**
	 * List of Field classes to register.
	 */
	public static function fields() : array {
		$classes_to_register = [];

		/**
		 * Filters the list of field classes to register.
		 *
		 * Useful for adding/removing GF specific fields to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_fields_to_register', $classes_to_register );
	}

	/**
	 * List of Connection classes to register.
	 */
	public static function connections() : array {
		$classes_to_register = [
			Connection\EntryConnections::class,
			Connection\FormConnections::class,
			Connection\FormFieldConnections::class,
		];

		/**
		 * Filters the list of connection classes to register.
		 *
		 * Useful for adding/removing GF specific connections to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_connections_to_register', $classes_to_register );
	}

	/**
	 * Registers mutation.
	 *
	 * @todo convert mutations to a static class, and this to a list of Registrable classes.
	 */
	public static function mutations() : array {
		$classes_to_register = [
			Mutation\DeleteDraftEntry::class,
			Mutation\DeleteEntry::class,
			Mutation\SubmitDraftEntry::class,
			Mutation\SubmitForm::class,
			Mutation\UpdateDraftEntry::class,
			Mutation\UpdateEntry::class,
		];

		/**
		 * Filters the list of connection classes to register.
		 *
		 * Useful for adding/removing GF specific connections to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		$classes_to_register = apply_filters( 'graphql_gf_mutations_to_register', $classes_to_register );

		return $classes_to_register;
	}

	/**
	 * Loops through a list of classes to manually register each GraphQL to the registry, and stores the type name and class in the local registry.
	 *
	 * Classes must extend WPGraphQL\Type\AbstractType.
	 *
	 * @param array           $classes_to_register .
	 * @param GraphQLRegistry $type_registry .
	 *
	 * @throws Exception .
	 */
	private static function register_types( array $classes_to_register, GraphQLRegistry $type_registry = null ) : void {
		// Bail if there are no classes to register.
		if ( empty( $classes_to_register ) ) {
			return;
		}

		foreach ( $classes_to_register as $class ) {
			if ( ! is_a( $class, Registrable::class, true ) ) {
				// translators: PHP class.
				throw new Exception( sprintf( __( 'To be registered to the GF GraphQL schema, %s needs to implement WPGraphQL\Interfaces\Registrable', 'wp-graphql-gravity-forms' ), $class ) );
			}

			// Register the type to the GraphQL schema. Skipped if we're trying to get the type registry beforehand.
			if ( null !== $type_registry ) {
				$class::register( $type_registry );
			}

			// Saves the Type => ClassName to the local registry.
			if ( isset( $class::$type ) ) {
				self::$registry[ $class::$type ] = $class;
			}
		}
	}
}
