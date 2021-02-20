<?php
/**
 * GraphQL Object Type - PageField
 *
 * @see https://docs.gravityforms.com/gf_field_page/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Button\Button;
use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - PageField
 */
class PageField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'PageField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'page';

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
				'description' => __( 'Gravity Forms Page field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\DisplayOnlyProperty::get(),
					FieldProperty\SizeProperty::get(),
					[
						'nextButton'     => [
							'type'        => Button::TYPE,
							'description' => __( 'An array containing the the individual properties for the "Next" button.', 'wp-graphql-gravity-forms' ),
						],
						// Although the property name is the same, this field is different than FieldProperty\PageNumberProperty.
						'pageNumber'     => [
							'type'        => 'Integer',
							'description' => __( 'The page number of the current page.', 'wp-graphql-gravity-forms' ),
						],
						'previousButton' => [
							'type'        => Button::TYPE,
							'description' => __( 'An array containing the the individual properties for the "Previous" button.', 'wp-graphql-gravity-forms' ),
						],
					],
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
					Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\LabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
