<?php
/**
 * GraphQL Input Type - CreditCardFieldInput
 * Input fields for credit card field.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\FormCreditCardTypeEnum;

/**
 * Class - CreditCardFieldInput
 */
class CreditCardFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CreditCardFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for Credit Card FormField.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'cardNumber'      => [
				'type'        => 'Int',
				'description' => __( 'Credit card number.', 'wp-graphql-gravity-forms' ),
			],
			'expirationMonth' => [
				'type'        => 'Int',
				'description' => __( 'Credit card expiration month.', 'wp-graphql-gravity-forms' ),
			],
			'expirationYear'  => [
				'type'        => 'Int',
				'description' => __( 'Credit Card expiration year.', 'wp-graphql-gravity-forms' ),
			],
			'securityCode'    => [
				'type'        => 'ID',
				'description' => __( 'Credit card security code.', 'wp-graphql-gravity-forms' ),
			],
			'cardholderName'  => [
				'type'        => 'String',
				'description' => __( 'Credit Card cardholder name.', 'wp-graphql-gravity-forms' ),
			],
			'cardType'        => [
				'type'        => FormCreditCardTypeEnum::$type,
				'description' => __( 'The credit card type.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
