<?php
/**
 * GraphQL Object Type - HiddenField
 *
 * @see https://docs.gravityforms.com/gf_field_hidden/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Hidden field.
 *
 * @see https://docs.gravityforms.com/gf_field_hidden/
 */
class HiddenField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'HiddenField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'hidden';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms Hidden field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DefaultValueProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\SizeProperty::get(),
					/**
					 * Deprecated field properties.
					 *
					 * @since 0.2.0
					 */

					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\IsRequiredProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\NoDuplicatesProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
