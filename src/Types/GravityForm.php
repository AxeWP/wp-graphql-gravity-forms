<?php

namespace WPGraphQLGravityForms\Types;

use GFAPI;
use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;

class GravityForm implements Hookable {
    const TYPE = 'GravityForm';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms form.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'id'                => [
                    'type'        => [
                        'non_null' => 'ID',
                    ],
                    'description' => __( 'The globally unique ID for the object', 'wp-graphql-gravityforms' ),
                ],
                'formId'   => [
                    'type'        => 'Integer',
                    'description' => __( 'Form ID.', 'wp-graphql-gravityforms' ),
                ],
                'title'  => [
                    'type'        => 'String',
                    'description' => __( 'Form title.', 'wp-graphql-gravityforms' ),
                ],
                'description' => [
                    'type'        => 'String',
                    'description' => __( 'Form description.', 'wp-graphql-gravityforms' ),
                ],
                'labelPlacement'   => [
                    'type'        => 'String',
                    'description' => __( 'Determines if the field labels are displayed on top of the fields (top_label), besides the fields and aligned to the left (left_label) or besides the fields and aligned to the right (right_label).', 'wp-graphql-gravityforms' ),
                ],
                'descriptionPlacement'   => [
                    'type'        => 'String',
                    'description' => __( 'Determines if the field description is displayed above the field input (i.e. immediately after the field label) or below the field input.', 'wp-graphql-gravityforms' ),
                ],
                'button'   => [
                    'type'        => '', // @TODO. Should resolve to JSON object.
                    'description' => __( 'Contains the form button settings such as the button text or image button source.', 'wp-graphql-gravityforms' ),
                ],
                'fields'   => [
                    'type'        => '', // @TODO. Should resolve to JSON array.
                    'description' => __( 'List of all fields that belong to the form.', 'wp-graphql-gravityforms' ),
                ],
                'version'   => [
                    'type'        => 'String',
                    'description' => __( 'Gravity Forms plugin version.', 'wp-graphql-gravityforms' ),
                ],
                'useCurrentUserAsAuthor'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'For forms with Post fields, this determines if the post should be created using the current logged in user as the author. 1 to use the current user, 0 otherwise.', 'wp-graphql-gravityforms' ),
                ],
                'postContentTemplateEnabled'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines if the post template functionality is enabled. When enabled, the post content will be created based on the template specified by postContentTemplate.', 'wp-graphql-gravityforms' ),
                ],
                'postTitleTemplateEnabled'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines if the post title template functionality is enabled. When enabled, the post title will be created based on the template specified by postTitleTemplate.', 'wp-graphql-gravityforms' ),
                ],
                'postTitleTemplate'   => [
                    'type'        => 'String',
                    'description' => __( 'Template to be used when creating the post title. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post title. Only applicable when postTitleTemplateEnabled is true', 'wp-graphql-gravityforms' ),
                ],
                'postContentTemplate'   => [
                    'type'        => 'String',
                    'description' => __( 'Template to be used when creating the post content. Field variables (i.e. {Name:3} ) can be added to the template to insert user submitted values into the post content. Only applicable when postContentTemplateEnabled is true.', 'wp-graphql-gravityforms' ),
                ],
                'lastPageButton'   => [
                    'type'        => '', // @TODO. Should resolve to JSON object.
                    'description' => __( 'Last page button data.', 'wp-graphql-gravityforms' ),
                ],
                'pagination'   => [
                    'type'        => 'String',
                    'description' => __( 'Pagination data.', 'wp-graphql-gravityforms' ),
                ],
                'firstPageCssClass'   => [
                    'type'        => 'String',
                    'description' => __( 'CSS class for the first page.', 'wp-graphql-gravityforms' ),
                ],
                'postAuthor'   => [
                    'type'        => 'Integer',
                    'description' => __( 'When useCurrentUserAsAuthor is set to 0, this property contains the user Id that will be used as the Post author.', 'wp-graphql-gravityforms' ),
                ],
                'postCategory'   => [
                    'type'        => 'Integer',
                    'description' => __( 'Form forms with Post fields, but without a Post Category field, this property determines the default category that the post will be associated with when created.', 'wp-graphql-gravityforms' ),
                ],
                'postFormat'   => [
                    'type'        => 'String',
                    'description' => __( 'For forms with Post fields, determines the format that the Post should be created with.', 'wp-graphql-gravityforms' ),
                ],
                'postStatus'   => [
                    'type'        => 'String',
                    'description' => __( 'For forms with Post fields, determines the status that the Post should be created with.', 'wp-graphql-gravityforms' ),
                ],
                'subLabelPlacement'   => [
                    'type'        => 'String',
                    'description' => __( 'How sub-labels are aligned.', 'wp-graphql-gravityforms' ),
                ],
                'cssClass'   => [
                    'type'        => 'String',
                    'description' => __( 'Custom CSS class. This class will be added to the <form> tag.', 'wp-graphql-gravityforms' ),
                ],
                'enableHoneypot'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Specifies if the form has the Honeypot spam-protection feature.', 'wp-graphql-gravityforms' ),
                ],
                'enableAnimation'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'When enabled, conditional logic hide/show operation will be performed with a jQuery slide animation. Only applicable to forms with conditional logic.', 'wp-graphql-gravityforms' ),
                ],
                'save'   => [
                    'type'        => '', // @TODO. Should resolve to JSON object.
                    'description' => __( '"Save and Continue" data.', 'wp-graphql-gravityforms' ),
                ],
                'limitEntries'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Specifies if this form has a limit on the number of submissions. 1 if the form limits submissions, 0 otherwise.', 'wp-graphql-gravityforms' ),
                ],
                'limitEntriesCount'   => [
                    'type'        => 'Integer',
                    'description' => __( 'When limitEntries is set to 1, this property specifies the number of submissions allowed.', 'wp-graphql-gravityforms' ),
                ],
                'limitEntriesPeriod' => [
                    'type'        => 'String',
                    'description' => __( 'When limitEntries is set to 1, this property specifies the time period during which submissions are allowed. Options are "day", "week", "month" and "year".', 'wp-graphql-gravityforms' ),
                ],
                'limitEntriesMessage' => [
                    'type'        => 'String',
                    'description' => __( 'Message that will be displayed when the maximum number of submissions have been reached.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleForm' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Specifies if this form is scheduled to be displayed only during a certain configured date/time.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleStart' => [
                    'type'        => 'String',
                    'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become active/visible.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleStartHour' => [
                    'type'        => 'Integer',
                    'description' => __( 'Hour (1 to 12) that the form will become active/visible.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleStartMinute' => [
                    'type'        => 'Integer',
                    'description' => __( 'Minute that the form will become active/visible.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleStartAmpm' => [
                    'type'        => 'String',
                    'description' => __( '"am? or "pm?. Applies to scheduleStartHour', 'wp-graphql-gravityforms' ),
                ],
                'scheduleEnd' => [
                    'type'        => 'String',
                    'description' => __( 'Date in the format (mm/dd/yyyy) that the form will become inactive/hidden.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleEndHour' => [
                    'type'        => 'Integer',
                    'description' => __( 'Hour (1 to 12) that the form will become inactive/hidden.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleEndMinute' => [
                    'type'        => 'Integer',
                    'description' => __( 'Minute that the form will become inactive/hidden.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleEndAmpm' => [
                    'type'        => 'String',
                    'description' => __( '"am? or "pm?. Applies to scheduleEndHour', 'wp-graphql-gravityforms' ),
                ],
                'schedulePendingMessage' => [
                    'type'        => 'String',
                    'description' => __( 'Message to be displayed when form is not yet available.', 'wp-graphql-gravityforms' ),
                ],
                'scheduleMessage' => [
                    'type'        => 'String',
                    'description' => __( 'Message to be displayed when form is no longer available', 'wp-graphql-gravityforms' ),
                ],
                'requireLogin' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Whether the form is configured to be displayed only to logged in users.', 'wp-graphql-gravityforms' ),
                ],
                'requireLoginMessage' => [
                    'type'        => 'String',
                    'description' => __( 'When requireLogin is set to true, this controls the message displayed when non-logged in user tries to access the form.', 'wp-graphql-gravityforms' ),
                ],
                // @TODO: Determine what these are. No documentation found.
                // 'gwreloadform_enable' => [
                //     'type'        => '',
                //     'description' => __( '', 'wp-graphql-gravityforms' ),
                // ],
                // 'gwreloadform_refresh_time' => [
                //     'type'        => '',
                //     'description' => __( '', 'wp-graphql-gravityforms' ),
                // ],
                'notifications' => [
                    'type'        => '', // @TODO. Should resolve to JSON object.
                    'description' => __( 'The properties for all the email notifications which exist for a form.', 'wp-graphql-gravityforms' ),
                ],
                'confirmations' => [
                    'type'        => 'Object',
                    'description' => __( 'Contains the form confirmation settings such as confirmation text or redirect URL', 'wp-graphql-gravityforms' ),
                ],
                'nextFieldId' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID to assign to the next field that is added to the form.', 'wp-graphql-gravityforms' ),
                ],
                'is_active' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines whether the form is active.', 'wp-graphql-gravityforms' ),
                ],
                'date_created' => [
                    'type'        => 'String',
                    'description' => __( 'The date the form was created in this format: "YYYY-MM-DD HH:mm:ss".', 'wp-graphql-gravityforms' ),
                ],
                'is_trash' => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines whether the form is in the trash.', 'wp-graphql-gravityforms' ),
                ],
            ],
        ] );
    }

    public function register_field() {
        register_graphql_field( 'RootQuery', 'gravityForm', [
            'description' => __( 'Get a Gravity Forms form.', 'wp-graphql-gravityforms' ),
            'type' => self::TYPE,
            'args' => [
				'id' => [
					'type' => [
						'non_null' => 'ID',
                    ],
                    'description' => __( 'The globally unique ID for the object', 'wp-graphql' ),
				],
			],
            'resolve' => function( $root, array $args ) {
                $id_parts = Relay::fromGlobalId( $args['id'] );

                if ( ! is_array( $id_parts ) || empty( $id_parts['id'] ) || empty( $id_parts['type'] ) ) {
                    throw new UserError( __( 'A valid global ID must be provided.' . $info, 'wp-graphql-gravityforms' ) );
                }

                $form = GFAPI::get_form( $id_parts['id'] );

                if ( ! $form ) {
                    throw new UserError( __( 'A valid form ID must be provided.' . $info, 'wp-graphql-gravityforms' ) );
                }

                return $form;
            }
        ] );
    }
}
