<?php
/**
 * Abstract ValueProperty type.
 *
 * @since 0.5.0
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;
use WPGraphQL\AppContext;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - AbstractValueProperty
 */
abstract class AbstractValueProperty extends AbstractType {
	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name;

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
		register_graphql_field(
			static::$type,
			static::$field_name,
			$this->get_type_config(
				[
					'type'            => $this->get_field_type(),
					'description'     => $this->get_type_description(),
					'resolve'         => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						if ( ! isset( $root['source'] ) || ! is_array( $root['source'] ) ) {
							return null;
						}

						$value = static::get( $root['source'], $root );
						return $value;
					},
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}

	/**
	 * Get the field value.
	 *
	 * @todo stop returning array once fieldValue is removed.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return mixed Entry field value.
	 */
	abstract public static function get( array $entry, GF_Field $field );

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string|array
	 */
	abstract public function get_field_type();
}
