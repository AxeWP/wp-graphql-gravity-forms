<?php
/**
 * GraphQL Interface for a FormField with the `credit_card_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\FormCreditCardTypeEnum;

/**
 * Class - FieldWithCreditCard
 */
class FieldWithCreditCard extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCreditCard';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'credit_card_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'supportedCreditCards' => [
				'type'        => [ 'list_of' => FormCreditCardTypeEnum::$type ],
				'description' => __( 'The credit card type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->creditCards ) ? $source->creditCards : null,
			],
		];
	}
}
