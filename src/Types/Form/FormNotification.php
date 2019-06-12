<?php

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Form notification.
 *
 * @see https://docs.gravityforms.com/notifications-object/
 */
class FormNotification implements Hookable {
    const TYPE = 'FormNotification';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Properties for all the email notifications which exist for a form.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'isActive'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Is the notification active or inactive. The default is true (active).', 'wp-graphql-gravityforms' ),
                ],
                'id'   => [
                    'type'        => 'String',
                    'description' => __( 'The notification ID. A 13 character unique ID.', 'wp-graphql-gravityforms' ),
                ],
                'name'   => [
                    'type'        => 'String',
                    'description' => __( 'The notification name.', 'wp-graphql-gravityforms' ),
                ],
                'service'   => [
                    'type'        => 'String',
                    'description' => __( 'The name of the service to be used when sending this notification. Default is wordpress.', 'wp-graphql-gravityforms' ),
                ],
                'event'   => [
                    'type'        => 'String',
                    'description' => __( 'The notification event. Default is form_submission.', 'wp-graphql-gravityforms' ),
                ],
                'to'   => [
                    'type'        => 'String',
                    'description' => __( 'The ID of an email field, an email address or merge tag to be used as the email to address.', 'wp-graphql-gravityforms' ),
                ],
                'toType'   => [
                    'type'        => 'String',
                    'description' => __( 'Identifies what to use for the notification to. Possible values: email, field, routing or hidden.', 'wp-graphql-gravityforms' ),
                ],
                'bcc'   => [
                    'type'        => 'String',
                    'description' => __( 'The email or merge tags to be used as the email bcc address.', 'wp-graphql-gravityforms' ),
                ],
                'subject'   => [
                    'type'        => 'String',
                    'description' => __( 'The email subject line. Merge tags supported.', 'wp-graphql-gravityforms' ),
                ],
                'message'   => [
                    'type'        => 'String',
                    'description' => __( 'The email body/content. Merge tags supported.', 'wp-graphql-gravityforms' ),
                ],
                'from'   => [
                    'type'        => 'String',
                    'description' => __( 'The email or merge tag to be used as the email from address.', 'wp-graphql-gravityforms' ),
                ],
                'fromName'   => [
                    'type'        => 'String',
                    'description' => __( 'The text or merge tag to be used as the email from name.', 'wp-graphql-gravityforms' ),
                ],
                'replyTo'   => [
                    'type'        => 'String',
                    'description' => __( 'The email or merge tags to be used as the email reply to address.', 'wp-graphql-gravityforms' ),
                ],
                // @TODO - https://docs.gravityforms.com/notifications-object/#routing-rule-properties
                // 'routing'   => [
                //     'type'        => '',
                //     'description' => __( 'An indexed array containing the routing rules.', 'wp-graphql-gravityforms' ),
                // ],
                // @TODO - https://docs.gravityforms.com/conditional-logic/
                // 'conditionalLogic'   => [
                //     'type'        => '',
                //     'description' => __( 'An associative array containing the conditional logic rules. See the Conditional Logic Object for more details.', 'wp-graphql-gravityforms' ),
                // ],
                'disableAutoformat'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines if the email message should be formatted so that paragraphs are automatically added for new lines. Default is false (auto-formatting enabled).', 'wp-graphql-gravityforms' ),
                ],
                'enableAttachments'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Determines if files uploaded on the form should be included when the notification is sent.', 'wp-graphql-gravityforms' ),
                ],
            ],
        ] );
    }
}
