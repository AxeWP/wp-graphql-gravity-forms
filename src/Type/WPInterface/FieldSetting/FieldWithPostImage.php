<?php
/**
 * GraphQL Interface for a FormField with the `conditional_logic_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithPostImage
 */
class FieldWithPostImage extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostImageSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_image_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );

		$config['interfaces'] = static::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasAlt'         => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the alt metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->displayAlt ),
			],
			'hasCaption'     => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the caption metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->displayCaption ),
			],
			'hasDescription' => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the description metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->displayDescription ),
			],
			'hasTitle'       => [
				'type'        => 'Boolean',
				'description' => __( 'Controls the visibility of the title metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->displayTitle ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [ FieldWithFileExtensions::$type ];
	}
}
