<?php
/**
 * GraphQL Interface for a FormField with the `columns_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FormFieldChoices;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FieldWithColumns
 */
class FieldWithColumns extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithColumns';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'columns_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasColumns' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field should use multiple columns. Default is false.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableColumns ),
			],
		];
	}

	/**
	 * Register GraphQL fields to the FormField objects that implement this interface.
	 *
	 * @param GF_Field     $field The Gravity forms field.
	 * @param array        $settings The GF settings for the field.
	 * @param TypeRegistry $registry The WPGraphQL type registry.
	 */
	public static function register_object_fields( GF_Field $field, array $settings, TypeRegistry $registry ) : void {
		// Register the FieldChoice for the object.
		FormFieldChoices::register( $field, $settings, $registry );
	}
}
