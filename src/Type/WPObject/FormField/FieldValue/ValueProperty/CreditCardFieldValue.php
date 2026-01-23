<?php
/**
 * GraphQL Object Type - CreditCardValueProperty
 * An individual property for the 'value' CreditCard field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - CreditCardValueProperty
 */
class CreditCardFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CreditCardFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual properties for each element of the Credit Card value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'cardNumber'      => [
				'type'        => 'String',
				'description' => static fn () => __( 'Credit card number.', 'wp-graphql-gravity-forms' ),
			],
			'expirationMonth' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Credit card expiration month.', 'wp-graphql-gravity-forms' ),
			],
			'expirationYear'  => [
				'type'        => 'String',
				'description' => static fn () => __( 'Credit card expiration year.', 'wp-graphql-gravity-forms' ),
			],
			'securityCode'    => [
				'type'        => 'String',
				'description' => static fn () => __( 'Credit card security code.', 'wp-graphql-gravity-forms' ),
			],
			'cardholderName'  => [
				'type'        => 'String',
				'description' => static fn () => __( 'Credit card cardholder name.', 'wp-graphql-gravity-forms' ),
			],
			'cardType'        => [
				'type'        => 'String',
				'description' => static fn () => __( 'The credit card type.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
