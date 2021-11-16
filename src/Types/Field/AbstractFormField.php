<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Types\Field
 * @since   0.7.0
 */

namespace WPGraphQL\GF\Types\Field;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\GraphQLInterface\FormFieldInterface;
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
	 * {@inheritDoc}
	 *
	 * @var boolean
	 */
	public static $should_load_eagerly = true;

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			static::$type,
			$this->get_type_config(
				[
					'description'     => $this->get_type_description(),
					'interfaces'      => [ FormFieldInterface::$type ],
					'fields'          => $this->prepare_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,

				]
			)
		);
	}


	/**
	 * Get the global properties that apply to all GF field types.
	 */
	protected function get_global_properties() : array {
		return FormFieldInterface::get_type_fields();
	}

	/**
	 * Get the custom properties.
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
