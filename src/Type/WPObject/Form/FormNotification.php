<?php
/**
 * GraphQL Object Type - Gravity Forms form notification
 *
 * @see https://docs.gravityforms.com/notifications-object/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormNotificationToTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;

/**
 * Class - FormNotification
 */
class FormNotification extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormNotification';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Properties for all the email notifications which exist for a form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'bcc'                   => [
				'type'        => 'String',
				'description' => __( 'The email or merge tags to be used as the email bcc address.', 'wp-graphql-gravity-forms' ),
			],
			'conditionalLogic'      => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'An associative array containing the conditional logic rules. See the Conditional Logic Object for more details.', 'wp-graphql-gravity-forms' ),
			],
			'event'                 => [
				'type'        => 'String',
				'description' => __( 'The notification event. Default is form_submission.', 'wp-graphql-gravity-forms' ),
			],
			'from'                  => [
				'type'        => 'String',
				'description' => __( 'The email or merge tag to be used as the email from address.', 'wp-graphql-gravity-forms' ),
			],
			'fromName'              => [
				'type'        => 'String',
				'description' => __( 'The text or merge tag to be used as the email from name.', 'wp-graphql-gravity-forms' ),
			],
			'id'                    => [
				'type'        => 'String',
				'description' => __( 'The notification ID. A 13 character unique ID.', 'wp-graphql-gravity-forms' ),
			],
			'isActive'              => [
				'type'        => 'Boolean',
				'description' => __( 'Is the notification active or inactive. The default is true (active).', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => isset( $source['isActive'] ) ? (bool) $source['isActive'] : true,
			],
			'isAutoformatted'       => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the email message should be formatted so that paragraphs are automatically added for new lines.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => empty( $source['disableAutoformat'] ),
			],
			'message'               => [
				'type'        => 'String',
				'description' => __( 'The email body/content. Merge tags supported.', 'wp-graphql-gravity-forms' ),
			],
			'name'                  => [
				'type'        => 'String',
				'description' => __( 'The notification name.', 'wp-graphql-gravity-forms' ),
			],
			'service'               => [
				'type'        => 'String',
				'description' => __( 'The name of the service to be used when sending this notification. Default is wordpress.', 'wp-graphql-gravity-forms' ),
			],
			'replyTo'               => [
				'type'        => 'String',
				'description' => __( 'The email or merge tags to be used as the email reply to address.', 'wp-graphql-gravity-forms' ),
			],
			'routing'               => [
				'type'        => [ 'list_of' => FormNotificationRouting::$type ],
				'description' => __( 'Routing rules.', 'wp-graphql-gravity-forms' ),
			],
			'shouldSendAttachments' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if files uploaded on the form should be included when the notification is sent.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['enableAttachments'] ),
			],
			'subject'               => [
				'type'        => 'String',
				'description' => __( 'The email subject line. Merge tags supported.', 'wp-graphql-gravity-forms' ),
			],
			'to'                    => [
				'type'        => 'String',
				'description' => __( 'The ID of an email field, an email address or merge tag to be used as the email to address.', 'wp-graphql-gravity-forms' ),
			],
			'toType'                => [
				'type'        => FormNotificationToTypeEnum::$type,
				'description' => __( 'Identifies what to use for the notification "to".', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
