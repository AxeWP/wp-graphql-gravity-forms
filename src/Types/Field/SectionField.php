<?php
/**
 * GraphQL Object Type - SectionField
 *
 * @see https://docs.gravityforms.com/gf_field_section/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;
/**
 * Class - SectionField
 */
class SectionField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SectionField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'section';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Section field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\DisplayOnlyProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			FieldProperty\SizeProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.2.0
			 */

			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
