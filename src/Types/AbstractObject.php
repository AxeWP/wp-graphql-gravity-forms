<?php
/**
 * Abstract GraphQL Object Type.
 *
 * @package WPGraphQLGravityForms\Types
 * @since 0.7.0
 */

namespace WPGraphQLGravityForms\Types;

/**
 * Class - AbstractType
 */
abstract class AbstractObject extends AbstractType {
	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			static::$type,
			$this->get_type_config(
				[
					'description' => $this->get_type_description(),
					'fields'      => $this->prepare_fields(),
				]
			)
		);
	}
}
