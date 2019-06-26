<?php

class TestFormQuery extends WP_UnitTestCase {
	/**
	 * Test Gravity Forms form query.
	 */
	public function test_form_query() {
        $form = [
            'title' => 'Test form title',
            'description' => 'Test form description.',
            'labelPlacement' => 'top_label',
            'descriptionPlacement' => 'below',
            'button' => [
                'type' => 'text',
                'text' => 'Submit',
                'imageUrl' => 'https://example.com/'
            ],
            'fields' => [
                [
                    'type' => 'text',
                    'id' => 1,
                    'label' => 'Single Line Text',
                    'adminLabel' => '',
                    'isRequired' => false,
                    'size' => 'medium',
                    'errorMessage' => '',
                    'visibility' => 'visible',
                    'inputs' => null,
                    'formId' => 2,
                    'description' => 'I am a single line text field.',
                    'allowsPrepopulate' => false,
                    'inputMask' => false,
                    'inputMaskValue' => '',
                    'inputMaskIsCustom' => false,
                    'maxLength' => '',
                    'inputType' => '',
                    'labelPlacement' => '',
                    'descriptionPlacement' => '',
                    'subLabelPlacement' => '',
                    'placeholder' => '',
                    'cssClass' => '',
                    'inputName' => '',
                    'noDuplicates' => false,
                    'defaultValue' => '',
                    'choices' => '',
                    'conditionalLogic' => '',
                    'productField' => '',
                    'enablePasswordInput' => '',
                    'multipleFiles' => false,
                    'maxFiles' => '',
                    'calculationFormula' => '',
                    'calculationRounding' => '',
                    'enableCalculation' => '',
                    'disableQuantity' => false,
                    'displayAllCategories' => false,
                    'useRichTextEditor' => false,
                    'checkboxLabel' => '',
                    'pageNumber' => 1,
                    'fields' => '',
                    'displayOnly' => ''
                ],
                [
                    'type' => 'textarea',
                    'id' => 2,
                    'label' => 'Text Area',
                    'adminLabel' => '',
                    'isRequired' => false,
                    'size' => 'medium',
                    'errorMessage' => '',
                    'visibility' => 'visible',
                    'inputs' => null,
                    'formId' => 2,
                    'description' => 'I am a text area field.',
                    'allowsPrepopulate' => false,
                    'inputMask' => false,
                    'inputMaskValue' => '',
                    'inputMaskIsCustom' => false,
                    'maxLength' => 28,
                    'inputType' => '',
                    'labelPlacement' => '',
                    'descriptionPlacement' => '',
                    'subLabelPlacement' => '',
                    'placeholder' => '',
                    'cssClass' => '',
                    'inputName' => '',
                    'noDuplicates' => false,
                    'defaultValue' => '',
                    'choices' => '',
                    'conditionalLogic' => '',
                    'productField' => '',
                    'form_id' => '',
                    'useRichTextEditor' => false,
                    'multipleFiles' => false,
                    'maxFiles' => '',
                    'calculationFormula' => '',
                    'calculationRounding' => '',
                    'enableCalculation' => '',
                    'disableQuantity' => false,
                    'displayAllCategories' => false,
                    'pageNumber' => 1,
                    'fields' => '',
                    'displayOnly' => ''
                ],
            ],
            'version' => '2.4.9',
            'id' => 2,
            'nextFieldId' => 6,
            'useCurrentUserAsAuthor' => true,
            'postContentTemplateEnabled' => false,
            'postTitleTemplateEnabled' => false,
            'postTitleTemplate' => 'Post title template',
            'postContentTemplate' => 'Post content template',
            'lastPageButton' => [
                'type' => 'text',
                'text' => 'Previous',
                'imageUrl' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png'
            ],
            'pagination' => null,
            'firstPageCssClass' => 'first-page-css-class',
            'notifications' => [
                '5cfec9464e529' => [
                    'id' => '5cfec9464e529',
                    'isActive' => true,
                    'to' => '{admin_email}',
                    'name' => 'Admin Notification',
                    'event' => 'form_submission',
                    'toType' => 'email',
                    'subject' => 'New submission from {form_title}',
                    'message' => '{all_fields}'
                ]
            ],
            'confirmations' => [
                '5cfec9464e7d7' => [
                    'id' => '5cfec9464e7d7',
                    'name' => 'Default Confirmation',
                    'isDefault' => true,
                    'type' => 'message',
                    'message' => 'Thanks for contacting us! We will get in touch with you shortly.',
                    'url' => 'https://example.com/',
                    'pageId' => 1,
                    'queryString' => 'text={Single Line Text:1}&textarea={Text Area:2}'
                ]
            ],
            'is_active' => '1',
            'date_created' => '2019-06-10 21:19:02',
            'is_trash' => '0'
        ];

        $form_id   = \GFAPI::add_form( $form );
        $global_id = \GraphQLRelay\Relay::toGlobalId( 'gravityformsform', $form_id );

        $query = "
        query {
            gravityFormsForm(id: \"{$global_id}\") {
                id
                formId
                title
                labelPlacement
                descriptionPlacement
                button {
                    type
                    text
                    imageUrl
                }
                fields {
                    ... on TextField {
                        type
                    }
                    ... on TextAreaField {
                        type
                    }
                }
                version
                useCurrentUserAsAuthor
                postContentTemplateEnabled
                postTitleTemplateEnabled
                postTitleTemplate
                postContentTemplate
                lastPageButton {
                    type
                    text
                    imageUrl
                }
                firstPageCssClass
                postAuthor
                postCategory
                postFormat
                postStatus
                subLabelPlacement
                cssClass
                enableHoneypot
                enableAnimation
                save {
                    enabled
                    button {
                        type
                        text
                        imageUrl
                    }
                }
                limitEntries
                limitEntriesCount
                limitEntriesPeriod
                limitEntriesMessage
                scheduleForm
                scheduleStart
                scheduleStartHour
                scheduleStartMinute
                scheduleStartAmpm
                scheduleEnd
                scheduleEndHour
                scheduleEndMinute
                scheduleEndAmpm
                schedulePendingMessage
                scheduleMessage
                requireLogin
                requireLoginMessage
                confirmations {
                    type
                    message
                    pageId
                    url
                    queryString
                }
                nextFieldId
                isActive
                date_created
                isTrash
            }
          }          
        ";

        // @TODO: add notifications and pagination to test query.

        $actual = graphql( [ 'query' => $query ] );

        // TODO
        $expected = [];

		$this->assertEquals( $expected, $actual );
    }
}
