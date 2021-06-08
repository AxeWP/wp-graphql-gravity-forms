<?php
/**
 * Abstract GraphQL Object Type.
 *
 * @package WPGraphQLGravityForms\Types;
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
					'fields'      => $fields,
				]
			)
		);
	}

	/**
	 * Gets the Field type description.
	 *
	 * @return string
	 */
	abstract protected function get_type_description() : string;

	/**
	 * Gets the properties for the Field.
	 *
	 * @todo convert to abstract class.
	 * @return array
	 */
	protected function get_type_fields() : array {
		return [];
	}
}
