<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Composer\\InstalledVersions' => $vendorDir . '/composer/InstalledVersions.php',
    'WPGraphQL\\GF\\Connection\\AbstractConnection' => $baseDir . '/src/Connection/AbstractConnection.php',
    'WPGraphQL\\GF\\Connection\\EntryConnections' => $baseDir . '/src/Connection/EntryConnections.php',
    'WPGraphQL\\GF\\Connection\\FormConnections' => $baseDir . '/src/Connection/FormConnections.php',
    'WPGraphQL\\GF\\Connection\\FormFieldConnections' => $baseDir . '/src/Connection/FormFieldConnections.php',
    'WPGraphQL\\GF\\CoreSchemaFilters' => $baseDir . '/src/CoreSchemaFilters.php',
    'WPGraphQL\\GF\\Data\\Connection\\EntriesConnectionResolver' => $baseDir . '/src/Data/Connection/EntriesConnectionResolver.php',
    'WPGraphQL\\GF\\Data\\Connection\\FormFieldsConnectionResolver' => $baseDir . '/src/Data/Connection/FormFieldsConnectionResolver.php',
    'WPGraphQL\\GF\\Data\\Connection\\FormsConnectionResolver' => $baseDir . '/src/Data/Connection/FormsConnectionResolver.php',
    'WPGraphQL\\GF\\Data\\Factory' => $baseDir . '/src/Data/Factory.php',
    'WPGraphQL\\GF\\Data\\Loader\\DraftEntriesLoader' => $baseDir . '/src/Data/Loader/DraftEntriesLoader.php',
    'WPGraphQL\\GF\\Data\\Loader\\EntriesLoader' => $baseDir . '/src/Data/Loader/EntriesLoader.php',
    'WPGraphQL\\GF\\Data\\Loader\\FormsLoader' => $baseDir . '/src/Data/Loader/FormsLoader.php',
    'WPGraphQL\\GF\\GF' => $baseDir . '/src/GF.php',
    'WPGraphQL\\GF\\Interfaces\\DataManipulator' => $baseDir . '/src/Interfaces/DataManipulator.php',
    'WPGraphQL\\GF\\Interfaces\\Enum' => $baseDir . '/src/Interfaces/Enum.php',
    'WPGraphQL\\GF\\Interfaces\\Field' => $baseDir . '/src/Interfaces/Field.php',
    'WPGraphQL\\GF\\Interfaces\\FieldProperty' => $baseDir . '/src/Interfaces/FieldProperty.php',
    'WPGraphQL\\GF\\Interfaces\\FieldValue' => $baseDir . '/src/Interfaces/FieldValue.php',
    'WPGraphQL\\GF\\Interfaces\\Mutation' => $baseDir . '/src/Interfaces/Mutation.php',
    'WPGraphQL\\GF\\Interfaces\\Registrable' => $baseDir . '/src/Interfaces/Registrable.php',
    'WPGraphQL\\GF\\Interfaces\\Type' => $baseDir . '/src/Interfaces/Type.php',
    'WPGraphQL\\GF\\Interfaces\\TypeWithFields' => $baseDir . '/src/Interfaces/TypeWithFields.php',
    'WPGraphQL\\GF\\Model\\DraftEntry' => $baseDir . '/src/Model/DraftEntry.php',
    'WPGraphQL\\GF\\Model\\Entry' => $baseDir . '/src/Model/Entry.php',
    'WPGraphQL\\GF\\Model\\Form' => $baseDir . '/src/Model/Form.php',
    'WPGraphQL\\GF\\Mutation\\AbstractMutation' => $baseDir . '/src/Mutation/AbstractMutation.php',
    'WPGraphQL\\GF\\Mutation\\DeleteDraftEntry' => $baseDir . '/src/Mutation/DeleteDraftEntry.php',
    'WPGraphQL\\GF\\Mutation\\DeleteEntry' => $baseDir . '/src/Mutation/DeleteEntry.php',
    'WPGraphQL\\GF\\Mutation\\FormSubmissionHelper' => $baseDir . '/src/Mutation/FormSubmissionHelper.php',
    'WPGraphQL\\GF\\Mutation\\SubmitDraftEntry' => $baseDir . '/src/Mutation/SubmitDraftEntry.php',
    'WPGraphQL\\GF\\Mutation\\SubmitForm' => $baseDir . '/src/Mutation/SubmitForm.php',
    'WPGraphQL\\GF\\Mutation\\UpdateDraftEntry' => $baseDir . '/src/Mutation/UpdateDraftEntry.php',
    'WPGraphQL\\GF\\Mutation\\UpdateEntry' => $baseDir . '/src/Mutation/UpdateEntry.php',
    'WPGraphQL\\GF\\TypeRegistry' => $baseDir . '/src/TypeRegistry.php',
    'WPGraphQL\\GF\\Type\\AbstractType' => $baseDir . '/src/Type/AbstractType.php',
    'WPGraphQL\\GF\\Type\\Enum\\AbstractEnum' => $baseDir . '/src/Type/Enum/AbstractEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\AddressTypeEnum' => $baseDir . '/src/Type/Enum/AddressTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\ButtonTypeEnum' => $baseDir . '/src/Type/Enum/ButtonTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\CalendarIconTypeEnum' => $baseDir . '/src/Type/Enum/CalendarIconTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\CaptchaThemeEnum' => $baseDir . '/src/Type/Enum/CaptchaThemeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\CaptchaTypeEnum' => $baseDir . '/src/Type/Enum/CaptchaTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\ChainedSelectsAlignmentEnum' => $baseDir . '/src/Type/Enum/ChainedSelectsAlignmentEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicActionTypeEnum' => $baseDir . '/src/Type/Enum/ConditionalLogicActionTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicLogicTypeEnum' => $baseDir . '/src/Type/Enum/ConditionalLogicLogicTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\ConfirmationTypeEnum' => $baseDir . '/src/Type/Enum/ConfirmationTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\DateFieldFormatEnum' => $baseDir . '/src/Type/Enum/DateFieldFormatEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\DateTypeEnum' => $baseDir . '/src/Type/Enum/DateTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\DescriptionPlacementPropertyEnum' => $baseDir . '/src/Type/Enum/DescriptionPlacementPropertyEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\EntryStatusEnum' => $baseDir . '/src/Type/Enum/EntryStatusEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersModeEnum' => $baseDir . '/src/Type/Enum/FieldFiltersModeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersOperatorInputEnum' => $baseDir . '/src/Type/Enum/FieldFiltersOperatorInputEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormDescriptionPlacementEnum' => $baseDir . '/src/Type/Enum/FormDescriptionPlacementEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormFieldsEnum' => $baseDir . '/src/Type/Enum/FormFieldsEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormLabelPlacementEnum' => $baseDir . '/src/Type/Enum/FormLabelPlacementEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormLimitEntriesPeriodEnum' => $baseDir . '/src/Type/Enum/FormLimitEntriesPeriodEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormStatusEnum' => $baseDir . '/src/Type/Enum/FormStatusEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\FormSubLabelPlacementEnum' => $baseDir . '/src/Type/Enum/FormSubLabelPlacementEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\IdTypeEnum' => $baseDir . '/src/Type/Enum/IdTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\LabelPlacementPropertyEnum' => $baseDir . '/src/Type/Enum/LabelPlacementPropertyEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\MinPasswordStrengthEnum' => $baseDir . '/src/Type/Enum/MinPasswordStrengthEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\NotificationToTypeEnum' => $baseDir . '/src/Type/Enum/NotificationToTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\NumberFieldFormatEnum' => $baseDir . '/src/Type/Enum/NumberFieldFormatEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\PageProgressStyleEnum' => $baseDir . '/src/Type/Enum/PageProgressStyleEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\PageProgressTypeEnum' => $baseDir . '/src/Type/Enum/PageProgressTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\PhoneFieldFormatEnum' => $baseDir . '/src/Type/Enum/PhoneFieldFormatEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\QuizFieldTypeEnum' => $baseDir . '/src/Type/Enum/QuizFieldTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\QuizGradingTypeEnum' => $baseDir . '/src/Type/Enum/QuizGradingTypeEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\RequiredIndicatorEnum' => $baseDir . '/src/Type/Enum/RequiredIndicatorEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\RuleOperatorEnum' => $baseDir . '/src/Type/Enum/RuleOperatorEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\SignatureBorderStyleEnum' => $baseDir . '/src/Type/Enum/SignatureBorderStyleEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\SignatureBorderWidthEnum' => $baseDir . '/src/Type/Enum/SignatureBorderWidthEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\SizePropertyEnum' => $baseDir . '/src/Type/Enum/SizePropertyEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\SortingInputEnum' => $baseDir . '/src/Type/Enum/SortingInputEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\TimeFieldFormatEnum' => $baseDir . '/src/Type/Enum/TimeFieldFormatEnum.php',
    'WPGraphQL\\GF\\Type\\Enum\\VisibilityPropertyEnum' => $baseDir . '/src/Type/Enum/VisibilityPropertyEnum.php',
    'WPGraphQL\\GF\\Type\\Input\\AbstractInput' => $baseDir . '/src/Type/Input/AbstractInput.php',
    'WPGraphQL\\GF\\Type\\Input\\AddressInput' => $baseDir . '/src/Type/Input/AddressInput.php',
    'WPGraphQL\\GF\\Type\\Input\\ChainedSelectInput' => $baseDir . '/src/Type/Input/ChainedSelectInput.php',
    'WPGraphQL\\GF\\Type\\Input\\CheckboxInput' => $baseDir . '/src/Type/Input/CheckboxInput.php',
    'WPGraphQL\\GF\\Type\\Input\\EmailInput' => $baseDir . '/src/Type/Input/EmailInput.php',
    'WPGraphQL\\GF\\Type\\Input\\EntriesDateFiltersInput' => $baseDir . '/src/Type/Input/EntriesDateFiltersInput.php',
    'WPGraphQL\\GF\\Type\\Input\\EntriesFieldFiltersInput' => $baseDir . '/src/Type/Input/EntriesFieldFiltersInput.php',
    'WPGraphQL\\GF\\Type\\Input\\EntriesSortingInput' => $baseDir . '/src/Type/Input/EntriesSortingInput.php',
    'WPGraphQL\\GF\\Type\\Input\\FieldValuesInput' => $baseDir . '/src/Type/Input/FieldValuesInput.php',
    'WPGraphQL\\GF\\Type\\Input\\FormsSortingInput' => $baseDir . '/src/Type/Input/FormsSortingInput.php',
    'WPGraphQL\\GF\\Type\\Input\\ListInput' => $baseDir . '/src/Type/Input/ListInput.php',
    'WPGraphQL\\GF\\Type\\Input\\NameInput' => $baseDir . '/src/Type/Input/NameInput.php',
    'WPGraphQL\\GF\\Type\\Input\\PostImageInput' => $baseDir . '/src/Type/Input/PostImageInput.php',
    'WPGraphQL\\GF\\Type\\WPInterface\\FormField' => $baseDir . '/src/Type/WPInterface/FormField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\AbstractObject' => $baseDir . '/src/Type/WPObject/AbstractObject.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Button\\Button' => $baseDir . '/src/Type/WPObject/Button/Button.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Button\\LastPageButton' => $baseDir . '/src/Type/WPObject/Button/LastPageButton.php',
    'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogic' => $baseDir . '/src/Type/WPObject/ConditionalLogic/ConditionalLogic.php',
    'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogicRule' => $baseDir . '/src/Type/WPObject/ConditionalLogic/ConditionalLogicRule.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Entry\\Entry' => $baseDir . '/src/Type/WPObject/Entry/Entry.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Entry\\EntryQuizResults' => $baseDir . '/src/Type/WPObject/Entry/EntryQuizResults.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FieldError' => $baseDir . '/src/Type/WPObject/FieldError.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\AbstractFormField' => $baseDir . '/src/Type/WPObject/FormField/AbstractFormField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\AddressField' => $baseDir . '/src/Type/WPObject/FormField/AddressField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\CaptchaField' => $baseDir . '/src/Type/WPObject/FormField/CaptchaField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ChainedSelectField' => $baseDir . '/src/Type/WPObject/FormField/ChainedSelectField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\CheckboxField' => $baseDir . '/src/Type/WPObject/FormField/CheckboxField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ConsentField' => $baseDir . '/src/Type/WPObject/FormField/ConsentField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\DateField' => $baseDir . '/src/Type/WPObject/FormField/DateField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\EmailField' => $baseDir . '/src/Type/WPObject/FormField/EmailField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AddressInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AddressInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AdminLabelProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AdminLabelProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AdminOnlyProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AdminOnlyProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AllowedExtensionsProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AllowedExtensionsProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AllowsPrepopulateProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AllowsPrepopulateProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\AutocompleteAttributeProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/AutocompleteAttributeProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChainedSelectChoiceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChainedSelectChoiceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChainedSelectInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChainedSelectInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\CheckboxInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/CheckboxInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceIsSelectedProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceIsSelectedProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceTextProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceTextProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceProperty\\ChoiceValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChoiceProperty/ChoiceValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoicesProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ChoicesProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ConditionalLogicProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ConditionalLogicProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\CssClassProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/CssClassProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DateInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/DateInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DefaultValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/DefaultValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DescriptionPlacementProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/DescriptionPlacementProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DescriptionProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/DescriptionProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\DisplayOnlyProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/DisplayOnlyProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EmailInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EmailInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableAutocompleteProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EnableAutocompleteProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableChoiceValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EnableChoiceValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableEnhancedUiProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EnableEnhancedUiProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnablePriceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EnablePriceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\EnableSelectAllProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/EnableSelectAllProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ErrorMessageProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ErrorMessageProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputNameProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputNameProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputCustomLabelProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputCustomLabelProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputIdProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputIdProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputIsHiddenProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputIsHiddenProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputKeyProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputKeyProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputProperty\\InputNameProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputProperty/InputNameProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputTypeProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputTypeProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputsProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/InputsProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\IsRequiredProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/IsRequiredProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\LabelPlacementProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/LabelPlacementProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\LabelProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/LabelProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ListChoiceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ListChoiceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\MaxLengthProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/MaxLengthProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\NameInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/NameInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\NoDuplicatesProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/NoDuplicatesProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\PasswordInputProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/PasswordInputProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\PlaceholderProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/PlaceholderProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ProductFieldProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/ProductFieldProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\QuizChoiceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/QuizChoiceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\RadioChoiceProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/RadioChoiceProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\SizeProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/SizeProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\SubLabelPlacementProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/SubLabelPlacementProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\VisibilityProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldProperty/VisibilityProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\AbstractFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/AbstractFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\AddressFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/AddressFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ChainedSelectFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ChainedSelectFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\CheckboxFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/CheckboxFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ConsentFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ConsentFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\DateFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/DateFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\EmailFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/EmailFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\FileUploadFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/FileUploadFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\HiddenFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/HiddenFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ListFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ListFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\MultiSelectFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/MultiSelectFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\NameFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/NameFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\NumberFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/NumberFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PhoneFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PhoneFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostCategoryFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostCategoryFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostContentFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostContentFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostCustomFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostCustomFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostExcerptFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostExcerptFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostImageFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostImageFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostTagsFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostTagsFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\PostTitleFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/PostTitleFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\QuizFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/QuizFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\RadioFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/RadioFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\SelectFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/SelectFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\SignatureFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/SignatureFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TextAreaFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/TextAreaFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TextFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/TextFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\TimeFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/TimeFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\AddressValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/AddressValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\CheckboxValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/CheckboxValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\ListValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/ListValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\NameValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/NameValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\PostImageValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/PostImageValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\TimeValueProperty' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/TimeValueProperty.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\WebsiteFieldValue' => $baseDir . '/src/Type/WPObject/FormField/FieldValue/WebsiteFieldValue.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FileUploadField' => $baseDir . '/src/Type/WPObject/FormField/FileUploadField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\HiddenField' => $baseDir . '/src/Type/WPObject/FormField/HiddenField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\HtmlField' => $baseDir . '/src/Type/WPObject/FormField/HtmlField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\ListField' => $baseDir . '/src/Type/WPObject/FormField/ListField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\MultiSelectField' => $baseDir . '/src/Type/WPObject/FormField/MultiSelectField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\NameField' => $baseDir . '/src/Type/WPObject/FormField/NameField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\NumberField' => $baseDir . '/src/Type/WPObject/FormField/NumberField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PageField' => $baseDir . '/src/Type/WPObject/FormField/PageField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PasswordField' => $baseDir . '/src/Type/WPObject/FormField/PasswordField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PhoneField' => $baseDir . '/src/Type/WPObject/FormField/PhoneField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostCategoryField' => $baseDir . '/src/Type/WPObject/FormField/PostCategoryField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostContentField' => $baseDir . '/src/Type/WPObject/FormField/PostContentField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostCustomField' => $baseDir . '/src/Type/WPObject/FormField/PostCustomField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostExcerptField' => $baseDir . '/src/Type/WPObject/FormField/PostExcerptField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostImageField' => $baseDir . '/src/Type/WPObject/FormField/PostImageField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostTagsField' => $baseDir . '/src/Type/WPObject/FormField/PostTagsField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\PostTitleField' => $baseDir . '/src/Type/WPObject/FormField/PostTitleField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\QuizField' => $baseDir . '/src/Type/WPObject/FormField/QuizField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\RadioField' => $baseDir . '/src/Type/WPObject/FormField/RadioField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SectionField' => $baseDir . '/src/Type/WPObject/FormField/SectionField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SelectField' => $baseDir . '/src/Type/WPObject/FormField/SelectField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\SignatureField' => $baseDir . '/src/Type/WPObject/FormField/SignatureField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TextAreaField' => $baseDir . '/src/Type/WPObject/FormField/TextAreaField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TextField' => $baseDir . '/src/Type/WPObject/FormField/TextField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\TimeField' => $baseDir . '/src/Type/WPObject/FormField/TimeField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\FormField\\WebsiteField' => $baseDir . '/src/Type/WPObject/FormField/WebsiteField.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\Form' => $baseDir . '/src/Type/WPObject/Form/Form.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormConfirmation' => $baseDir . '/src/Type/WPObject/Form/FormConfirmation.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotification' => $baseDir . '/src/Type/WPObject/Form/FormNotification.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotificationRouting' => $baseDir . '/src/Type/WPObject/Form/FormNotificationRouting.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormPagination' => $baseDir . '/src/Type/WPObject/Form/FormPagination.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\QuizGrades' => $baseDir . '/src/Type/WPObject/Form/QuizGrades.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\QuizSettings' => $baseDir . '/src/Type/WPObject/Form/QuizSettings.php',
    'WPGraphQL\\GF\\Type\\WPObject\\Form\\SaveAndContinue' => $baseDir . '/src/Type/WPObject/Form/SaveAndContinue.php',
    'WPGraphQL\\GF\\Utils\\GFUtils' => $baseDir . '/src/Utils/GFUtils.php',
    'WPGraphQL\\GF\\Utils\\Utils' => $baseDir . '/src/Utils/Utils.php',
);
