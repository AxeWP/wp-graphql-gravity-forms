<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4bd9f01cc0b77c11bc8413f515ba2488
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
        'WPGraphQL\\GF\\Connection\\EntriesConnection' => __DIR__ . '/../..' . '/src/Connection/EntriesConnection.php',
        'WPGraphQL\\GF\\Connection\\FormFieldsConnection' => __DIR__ . '/../..' . '/src/Connection/FormFieldsConnection.php',
        'WPGraphQL\\GF\\Connection\\FormsConnection' => __DIR__ . '/../..' . '/src/Connection/FormsConnection.php',
        'WPGraphQL\\GF\\CoreSchemaFilters' => __DIR__ . '/../..' . '/src/CoreSchemaFilters.php',
        'WPGraphQL\\GF\\Data\\Connection\\EntriesConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/EntriesConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\Connection\\FormFieldsConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/FormFieldsConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\Connection\\FormsConnectionResolver' => __DIR__ . '/../..' . '/src/Data/Connection/FormsConnectionResolver.php',
        'WPGraphQL\\GF\\Data\\EntryObjectMutation' => __DIR__ . '/../..' . '/src/Data/EntryObjectMutation.php',
        'WPGraphQL\\GF\\Data\\Factory' => __DIR__ . '/../..' . '/src/Data/Factory.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\AbstractFieldValueInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/AbstractFieldValueInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\AddressValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/AddressValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\CheckboxValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/CheckboxValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\ConsentValueInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/ConsentValueInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\EmailValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/EmailValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\FileUploadValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/FileUploadValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\ImageValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/ImageValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\ListValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/ListValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\NameValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/NameValuesInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\ValueInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/ValueInput.php',
        'WPGraphQL\\GF\\Data\\FieldValueInput\\ValuesInput' => __DIR__ . '/../..' . '/src/Data/FieldValueInput/ValuesInput.php',
        'WPGraphQL\\GF\\Data\\Loader\\DraftEntriesLoader' => __DIR__ . '/../..' . '/src/Data/Loader/DraftEntriesLoader.php',
        'WPGraphQL\\GF\\Data\\Loader\\EntriesLoader' => __DIR__ . '/../..' . '/src/Data/Loader/EntriesLoader.php',
        'WPGraphQL\\GF\\Data\\Loader\\FormsLoader' => __DIR__ . '/../..' . '/src/Data/Loader/FormsLoader.php',
        'WPGraphQL\\GF\\Extensions\\Extensions' => __DIR__ . '/../..' . '/src/Extensions/Extensions.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\Data\\FieldValueInput\\ChainedSelectValuesInput' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/Data/FieldValueInput/ChainedSelectValuesInput.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\GFChainedSelects' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/GFChainedSelects.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\Type\\Enum\\ChainedSelectFieldAlignmentEnum' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/Type/Enum/ChainedSelectFieldAlignmentEnum.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\Type\\Input\\ChainedSelectFieldInput' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/Type/Input/ChainedSelectFieldInput.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\Type\\WPObject\\FormField\\FieldProperty\\PropertyMapper' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/Type/WPObject/FormField/FieldProperty/PropertyMapper.php',
        'WPGraphQL\\GF\\Extensions\\GFChainedSelects\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty' => __DIR__ . '/../..' . '/src/Extensions/GFChainedSelects/Type/WPObject/FormField/FieldValue/ValueProperty.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\GFQuiz' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/GFQuiz.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\Enum\\QuizFieldGradingTypeEnum' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/Enum/QuizFieldGradingTypeEnum.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\Enum\\QuizFieldTypeEnum' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/Enum/QuizFieldTypeEnum.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\WPObject\\Entry\\EntryQuizResults' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/WPObject/Entry/EntryQuizResults.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\WPObject\\FormField\\FieldProperty\\PropertyMapper' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/WPObject/FormField/FieldProperty/PropertyMapper.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\WPObject\\Form\\FormQuiz' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/WPObject/Form/FormQuiz.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\WPObject\\Form\\FormQuizConfirmation' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/WPObject/Form/FormQuizConfirmation.php',
        'WPGraphQL\\GF\\Extensions\\GFQuiz\\Type\\WPObject\\Form\\FormQuizGrades' => __DIR__ . '/../..' . '/src/Extensions/GFQuiz/Type/WPObject/Form/FormQuizGrades.php',
        'WPGraphQL\\GF\\Extensions\\GFSignature\\Data\\FieldValueInput\\SignatureValuesInput' => __DIR__ . '/../..' . '/src/Extensions/GFSignature/Data/FieldValueInput/SignatureValuesInput.php',
        'WPGraphQL\\GF\\Extensions\\GFSignature\\GFSignature' => __DIR__ . '/../..' . '/src/Extensions/GFSignature/GFSignature.php',
        'WPGraphQL\\GF\\Extensions\\GFSignature\\Type\\Enum\\SignatureFieldBorderStyleEnum' => __DIR__ . '/../..' . '/src/Extensions/GFSignature/Type/Enum/SignatureFieldBorderStyleEnum.php',
        'WPGraphQL\\GF\\Extensions\\GFSignature\\Type\\Enum\\SignatureFieldBorderWidthEnum' => __DIR__ . '/../..' . '/src/Extensions/GFSignature/Type/Enum/SignatureFieldBorderWidthEnum.php',
        'WPGraphQL\\GF\\Extensions\\GFSignature\\Type\\WPObject\\FormField\\FieldProperty\\PropertyMapper' => __DIR__ . '/../..' . '/src/Extensions/GFSignature/Type/WPObject/FormField/FieldProperty/PropertyMapper.php',
        'WPGraphQL\\GF\\GF' => __DIR__ . '/../..' . '/src/GF.php',
        'WPGraphQL\\GF\\Interfaces\\DataManipulator' => __DIR__ . '/../..' . '/src/Interfaces/DataManipulator.php',
        'WPGraphQL\\GF\\Interfaces\\Enum' => __DIR__ . '/../..' . '/src/Interfaces/Enum.php',
        'WPGraphQL\\GF\\Interfaces\\Field' => __DIR__ . '/../..' . '/src/Interfaces/Field.php',
        'WPGraphQL\\GF\\Interfaces\\FieldProperty' => __DIR__ . '/../..' . '/src/Interfaces/FieldProperty.php',
        'WPGraphQL\\GF\\Interfaces\\Mutation' => __DIR__ . '/../..' . '/src/Interfaces/Mutation.php',
        'WPGraphQL\\GF\\Interfaces\\Registrable' => __DIR__ . '/../..' . '/src/Interfaces/Registrable.php',
        'WPGraphQL\\GF\\Interfaces\\Type' => __DIR__ . '/../..' . '/src/Interfaces/Type.php',
        'WPGraphQL\\GF\\Interfaces\\TypeWithFields' => __DIR__ . '/../..' . '/src/Interfaces/TypeWithFields.php',
        'WPGraphQL\\GF\\Model\\DraftEntry' => __DIR__ . '/../..' . '/src/Model/DraftEntry.php',
        'WPGraphQL\\GF\\Model\\Form' => __DIR__ . '/../..' . '/src/Model/Form.php',
        'WPGraphQL\\GF\\Model\\SubmittedEntry' => __DIR__ . '/../..' . '/src/Model/SubmittedEntry.php',
        'WPGraphQL\\GF\\Mutation\\AbstractMutation' => __DIR__ . '/../..' . '/src/Mutation/AbstractMutation.php',
        'WPGraphQL\\GF\\Mutation\\DeleteDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/DeleteDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\DeleteEntry' => __DIR__ . '/../..' . '/src/Mutation/DeleteEntry.php',
        'WPGraphQL\\GF\\Mutation\\SubmitDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/SubmitDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\SubmitForm' => __DIR__ . '/../..' . '/src/Mutation/SubmitForm.php',
        'WPGraphQL\\GF\\Mutation\\UpdateDraftEntry' => __DIR__ . '/../..' . '/src/Mutation/UpdateDraftEntry.php',
        'WPGraphQL\\GF\\Mutation\\UpdateEntry' => __DIR__ . '/../..' . '/src/Mutation/UpdateEntry.php',
        'WPGraphQL\\GF\\TypeRegistry' => __DIR__ . '/../..' . '/src/TypeRegistry.php',
        'WPGraphQL\\GF\\Type\\Enum\\AbstractEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AbstractEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\AddressFieldCountryEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AddressFieldCountryEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\AddressFieldTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AddressFieldTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\AmPmEnum' => __DIR__ . '/../..' . '/src/Type/Enum/AmPmEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CaptchaFieldBadgePositionEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CaptchaFieldBadgePositionEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CaptchaFieldThemeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CaptchaFieldThemeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\CaptchaFieldTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/CaptchaFieldTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicActionTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ConditionalLogicActionTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\ConditionalLogicLogicTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/ConditionalLogicLogicTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DateFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DateFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DateFieldTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DateFieldTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\DraftEntryIdTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/DraftEntryIdTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\EntryIdTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/EntryIdTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\EntryStatusEnum' => __DIR__ . '/../..' . '/src/Type/Enum/EntryStatusEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\EntryTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/EntryTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersModeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FieldFiltersModeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FieldFiltersOperatorInputEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FieldFiltersOperatorInputEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormButtonTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormButtonTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormConfirmationTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormConfirmationTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormCreditCardTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormCreditCardTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormDescriptionPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormDescriptionPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldCalendarIconTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldCalendarIconTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldDescriptionPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldDescriptionPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldRequiredIndicatorEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldRequiredIndicatorEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldSizeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldSizeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldSubLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldSubLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormFieldVisibilityEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormFieldVisibilityEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormIdTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormIdTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormLimitEntriesPeriodEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormLimitEntriesPeriodEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormNotificationToTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormNotificationToTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormPageProgressStyleEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormPageProgressStyleEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormPageProgressTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormPageProgressTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormRuleOperatorEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormRuleOperatorEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormStatusEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormStatusEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\FormSubLabelPlacementEnum' => __DIR__ . '/../..' . '/src/Type/Enum/FormSubLabelPlacementEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\NumberFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/NumberFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PasswordFieldMinStrengthEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PasswordFieldMinStrengthEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PhoneFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PhoneFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\PostFormatTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/PostFormatTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\SubmittedEntryIdTypeEnum' => __DIR__ . '/../..' . '/src/Type/Enum/SubmittedEntryIdTypeEnum.php',
        'WPGraphQL\\GF\\Type\\Enum\\TimeFieldFormatEnum' => __DIR__ . '/../..' . '/src/Type/Enum/TimeFieldFormatEnum.php',
        'WPGraphQL\\GF\\Type\\Input\\AbstractInput' => __DIR__ . '/../..' . '/src/Type/Input/AbstractInput.php',
        'WPGraphQL\\GF\\Type\\Input\\AddressFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/AddressFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\CheckboxFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/CheckboxFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\CreditCardFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/CreditCardFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EmailFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/EmailFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesConnectionOrderbyInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesConnectionOrderbyInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesDateFiltersInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesDateFiltersInput.php',
        'WPGraphQL\\GF\\Type\\Input\\EntriesFieldFiltersInput' => __DIR__ . '/../..' . '/src/Type/Input/EntriesFieldFiltersInput.php',
        'WPGraphQL\\GF\\Type\\Input\\FormFieldValuesInput' => __DIR__ . '/../..' . '/src/Type/Input/FormFieldValuesInput.php',
        'WPGraphQL\\GF\\Type\\Input\\FormsConnectionOrderbyInput' => __DIR__ . '/../..' . '/src/Type/Input/FormsConnectionOrderbyInput.php',
        'WPGraphQL\\GF\\Type\\Input\\ListFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/ListFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\NameFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/NameFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\PostImageFieldInput' => __DIR__ . '/../..' . '/src/Type/Input/PostImageFieldInput.php',
        'WPGraphQL\\GF\\Type\\Input\\SubmitFormMetaInput' => __DIR__ . '/../..' . '/src/Type/Input/SubmitFormMetaInput.php',
        'WPGraphQL\\GF\\Type\\Input\\UpdateDraftEntryMetaInput' => __DIR__ . '/../..' . '/src/Type/Input/UpdateDraftEntryMetaInput.php',
        'WPGraphQL\\GF\\Type\\Input\\UpdateEntryMetaInput' => __DIR__ . '/../..' . '/src/Type/Input/UpdateEntryMetaInput.php',
        'WPGraphQL\\GF\\Type\\WPInterface\\Entry' => __DIR__ . '/../..' . '/src/Type/WPInterface/Entry.php',
        'WPGraphQL\\GF\\Type\\WPInterface\\FormField' => __DIR__ . '/../..' . '/src/Type/WPInterface/FormField.php',
        'WPGraphQL\\GF\\Type\\WPInterface\\NodeWithForm' => __DIR__ . '/../..' . '/src/Type/WPInterface/NodeWithForm.php',
        'WPGraphQL\\GF\\Type\\WPObject\\AbstractObject' => __DIR__ . '/../..' . '/src/Type/WPObject/AbstractObject.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Button\\FormButton' => __DIR__ . '/../..' . '/src/Type/WPObject/Button/FormButton.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Button\\FormLastPageButton' => __DIR__ . '/../..' . '/src/Type/WPObject/Button/FormLastPageButton.php',
        'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogic' => __DIR__ . '/../..' . '/src/Type/WPObject/ConditionalLogic/ConditionalLogic.php',
        'WPGraphQL\\GF\\Type\\WPObject\\ConditionalLogic\\ConditionalLogicRule' => __DIR__ . '/../..' . '/src/Type/WPObject/ConditionalLogic/ConditionalLogicRule.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Entry\\DraftEntry' => __DIR__ . '/../..' . '/src/Type/WPObject/Entry/DraftEntry.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Entry\\SubmittedEntry' => __DIR__ . '/../..' . '/src/Type/WPObject/Entry/SubmittedEntry.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FieldError' => __DIR__ . '/../..' . '/src/Type/WPObject/FieldError.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\ChoiceMapper' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/ChoiceMapper.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\FieldProperties' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/FieldProperties.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\InputMapper' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/InputMapper.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldProperty\\PropertyMapper' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldProperty/PropertyMapper.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperties' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperties.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\AddressValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/AddressValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\CheckboxValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/CheckboxValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\ImageValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/ImageValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\ListValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/ListValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\NameValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/NameValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FieldValue\\ValueProperty\\TimeValueProperty' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FieldValue/ValueProperty/TimeValueProperty.php',
        'WPGraphQL\\GF\\Type\\WPObject\\FormField\\FormFields' => __DIR__ . '/../..' . '/src/Type/WPObject/FormField/FormFields.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\Form' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/Form.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormConfirmation' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormConfirmation.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormEntryLimits' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormEntryLimits.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormLogin' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormLogin.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotification' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormNotification.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormNotificationRouting' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormNotificationRouting.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormPagination' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormPagination.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormPostCreation' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormPostCreation.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormSaveAndContinue' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormSaveAndContinue.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormSchedule' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormSchedule.php',
        'WPGraphQL\\GF\\Type\\WPObject\\Form\\FormScheduleDetails' => __DIR__ . '/../..' . '/src/Type/WPObject/Form/FormScheduleDetails.php',
        'WPGraphQL\\GF\\Utils\\GFUtils' => __DIR__ . '/../..' . '/src/Utils/GFUtils.php',
        'WPGraphQL\\GF\\Utils\\Utils' => __DIR__ . '/../..' . '/src/Utils/Utils.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4bd9f01cc0b77c11bc8413f515ba2488::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4bd9f01cc0b77c11bc8413f515ba2488::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4bd9f01cc0b77c11bc8413f515ba2488::$classMap;

        }, null, ClassLoader::class);
    }
}
