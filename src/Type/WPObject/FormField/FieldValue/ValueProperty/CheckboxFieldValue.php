<?php
/**
 * GraphQL Object Type - CheckboxValuePropery
 * An individual property for the 'value' Checkbox field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Interfaces\TypeWithInterfaces;
use WPGraphQL\GF\Type\WPInterface\FieldValue\FieldValueWithChoice;
use WPGraphQL\GF\Type\WPInterface\FieldValue\FieldValueWithInput;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - CheckboxValueProperty
 */
class CheckboxFieldValue extends AbstractObject implements TypeWithInterfaces {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		$config = parent::get_type_config();

		$config['interfaces'] = self::get_interfaces();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual properties for each element of the Checkbox value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [
			FieldValueWithChoice::$type,
			FieldValueWithInput::$type,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value.', 'wp-graphql-gravity-forms' ),
			],
			'text'    => [
				'type'        => 'String',
				'description' => __( 'Input text.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
