<?php
/**
 * GraphQL Input Type - CreditCardInput
 * Input fields for address field.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\FormCreditCardTypeEnum;

/**
 * Class - CreditCardInput
 */
class CreditCardInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CreditCardInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Input fields for Address FormField.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'cardNumber'      => [
				'type'        => 'Int',
				'description' => __( 'Credit card number', 'wp-graphql-gravity-forms' ),
			],
			'expirationMonth' => [
				'type'        => 'Int',
				'description' => __( 'Credit card expiration month', 'wp-graphql-gravity-forms' ),
			],
			'expirationYear'  => [
				'type'        => 'Int',
				'description' => __( 'Credit Card expiration year', 'wp-graphql-gravity-forms' ),
			],
			'securityCode'    => [
				'type'        => 'ID',
				'description' => __( 'Credit card security code', 'wp-graphql-gravity-forms' ),
			],
			'cardholderName'  => [
				'type'        => 'String',
				'description' => __( 'Credit Card cardholder name', 'wp-graphql-gravity-forms' ),
			],
			'cardType'        => [
				'type'        => FormCreditCardTypeEnum::$type,
				'description' => __( 'The credit card type', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
