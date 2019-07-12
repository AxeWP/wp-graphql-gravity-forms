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
                'imageUrl' => 'https://example.com/',
                'conditionalLogic' => [
                    'actionType' => 'show',
                    'logicType' => 'any',
                    'rules' => [
                        [
                            'fieldId'  => 1,
                            'operator' => 'is',
                            'value'    => 'value1'
                        ],
                        [
                            'fieldId'  => 1,
                            'operator' => 'is',
                            'value'    => 'value2',
                        ],
                    ],
                ],
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
            'pagination' => [
                'type'                                => 'percentage',
                'pages'                               => [ 'page-1-name', 'page-2-name' ],
                'style'                               => 'custom',
                'backgroundColor'                     => '#c6df9c',
                'color'                               => '#197b30',
                'display_progressbar_on_confirmation' => true,
                'progressbar_completion_text'         => 'Completed!',
            ],
            'firstPageCssClass' => 'first-page-css-class',
            'postAuthor' => 1,
            'postCategory' => 1,
            'postFormat' => '0',
            'postStatus' => 'publish',
            'subLabelPlacement' => 'prefix',
            'cssClass' => 'css-class-1 css-class-2',
            'enableHoneypot' => false,
            'enableAnimation' => false,
            'save' => [
                'enabled' => true,
                'button' => [
                    'type'     => 'link',
                    'text'     => 'Save and Continue Later',
                    'imageUrl' => 'https://example.com/',
                ],
            ],
            'limitEntries' => true,
            'limitEntriesCount' => 100,
            'limitEntriesPeriod' => 'year',
            'limitEntriesMessage' => 'Only 100 entries are permitted.',
            'scheduleForm' => true,
            'scheduleStart' => '01/01/2020',
            'scheduleStartHour' => 9,
            'scheduleStartMinute' => 30,
            'scheduleStartAmpm' => 'am',
            'scheduleEnd' => '01/01/2030',
            'scheduleEndHour' => 10,
            'scheduleEndMinute' => 45,
            'scheduleEndAmpm' => 'pm',
            'schedulePendingMessage' => 'Schedule pending message.',
            'scheduleMessage' => 'Schedule message.',
            'requireLogin' => true,
            'requireLoginMessage' => 'You must be logged in to submit this form.',
            'notifications' => [
                '5cfec9464e529' => [
                    'id'                => '5cfec9464e529',
                    'isActive'          => true,
                    'to'                => '{admin_email}',
                    'name'              => 'Admin Notification',
                    'event'             => 'form_submission',
                    'toType'            => 'email',
                    'subject'           => 'New submission from {form_title}',
                    'message'           => '{all_fields}',
                    'service'           => 'wordpress',
                    'bcc'               => 'bcc-email@example.com',
                    'from'              => 'from-email@example.com',
                    'fromName'          => 'WordPress',
                    'replyTo'           => 'replyto-email@example.com',
                    'disableAutoformat' => false,
                    'enableAttachments' => false,
                    'routing' => [
                        [
                            'fieldId'  => 1,
                            'operator' => 'is',
                            'value'    => 'value1',
                            'email'    => 'email1@example.com',
                        ],
                        [
                            'fieldId'  => 1,
                            'operator' => 'is',
                            'value'    => 'value2',
                            'email'    => 'email2@example.com',
                        ],
                    ],
                    'conditionalLogic' => [
                        'actionType' => 'show',
                        'logicType' => 'any',
                        'rules' => [
                            [
                                'fieldId'  => 1,
                                'operator' => 'is',
                                'value'    => 'value1'
                            ],
                            [
                                'fieldId'  => 1,
                                'operator' => 'is',
                                'value'    => 'value2',
                            ],
                        ],
                    ],
                ],
            ],
            'confirmations' => [
                '5cfec9464e7d7'   => [
                    'id'          => '5cfec9464e7d7',
                    'name'        => 'Default Confirmation',
                    'isDefault'   => true,
                    'type'        => 'message',
                    'message'     => 'Thanks for contacting us! We will get in touch with you shortly.',
                    'url'         => 'https://example.com/',
                    'pageId'      => 1,
                    'queryString' => 'text={Single Line Text:1}&textarea={Text Area:2}'
                ]
            ],
            'nextFieldId' => 3,
            'is_active' => true,
            'date_created' => '2019-06-10 21:19:02', // This is disregarded by GFAPI::add_form().
            'is_trash' => false,
        ];

        // Insert form into the DB using the mock data above.
        $form_id   = \GFAPI::add_form( $form );
        $global_id = \GraphQLRelay\Relay::toGlobalId( 'GravityFormsForm', $form_id );

        // A new date_created is generated when a form is imported using GFAPI::add_form(), so
        // we need to get that value from the DB rather than from the mock data above.
        $imported_form = \GFAPI::get_form( $form_id );
        $date_created  = $imported_form['date_created'] ?? '';

        $query = "
            query {
                gravityFormsForm(id: \"{$global_id}\") {
                    id
                    formId
                    title
                    description
                    labelPlacement
                    descriptionPlacement
                    button {
                        type
                        text
                        imageUrl
                        conditionalLogic {
                            actionType
                            logicType
                            rules {
                                fieldId
                                operator
                                value
                            }
                        }
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
                    pagination {
                        type
                        pages
                        style
                        backgroundColor
                        color
                        displayProgressbarOnConfirmation
                        progressbarCompletionText
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
                    notifications {
                        id
                        isActive
                        to
                        name
                        event
                        toType
                        subject
                        message
                        service
                        bcc
                        from
                        fromName
                        replyTo
                        disableAutoformat
                        enableAttachments
                        routing {
                            fieldId
                            operator
                            value
                            email
                        }
                        conditionalLogic {
                            actionType
                            logicType
                            rules {
                                fieldId
                                operator
                                value
                            }
                        }
                    }
                    confirmations {
                        id
                        name
                        isDefault
                        type
                        message
                        url
                        pageId
                        queryString
                    }
                    nextFieldId
                    isActive
                    dateCreated
                    isTrash
                }
            }
        ";

        $actual = graphql( [ 'query' => $query ] );

        $expected = [
            'data' => [
                'gravityFormsForm' => [
                    'id' => $global_id,
                    'formId' => $form_id,
                    'title' => $form['title'],
                    'description' => $form['description'],
                    'labelPlacement' => $form['labelPlacement'],
                    'descriptionPlacement' => $form['descriptionPlacement'],
                    'button' => [
                        'type'     => $form['button']['type'],
                        'text'     => $form['button']['text'],
                        'imageUrl' => $form['button']['imageUrl'],
                        'conditionalLogic' => [
                            'actionType' => $form['button']['conditionalLogic']['actionType'],
                            'logicType' => $form['button']['conditionalLogic']['logicType'],
                            'rules' => [
                                [
                                    'fieldId'  => $form['button']['conditionalLogic']['rules'][0]['fieldId'],
                                    'operator' => $form['button']['conditionalLogic']['rules'][0]['operator'],
                                    'value'    => $form['button']['conditionalLogic']['rules'][0]['value'],
                                ],
                                [
                                    'fieldId'  => $form['button']['conditionalLogic']['rules'][1]['fieldId'],
                                    'operator' => $form['button']['conditionalLogic']['rules'][1]['operator'],
                                    'value'    => $form['button']['conditionalLogic']['rules'][1]['value'],
                                ],
                            ],
                        ],
                    ],
                    'fields' => [
                        [ 'type' => $form['fields'][0]['type'] ],
                        [ 'type' => $form['fields'][1]['type'] ],
                    ],
                    'version' => $form['version'],
                    'useCurrentUserAsAuthor' => $form['useCurrentUserAsAuthor'],
                    'postContentTemplateEnabled' => $form['postContentTemplateEnabled'],
                    'postTitleTemplateEnabled' => $form['postTitleTemplateEnabled'],
                    'postTitleTemplate' => $form['postTitleTemplate'],
                    'postContentTemplate' => $form['postContentTemplate'],
                    'lastPageButton' => [
                        'type'     => $form['lastPageButton']['type'],
                        'text'     => $form['lastPageButton']['text'],
                        'imageUrl' => $form['lastPageButton']['imageUrl'],
                    ],
                    'pagination' => [
                        'type'                             => $form['pagination']['type'],
                        'pages'                            => $form['pagination']['pages'],
                        'style'                            => $form['pagination']['style'],
                        'backgroundColor'                  => $form['pagination']['backgroundColor'],
                        'color'                            => $form['pagination']['color'],
                        'displayProgressbarOnConfirmation' => $form['pagination']['display_progressbar_on_confirmation'],
                        'progressbarCompletionText'        => $form['pagination']['progressbar_completion_text'],
                    ],
                    'firstPageCssClass' => $form['firstPageCssClass'],
                    'postAuthor' => $form['postAuthor'],
                    'postCategory' => $form['postCategory'],
                    'postFormat' => $form['postFormat'],
                    'postStatus' => $form['postStatus'],
                    'subLabelPlacement' => $form['subLabelPlacement'],
                    'cssClass' => $form['cssClass'],
                    'enableHoneypot' => $form['enableHoneypot'],
                    'enableAnimation' => $form['enableAnimation'],
                    'save' => [
                        'enabled' => $form['save']['enabled'],
                        'button' => [
                            'type'     => $form['save']['button']['type'],
                            'text'     => $form['save']['button']['text'],
                            'imageUrl' => $form['save']['button']['imageUrl'],
                        ],
                    ],
                    'limitEntries' => $form['limitEntries'],
                    'limitEntriesCount' => $form['limitEntriesCount'],
                    'limitEntriesPeriod' => $form['limitEntriesPeriod'],
                    'limitEntriesMessage' => $form['limitEntriesMessage'],
                    'scheduleForm' => $form['scheduleForm'],
                    'scheduleStart' => $form['scheduleStart'],
                    'scheduleStartHour' => $form['scheduleStartHour'],
                    'scheduleStartMinute' => $form['scheduleStartMinute'],
                    'scheduleStartAmpm' => $form['scheduleStartAmpm'],
                    'scheduleEnd' => $form['scheduleEnd'],
                    'scheduleEndHour' => $form['scheduleEndHour'],
                    'scheduleEndMinute' => $form['scheduleEndMinute'],
                    'scheduleEndAmpm' => $form['scheduleEndAmpm'],
                    'schedulePendingMessage' => $form['schedulePendingMessage'],
                    'scheduleMessage' => $form['scheduleMessage'],
                    'requireLogin' => $form['requireLogin'],
                    'requireLoginMessage' => $form['requireLoginMessage'],
                    'notifications' => [
                        [
                            'id'                => $form['notifications']['5cfec9464e529']['id'],
                            'isActive'          => $form['notifications']['5cfec9464e529']['isActive'],
                            'to'                => $form['notifications']['5cfec9464e529']['to'],
                            'name'              => $form['notifications']['5cfec9464e529']['name'],
                            'event'             => $form['notifications']['5cfec9464e529']['event'],
                            'toType'            => $form['notifications']['5cfec9464e529']['toType'],
                            'subject'           => $form['notifications']['5cfec9464e529']['subject'],
                            'message'           => $form['notifications']['5cfec9464e529']['message'],
                            'service'           => $form['notifications']['5cfec9464e529']['service'],
                            'bcc'               => $form['notifications']['5cfec9464e529']['bcc'],
                            'from'              => $form['notifications']['5cfec9464e529']['from'],
                            'fromName'          => $form['notifications']['5cfec9464e529']['fromName'],
                            'replyTo'           => $form['notifications']['5cfec9464e529']['replyTo'],
                            'disableAutoformat' => $form['notifications']['5cfec9464e529']['disableAutoformat'],
                            'enableAttachments' => $form['notifications']['5cfec9464e529']['enableAttachments'],
                            'routing' => [
                                [
                                    'fieldId'  => $form['notifications']['5cfec9464e529']['routing'][0]['fieldId'],
                                    'operator' => $form['notifications']['5cfec9464e529']['routing'][0]['operator'],
                                    'value'    => $form['notifications']['5cfec9464e529']['routing'][0]['value'],
                                    'email'    => $form['notifications']['5cfec9464e529']['routing'][0]['email'],
                                ],
                                [
                                    'fieldId'  => $form['notifications']['5cfec9464e529']['routing'][1]['fieldId'],
                                    'operator' => $form['notifications']['5cfec9464e529']['routing'][1]['operator'],
                                    'value'    => $form['notifications']['5cfec9464e529']['routing'][1]['value'],
                                    'email'    => $form['notifications']['5cfec9464e529']['routing'][1]['email'],
                                ],
                            ],
                            'conditionalLogic' => [
                                'actionType' => $form['notifications']['5cfec9464e529']['conditionalLogic']['actionType'],
                                'logicType' => $form['notifications']['5cfec9464e529']['conditionalLogic']['logicType'],
                                'rules' => [
                                    [
                                        'fieldId'  => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['fieldId'],
                                        'operator' => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['operator'],
                                        'value'    => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][0]['value'],
                                    ],
                                    [
                                        'fieldId'  => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['fieldId'],
                                        'operator' => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['operator'],
                                        'value'    => $form['notifications']['5cfec9464e529']['conditionalLogic']['rules'][1]['value'],
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'confirmations' => [
                        [
                            'id'          => $form['confirmations']['5cfec9464e7d7']['id'],
                            'name'        => $form['confirmations']['5cfec9464e7d7']['name'],
                            'isDefault'   => $form['confirmations']['5cfec9464e7d7']['isDefault'],
                            'type'        => $form['confirmations']['5cfec9464e7d7']['type'],
                            'message'     => $form['confirmations']['5cfec9464e7d7']['message'],
                            'url'         => $form['confirmations']['5cfec9464e7d7']['url'],
                            'pageId'      => $form['confirmations']['5cfec9464e7d7']['pageId'],
                            'queryString' => $form['confirmations']['5cfec9464e7d7']['queryString'],
                        ]
                    ],
                    'nextFieldId' => $form['nextFieldId'],
                    'isActive' => $form['is_active'],
                    'dateCreated' => $date_created,
                    'isTrash' => $form['is_trash'],
                ],
            ],
        ];

        $this->assertEquals( $expected, $actual );
    }
}
