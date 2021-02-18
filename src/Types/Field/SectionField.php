<?php
/**
 * GraphQL Object Type - SectionField
 *
 * @see https://docs.gravityforms.com/gf_field_section/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;
/**
 * Class - SectionField
 */
class SectionField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'SectionField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'section';

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
				'description' => __( 'Gravity Forms Section field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					/**
					 * Depreciated field properties.
					 *
					 * @since 0.1.0
					 */

					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
