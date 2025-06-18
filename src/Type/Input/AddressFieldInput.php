<?php
/**
 * GraphQL Input Type - AddressFieldInput
 * Input fields for address field.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;

/**
 * Class - AddressFieldInput
 */
class AddressFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for Address FormField.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'street'  => [
				'type'        => 'String',
				'description' => static fn () => __( 'Street address.', 'wp-graphql-gravity-forms' ),
			],
			'lineTwo' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Address line two.', 'wp-graphql-gravity-forms' ),
			],
			'city'    => [
				'type'        => 'String',
				'description' => static fn () => __( 'Address city.', 'wp-graphql-gravity-forms' ),
			],
			'state'   => [
				'type'        => 'String',
				'description' => static fn () => __( 'Address state/region/province name.', 'wp-graphql-gravity-forms' ),
			],
			'zip'     => [
				'type'        => 'String',
				'description' => static fn () => __( 'Address zip code.', 'wp-graphql-gravity-forms' ),
			],
			'country' => [
				'type'        => AddressFieldCountryEnum::$type,
				'description' => static fn () => __( 'Address country.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
