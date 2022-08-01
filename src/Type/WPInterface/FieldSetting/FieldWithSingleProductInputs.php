<?php
/**
 * GraphQL Interface for a Single Product Field inputs.
 *
 * This isnt a real GF setting, as the inputs are added directly to GF_Field_SingleProduct.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Registry\FieldInputRegistry;
/**
 * Class - FieldWithSingleProductInputs
 */
class FieldWithSingleProductInputs extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSingleProductInputs';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'single_product_inputs';

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_gf_register_form_field_inputs', [ __CLASS__, 'add_inputs' ], 10, 2 );
	}

	/**
	 * {@inheritDoc}
	 *
	 * The only added field is `inputs`, which is handled by the Input registry.
	 */
	public static function get_fields() : array {
		return [];
	}

	/**
	 * Registers the inputs to the SingleProductField.
	 *
	 * @param GF_Field $field The Gravity Forms Field object.
	 * @param array    $settings The `form_editor_field_settings()` key.
	 */
	public static function add_inputs( GF_Field $field, $settings ) : void {
		if ( 'singleproduct' !== $field->type ) {
			return;
		}

		error_log( 'adding input' );

		// Register the FieldInput for the object.
		FieldInputRegistry::register( $field, $settings );
	}
}
