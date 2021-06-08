<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\AbstractType;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;
/**
 * Class - AbstractField
 */
abstract class AbstractField extends AbstractType {
	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type;

	/**
	 * Get the custom properties for the WPGraphQL type config array.
	 */
	public function get_custom_config_properties() : array {
		return [ 'interfaces' => [ FormFieldInterface::TYPE ] ];
	}

	/**
	 * Get the global properties that apply to all GF field types.
	 *
	 * @return array
	 */
	protected function get_global_properties() : array {
		return FormFieldInterface::get_properties();
	}

	/**
	 * Get the custom properties.
	 *
	 * @return array
	 */
	protected function get_custom_properties() : array {
		/**
		 * Add GraphQL fields for custom field properties.
		 *
		 * @param array Additional GraphQL field definitions.
		 * @param array The type of Gravity Forms field.
		 */
		return apply_filters( 'wp_graphql_gf_custom_properties', [], static::$gf_type );
	}
}
