<?php
/**
 * GraphQL Object Type - AddressField
 *
 * @see https://docs.gravityforms.com/gf_field_date/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - DateField
 */
class DateField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'DateField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'date';

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
				'description' => __( 'Gravity Forms Date field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\DefaultValueProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\PlaceholderProperty::get(),
					FieldProperty\SizeProperty::get(),
					[
						/**
						 * Possible values: Possible values: calendar, custom, none
						 */
						'calendarIconType' => [
							'type'        => 'String',
							'description' => __( 'Determines how the date field displays it’s calendar icon.', 'wp-graphql-gravity-forms' ),
						],
						'calendarIconUrl'  => [
							'type'        => 'String',
							'description' => __( 'Contains the URL to the custom calendar icon. Only applicable when calendarIconType is set to custom.', 'wp-graphql-gravity-forms' ),
						],
						/**
						 * Possible values: mdy, dmy
						 */
						'dateFormat'       => [
							'type'        => 'String',
							'description' => __( 'Determines how the date is displayed.', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
