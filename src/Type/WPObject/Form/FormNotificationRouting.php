<?php
/**
 * GraphQL Object Type - Gravity Forms form notification routing
 *
 * @see https://docs.gravityforms.com/notifications-object/#routing-rule-properties
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.0.1
 * @since   0.3.1 `fieldId` changed to type `Int`.
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\Enum\FormRuleOperatorEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormNotificationRouting
 */
class FormNotificationRouting extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormNotificationRouting';

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
			'fieldId'  => [
				'type'        => 'Int',
				'description' => __( 'Target field ID. The field that will have it’s value compared with the value property to determine if this rule is a match.', 'wp-graphql-gravity-forms' ),
			],
			'operator' => [
				'type'        => FormRuleOperatorEnum::$type,
				'description' => __( 'Operator to be used when evaluating this rule.', 'wp-graphql-gravity-forms' ),
			],
			'value'    => [
				'type'        => 'String',
				'description' => __( 'The value to compare with the field specified by fieldId.', 'wp-graphql-gravity-forms' ),
			],
			'email'    => [
				'type'        => 'String',
				'description' => __( 'The email or merge tag to be used as the email To address if this rule is a match.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
