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
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Mutation;
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
	 * The list of registered classes.
	 *
	 * @var array
	 */
	public static array $registered_classes;

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
	 * Gets an array of all the registered GraphQL types along with their class name.
	 */
	public static function get_registered_classes() : array {
		if ( empty( self::$registered_classes ) ) {
			self::initialize_registry();
		}

		return self::$registered_classes;
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

		self::$registered_classes = $classes_to_register;

		self::register_types( self::$registered_classes, $type_registry );
	}



	/**
	 * List of Enum classes to register.
	 */
	private static function enums() : array {
		// Enums to register.
		$classes_to_register = [
			Enum\AddressFieldCountryEnum::class,
			Enum\AddressFieldTypeEnum::class,
			Enum\AmPmEnum::class,
			Enum\CaptchaFieldBadgePositionEnum::class,
			Enum\CaptchaFieldThemeEnum::class,
			Enum\CaptchaFieldTypeEnum::class,
			Enum\CurrencyEnum::class,
			Enum\ConditionalLogicActionTypeEnum::class,
			Enum\ConditionalLogicLogicTypeEnum::class,
			Enum\DateFieldFormatEnum::class,
			Enum\DateFieldTypeEnum::class,
			Enum\DraftEntryIdTypeEnum::class,
			Enum\EntryIdTypeEnum::class,
			Enum\EntryStatusEnum::class,
			Enum\EntryTypeEnum::class,
			Enum\FieldFiltersModeEnum::class,
			Enum\FieldFiltersOperatorInputEnum::class,
			Enum\FormButtonTypeEnum::class,
			Enum\FormConfirmationTypeEnum::class,
			Enum\FormCreditCardTypeEnum::class,
			Enum\FormDescriptionPlacementEnum::class,
			Enum\FormFieldCalendarIconTypeEnum::class,
			Enum\FormFieldDescriptionPlacementEnum::class,
			Enum\FormFieldLabelPlacementEnum::class,
			Enum\FormFieldRequiredIndicatorEnum::class,
			Enum\FormFieldSizeEnum::class,
			Enum\FormFieldSubLabelPlacementEnum::class,
			Enum\FormFieldTypeEnum::class,
			Enum\FormFieldVisibilityEnum::class,
			Enum\FormIdTypeEnum::class,
			Enum\FormLabelPlacementEnum::class,
			Enum\FormLimitEntriesPeriodEnum::class,
			Enum\FormNotificationToTypeEnum::class,
			Enum\FormPageProgressStyleEnum::class,
			Enum\FormPageProgressTypeEnum::class,
			Enum\FormRetentionPolicyEnum::class,
			Enum\FormRuleOperatorEnum::class,
			Enum\FormStatusEnum::class,
			Enum\FormSubLabelPlacementEnum::class,
			Enum\FormSubmitButtonLocationEnum::class,
			Enum\FormSubmitButtonWidthEnum::class,
			Enum\NumberFieldFormatEnum::class,
			Enum\PasswordFieldMinStrengthEnum::class,
			Enum\PhoneFieldFormatEnum::class,
			Enum\PostFormatTypeEnum::class,
			Enum\SubmittedEntryIdTypeEnum::class,
			Enum\TimeFieldFormatEnum::class,
		];

		/**
		 * Filters the list of enum classes to register.
		 *
		 * Useful for adding/removing GF specific enums to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_enum_classes', $classes_to_register );
	}

	/**
	 * List of Input classes to register.
	 */
	private static function inputs() : array {
		$classes_to_register = [
			Input\AddressFieldInput::class,
			Input\CheckboxFieldInput::class,
			Input\CreditCardFieldInput::class,
			Input\EmailFieldInput::class,
			Input\EntriesConnectionOrderbyInput::class,
			Input\EntriesDateFiltersInput::class,
			Input\EntriesFieldFiltersInput::class,
			Input\FormsConnectionOrderbyInput::class,
			Input\ListFieldInput::class,
			Input\NameFieldInput::class,
			Input\SubmitFormMetaInput::class,
			Input\UpdateDraftEntryMetaInput::class,
			Input\UpdateEntryMetaInput::class,
		];

		if ( Utils::is_graphql_upload_enabled() ) {
			$classes_to_register[] = Input\PostImageFieldInput::class;
		}

		// Register late, since it depends on above inputs.
		$classes_to_register[] = Input\FormFieldValuesInput::class;

		/**
		 * Filters the list of input classes to register.
		 *
		 * Useful for adding/removing GF specific inputs to the schema.
		 *
		 * @param array           $classes_to_register Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_input_classes', $classes_to_register );
	}

	/**
	 * List of Interface classes to register.
	 */
	public static function interfaces() : array {
		$classes_to_register = [
			WPInterface\Entry::class,
			WPInterface\FormField::class,
			WPInterface\NodeWithForm::class,
		];

		/**
		 * Filters the list of interfaces classes to register.
		 *
		 * Useful for adding/removing GF specific interfaces to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_interface_classes', $classes_to_register );
	}

	/**
	 * List of Object classes to register.
	 */
	public static function objects() : array {
		$classes_to_register = [
			// Buttons.
			WPObject\Button\FormButton::class,
			WPObject\Button\FormLastPageButton::class,
			// Conditional Logic.
			WPObject\ConditionalLogic\ConditionalLogic::class,
			WPObject\ConditionalLogic\ConditionalLogicRule::class,
			// Entries.
			WPObject\Entry\DraftEntry::class,
			WPObject\Entry\SubmittedEntry::class,
			// Forms.
			WPObject\Form\Form::class,
			WPObject\Form\FormConfirmation::class,
			WPObject\Form\FormDataPolicies::class,
			WPObject\Form\FormEntryLimits::class,
			WPObject\Form\FormEntryDataPolicy::class,
			WPObject\Form\FormLogin::class,
			WPObject\Form\FormNotification::class,
			WPObject\Form\FormNotificationRouting::class,
			WPObject\Form\FormPagination::class,
			WPObject\Form\FormPersonalData::class,
			WPObject\Form\FormPostCreation::class,
			WPObject\Form\FormScheduleDetails::class,
			WPObject\Form\FormSchedule::class,
			WPObject\Form\FormSaveAndContinue::class,
			WPObject\Form\FormSubmitButton::class,
			WPObject\FormField\FormFieldDataPolicy::class,
			// Form Field Value properties.
			WPObject\FormField\FieldValue\ValueProperty\AddressFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\CheckboxFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\FileUploadFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\ListFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\NameFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\ImageFieldValue::class,
			WPObject\FormField\FieldValue\ValueProperty\TimeFieldValue::class,
			// Form Fields.
			WPObject\FormField\FormFields::class,
			// Field Error.
			WPObject\FieldError::class,
			// GF Settings.
			WPObject\Settings\Logger::class,
			WPObject\Settings\SettingsLogging::class,
			WPObject\Settings\Settings::class,
		];

		/**
		 * Filters the list of object classes to register.
		 *
		 * Useful for adding/removing GF specific objects to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_object_classes', $classes_to_register );
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
		return apply_filters( 'graphql_gf_registered_field_classes', $classes_to_register );
	}

	/**
	 * List of Connection classes to register.
	 */
	public static function connections() : array {
		$classes_to_register = [
			Connection\EntriesConnection::class,
			Connection\FormsConnection::class,
			Connection\FormFieldsConnection::class,
		];

		/**
		 * Filters the list of connection classes to register.
		 *
		 * Useful for adding/removing GF specific connections to the schema.
		 *
		 * @param array           $classes_to_register = Array of classes to be registered to the schema.
		 */
		return apply_filters( 'graphql_gf_registered_connection_classes', $classes_to_register );
	}

	/**
	 * Registers mutation.
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
		$classes_to_register = apply_filters( 'graphql_gf_registered_mutation_classes', $classes_to_register );

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
				throw new Exception( sprintf( __( 'To be registered to the GF GraphQL schema, %s needs to implement WPGraphQL\Interfaces\Registrable.', 'wp-graphql-gravity-forms' ), $class ) );
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
