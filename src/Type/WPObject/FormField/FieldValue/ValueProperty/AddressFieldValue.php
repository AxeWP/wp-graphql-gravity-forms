<?php
/**
 * GraphQL Object Type - AddressValuePropery
 * An individual property for the 'value' Address field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.5.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\Enum\AddressFieldCountryEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - AddressValueProperty
 */
class AddressFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual properties for each element of the address value field.', 'wp-graphql-gravity-forms' );
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
