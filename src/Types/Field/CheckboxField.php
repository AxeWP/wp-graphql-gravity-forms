<?php
/**
 * GraphQL Object Type - CheckboxField
 *
 * @see https://docs.gravityforms.com/gf_field_checkbox/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - CheckboxField
 */
class CheckboxField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'CheckboxField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'checkbox';

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
				'description' => __( 'Gravity Forms Checkbox field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\ChoicesProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\EnableChoiceValueProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\SizeProperty::get(),
					[
						'inputs'          => [
							'type'        => [ 'list_of' => FieldProperty\CheckboxInputProperty::TYPE ],
							'description' => __( 'List of inputs. Checkboxes are treated as multi-input fields, since each checkbox item is stored separately.', 'wp-graphql-gravity-forms' ),
						],
						'enableSelectAll' => [
							'type'        => 'Boolean',
							'description' => __( 'Whether the "select all" choice should be displayed.', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			]
		);
	}
}
