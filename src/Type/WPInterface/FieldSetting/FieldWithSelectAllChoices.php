<?php
/**
 * GraphQL Interface for a FormField with the `select_all_choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\WPInterface\FieldWithChoices;
use WPGraphQL\GF\Type\WPInterface\FieldWithInputs;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithSelectAllChoices
 */
class FieldWithSelectAllChoices extends AbstractFieldSetting implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSelectAllChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'select_all_choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( ?TypeRegistry $type_registry = null ): array {
		$config = parent::get_type_config( $type_registry );

		$config['interfaces'] = static::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the \"select all\" choice should be displayed.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ): bool => ! empty( $source->enableSelectAll ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [
			FieldWithChoices::$type,
			FieldWithInputs::$type,
		];
	}
}
