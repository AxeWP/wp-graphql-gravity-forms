<?php
/**
 * Enum Type - FormFieldTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\Type\WPEnumType;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FormFieldTypeEnum
 */
class FormFieldTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldTypeEnum';


	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Field Type.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		$fields = Utils::get_registered_form_field_types();

		$values = [];

		foreach ( $fields as $gf_type => $type ) {
			$values[ WPEnumType::get_safe_name( $gf_type ) ] = [
				'value'       => $gf_type,
				// translators: GF Field type.
				'description' => sprintf( __( 'A Gravity Forms %s field.', 'wp-graphql-gravity-forms' ), $gf_type ),
			];
		}

		return $values;
	}
}
