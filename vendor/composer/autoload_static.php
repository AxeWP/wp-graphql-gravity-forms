<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4041e9042c6b9fdddfadea4e48957371
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPGraphQL\\GF\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPGraphQL\\GF\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WPGraphQL\\GF\\Connection\\AbstractConnection' => __DIR__ . '/../..' . '/src/Connection/AbstractConnection.php',
        'WPGraphQL\\GF\\Connection\\EntryConnections' => __DIR__ . '/../..' . '/src/Connection/EntryConnections.php',
        'WPGraphQL\\GF\\Connection\\FormConnections' => __DIR__ . '/../..' . '/src/Connection/FormConnections.php',
        'WPGraphQL\\GF\\Connection\\FormFieldConnections' => __DIR__ . '/../..' . '/src/Connection/FormFieldConnections.php',
        'WPGraphQL\\GF\\CoreSchemaFilters' => __DIR__ . '/../..' . '/src/CoreSchemaFilters.php',
        'WPGraphQL\\GF\\Data\\Connection\\EntriesConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/EntriesConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\Connection\\FormFieldsConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/FormFieldsConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\Connection\\FormsConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/FormsConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\Factory' => __DIR__ . '/../..' . '/src/Data/Factory.php',
        'WPGraphQL\\GF\\Data\\Loader\\DraftEntriesLoader' => __DIR__ . '/../..' . '/src/Data/Loader/DraftEntriesLoader.php',
        'WPGraphQL\\GF\\Data\\Loader\\EntriesLoader' => __DIR__ . '/../..' . '/src/Data/Loader/EntriesLoader.php',
        'WPGraphQL\\GF\\Data\\Loader\\FormsLoader' => __DIR__ . '/../..' . '/src/Data/Loader/FormsLoader.php',
        'WPGraphQL\\GF\\GF' => __DIR__ . '/../..' . '/src/GF.php',
        'WPGraphQL\\GF\\Interfaces\\DataManipulator' => __DIR__ . '/../..' . '/src/Interfaces/DataManipulator.php',
        'WPGraphQL\\GF\\Interfaces\\Enum' => __DIR__ . '/../..' . '/src/Interfaces/Enum.php',
        'WPGraphQL\\GF\\Interfaces\\Field' => __DIR__ . '/../..' . '/src/Interfaces/Field.php',
        'WPGraphQL\\GF\\Interfaces\\FieldProperty' => __DIR__ . '/../..' . '/src/Interfaces/FieldProperty.php',
        'WPGraphQL\\GF\\Interfaces\\FieldValue' => __DIR__ . '/../..' . '/src/Interfaces/FieldValue.php',
        'WPGraphQL\\GF\\Interfaces\\Mutation' => __DIR__ . '/../..' . '/src/Interfaces/Mutation.php',
        'WPGraphQL\\GF\\Interfaces\\Registrable' => __DIR__ . '/../..' . '/src/Interfaces/Registrable.php',
        'WPGraphQL\\GF\\Interfaces\\Type' => __DIR__ . '/../..' . '/src/Interfaces/Type.php',
        'WPGraphQL\\GF\\Interfaces\\TypeWithFields' => __DIR__ . '/../..' . '/src/Interfaces/TypeWithFields.php',
        'WPGraphQL\\GF\\Model\\DraftEntry' => __DIR__ . '/../..' . '/src/Model/DraftEntry.php',
        'WPGraphQL\\GF\\Model\\Entry' => __DIR__ . '/../..' . '/src/Model/Entry.php',
        'WPGraphQL\\GF\\Model\\Form' => __DIR__ . '/../..' . '/src/Model/Form.php',
        'WPGraphQL\\GF\\Mutation\\AbstractMutation' => __DIR__ . '/../..' . '/src/Mutation/AbstractMutation.php',
        'WPGraphQL\\GF\\Mutation\\DeleteDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/DeleteDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\DeleteEntry' => __DIR__ . '/../..' . '/src/Mutation/DeleteEntry.php',
        'WPGraphQL\\GF\\Mutation\\FormSubmissionHelper' => __DIR__ . '/../..' . '/src/Mutation/FormSubmissionHelper.php',
        'WPGraphQL\\GF\\Mutation\\SubmitDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/SubmitDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\SubmitForm' => __DIR__ . '/../..' . '/src/Mutation/SubmitForm.php',
        'WPGraphQL\\GF\\Mutation\\UpdateDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/UpdateDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\UpdateEntry' => __DIR__ . '/../..' . '/src/Mutation/UpdateEntry.php',
        'WPGraphQL\\GF\\TypeRegistry' => __DIR__ . '/../..' . '/src/TypeRegistry.php',
        'WPGraphQL\\GF\\Type\\AbstractType' => __DIR__ . '/../..' . '/src/Type/AbstractType.php',
        'WPGraphQL\\GF\\Type\\Enum\\AbstractEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AbstractEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\AddressTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AddressTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ButtonTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ButtonTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CalendarIconTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CalendarIconTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CaptchaThemeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CaptchaThemeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CaptchaTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CaptchaTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ChainedSelectsAlignmentEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ChainedSelectsAlignmentEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicActionTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ConditionalLogicActionTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicLogicTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ConditionalLogicLogicTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ConfirmationTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ConfirmationTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DateFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DateFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DateTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DateTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DescriptionPlacementPropertyEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DescriptionPlacementPropertyEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\EntryStatusEnum' => __DIR__ . '/../..' . '/src/Type/Enum/EntryStatusEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersModeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FieldFiltersModeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersOperatorInputEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FieldFiltersOperatorInputEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormDescriptionPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormDescriptionPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldsEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldsEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormLimitEntriesPeriodEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormLimitEntriesPeriodEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormStatusEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormStatusEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormSubLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormSubLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\IdTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/IdTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\LabelPlacementPropertyEnum' => __DIR__ . '/../..' . '/src/Type/Enum/LabelPlacementPropertyEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\MinPasswordStrengthEnum' => __DIR__ . '/../..' . '/src/Type/Enum/MinPasswordStrengthEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\NotificationToTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/NotificationToTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\NumberFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/NumberFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PageProgressStyleEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PageProgressStyleEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PageProgressTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PageProgressTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PhoneFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PhoneFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\QuizFieldTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/QuizFieldTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\QuizGradingTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/QuizGradingTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\RequiredIndicatorEnum' => __DIR__ . '/../..' . '/src/Type/Enum/RequiredIndicatorEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\RuleOperatorEnum' => __DIR__ . '/../..' . '/src/Type/Enum/RuleOperatorEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\SignatureBorderStyleEnum' => __DIR__ . '/../..' . '/src/Type/Enum/SignatureBorderStyleEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\SignatureBorderWidthEnum' => __DIR__ . '/../..' . '/src/Type/Enum/SignatureBorderWidthEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\SizePropertyEnum' => __DIR__ . '/../..' . '/src/Type/Enum/SizePropertyEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\SortingInputEnum' => __DIR__ . '/../..' . '/src/Type/Enum/SortingInputEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\TimeFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/TimeFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\VisibilityPropertyEnum' => __DIR__ . '/../..' . '/src/Type/Enum/VisibilityPropertyEnum.php',
        'WPGraphQL\\GF\\Type\\Input\\AbstractInput' => __DIR__ . '/../..' . '/src/Type/Input/AbstractInput.php',
        'WPGraphQL\\GF\\Type\\Input\\AddressInput' => __DIR__ . '/../..' . '/src/Type/Input/AddressInput.php',
        'WPGraphQL\\GF\\Type\\Input\\ChainedSelectInput' => __DIR__ . '/../..' . '/src/Type/Input/ChainedSelectInput.php',
        'WPGraphQL\\GF\\Type\\Input\\CheckboxInput' => __DIR__ . '/../..' . '/src/Type/Input/CheckboxInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EmailInput' => __DIR__ . '/../..' . '/src/Type/Input/EmailInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesDateFiltersInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesDateFiltersInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesFieldFiltersInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesFieldFiltersInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesSortingInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesSortingInput.php',
        'WPGraphQL\\GF\\Type\\Input\\FieldValuesInput' => __DIR__ . '/../..' . '/src/Type/Input/FieldValuesInput.php',
        'WPGraphQL\\GF\\Type\\Input\\FormsSortingInput' => __DIR__ . '/../..' . '/src/Type/Input/FormsSortingInput.php',
        'WPGraphQL\\GF\\Type\\Input\\ListInput' => __DIR__ . '/../..' . '/src/Type/Input/ListInput.php',
        'WPGraphQL\\GF\\Type\\Input\\NameInput' => __DIR__ . '/../..' . '/src/Type/Input/NameInput.php',
        'WPGraphQL\\GF\\Type\\Input\\PostImageInput' => __DIR__ . '/../..' . '/src/Type/Input/PostImageInput.php',
        'WPGraphQL\\GF\\Type\\WPInterface\\FormField' => __DIR__ . '/../..' . '/src/Type/WPInterface/FormField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\AbstractObject' => __DIR__ . '/../..' . '/src/Type/WPObject/AbstractObject.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Button\\Button' => __DIR__ . '/../..' . '/src/Type/WPObject/Button/Button.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Button\\LastPageButton' => __DIR__ . '/../..' . '/src/Type/WPObject/Button/LastPageButton.php',
        'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogic' => __DIR__ . '/../..' . '/src/Type/WPObject/ConditionalLogic/ConditionalLogic.php',
        'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogicRule' => __DIR__ . '/../..' . '/src/Type/WPObject/ConditionalLogic/ConditionalLogicRule.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Entry\\Entry' => __DIR__ . '/../..' . '/src/Type/WPObject/Entry/Entry.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Entry\\EntryQuizResults' => __DIR__ . '/../..' . '/src/Type/WPObject/Entry/EntryQuizResults.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FieldError' => __DIR__ . '/../..' . '/src/Type/WPObject/FieldError.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\AbstractFormField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/AbstractFormField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\AddressField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/AddressField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\CaptchaField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/CaptchaField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ChainedSelectField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/ChainedSelectField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\CheckboxField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/CheckboxField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ConsentField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/ConsentField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\DateField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/DateField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\EmailField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/EmailField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AddressInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AddressInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AdminLabelProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AdminLabelProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AdminOnlyProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AdminOnlyProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AllowedExtensionsProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AllowedExtensionsProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AllowsPrepopulateProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AllowsPrepopulateProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AutocompleteAttributeProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/AutocompleteAttributeProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChainedSelectChoiceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChainedSelectChoiceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChainedSelectInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChainedSelectInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\CheckboxInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/CheckboxInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceIsSelectedProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceIsSelectedProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceTextProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceTextProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoicesProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoicesProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ConditionalLogicProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ConditionalLogicProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\CssClassProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/CssClassProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DateInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/DateInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DefaultValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/DefaultValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DescriptionPlacementProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/DescriptionPlacementProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DescriptionProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/DescriptionProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DisplayOnlyProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/DisplayOnlyProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EmailInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EmailInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableAutocompleteProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EnableAutocompleteProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableChoiceValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EnableChoiceValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableEnhancedUiProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EnableEnhancedUiProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnablePriceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EnablePriceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableSelectAllProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/EnableSelectAllProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ErrorMessageProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ErrorMessageProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputNameProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputNameProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputCustomLabelProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputCustomLabelProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputIdProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputIdProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputIsHiddenProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputIsHiddenProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputKeyProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputKeyProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputNameProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputNameProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputTypeProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputTypeProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputsProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputsProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\IsRequiredProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/IsRequiredProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\LabelPlacementProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/LabelPlacementProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\LabelProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/LabelProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ListChoiceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ListChoiceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\MaxLengthProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/MaxLengthProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\NameInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/NameInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\NoDuplicatesProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/NoDuplicatesProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\PasswordInputProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/PasswordInputProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\PlaceholderProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/PlaceholderProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ProductFieldProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ProductFieldProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\QuizChoiceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/QuizChoiceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\RadioChoiceProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/RadioChoiceProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\SizeProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/SizeProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\SubLabelPlacementProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/SubLabelPlacementProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\VisibilityProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/VisibilityProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\AbstractFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/AbstractFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\AddressFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/AddressFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ChainedSelectFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ChainedSelectFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\CheckboxFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/CheckboxFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ConsentFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ConsentFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\DateFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/DateFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\EmailFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/EmailFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\FileUploadFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/FileUploadFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\HiddenFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/HiddenFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ListFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ListFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\MultiSelectFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/MultiSelectFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\NameFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/NameFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\NumberFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/NumberFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PhoneFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PhoneFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostCategoryFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostCategoryFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostContentFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostContentFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostCustomFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostCustomFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostExcerptFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostExcerptFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostImageFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostImageFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostTagsFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostTagsFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostTitleFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/PostTitleFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\QuizFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/QuizFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\RadioFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/RadioFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\SelectFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/SelectFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\SignatureFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/SignatureFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TextAreaFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/TextAreaFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TextFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/TextFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TimeFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/TimeFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\AddressValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/AddressValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\CheckboxValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/CheckboxValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\ListValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/ListValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\NameValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/NameValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\PostImageValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/PostImageValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\TimeValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/TimeValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\WebsiteFieldValue' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/WebsiteFieldValue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FileUploadField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FileUploadField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\HiddenField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/HiddenField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\HtmlField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/HtmlField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ListField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/ListField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\MultiSelectField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/MultiSelectField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\NameField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/NameField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\NumberField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/NumberField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PageField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PageField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PasswordField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PasswordField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PhoneField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PhoneField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostCategoryField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostCategoryField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostContentField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostContentField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostCustomField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostCustomField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostExcerptField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostExcerptField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostImageField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostImageField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostTagsField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostTagsField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostTitleField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/PostTitleField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\QuizField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/QuizField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\RadioField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/RadioField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SectionField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/SectionField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SelectField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/SelectField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SignatureField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/SignatureField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TextAreaField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/TextAreaField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TextField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/TextField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TimeField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/TimeField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\WebsiteField' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/WebsiteField.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\Form' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/Form.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormConfirmation' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormConfirmation.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotification' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormNotification.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotificationRouting' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormNotificationRouting.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormPagination' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormPagination.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\QuizGrades' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/QuizGrades.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\QuizSettings' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/QuizSettings.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\SaveAndContinue' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/SaveAndContinue.php',
        'WPGraphQL\\GF\\Utils\\GFUtils' => __DIR__ . '/../..' . '/src/Utils/GFUtils.php',
        'WPGraphQL\\GF\\Utils\\Utils' => __DIR__ . '/../..' . '/src/Utils/Utils.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4041e9042c6b9fdddfadea4e48957371::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4041e9042c6b9fdddfadea4e48957371::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4041e9042c6b9fdddfadea4e48957371::$classMap;

        }, null, ClassLoader::class);
    }
}
