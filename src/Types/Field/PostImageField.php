<?php
/**
 * GraphQL Object Type - PostImageField
 *
 * @see https://docs.gravityforms.com/post-image/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - PostImageField
 */
class PostImageField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'PostImageField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'post_image';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register hooks to WordPress.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms Post Image field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'displayCaption'     => [
							'type'        => 'Boolean',
							'description' => __( 'Controls the visibility of the caption metadata for Post Image fields. 1 will display the caption field, 0 will hide it.', 'wp-graphql-gravity-forms' ),
						],
						'displayDescription' => [
							'type'        => 'Boolean',
							'description' => __( 'Controls the visibility of the description metadata for Post Image fields. 1 will display the description field, 0 will hide it.', 'wp-graphql-gravity-forms' ),
						],
						'displayTitle'       => [
							'type'        => 'Boolean',
							'description' => __( 'Controls the visibility of the title metadata for Post Image fields. 1 will display the title field, 0 will hide it.', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
