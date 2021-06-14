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

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;
/**
 * Class - AbstractFormField
 */
abstract class AbstractFormField extends AbstractObject {
	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type;

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		/**
		 * Call deprecated get_properties() function, in case it's used in a child class.
		 *
		 * @since 0.6.4
		 */
		$fields = $this->get_type_fields();
		if ( method_exists( $this, 'get_properties' ) ) {
			_deprecated_function( 'get_properties', '0.6.4', 'get_type_fields' );
			$fields = array_merge( $fields, $this->get_properties() );
		}

		register_graphql_object_type(
			static::$type,
			$this->get_type_config(
				[
					'description' => $this->get_type_description(),
					'interfaces'  => [ FormFieldInterface::$type ],
					'fields'      => $fields,
				]
			)
		);
	}

	/**
	 * Get the global properties that apply to all GF field types.
	 *
	 * @return array
	 */
	protected function get_global_properties() : array {
		return FormFieldInterface::get_type_fields();
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
