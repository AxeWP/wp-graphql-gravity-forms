<?php
/**
 * GraphQL Interface for a FormField with the `product_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;

/**
 * Class - FieldWithProductField
 */
class FieldWithProductField extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithProductFieldSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'product_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'productField'          => [
				'type'              => 'Int',
				'description'       => static fn () => __( 'The id of the product field to which the field is associated.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => static fn () => __( 'Use `connectedProductField` field instead.', 'wp-graphql-gravity-forms' ),
			],
			'connectedProductField' => [
				'type'        => 'ProductField',
				'description' => static fn () => __( 'The product field to which the field is associated.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( ! isset( $context->gfForm ) || empty( $source->productField ) ) {
						return null;
					}

					$id_for_loader = FormFieldsLoader::prepare_loader_id( $context->gfForm->databaseId, (int) $source->productField );

					return $context->get_loader( FormFieldsLoader::$name )->load_deferred( $id_for_loader );
				},
			],
		];
	}
}
