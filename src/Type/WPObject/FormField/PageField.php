<?php
/**
 * GraphQL Object Type - PageField
 *
 * @see https://docs.gravityforms.com/gf_field_page/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\Button\Button;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - PageField
 */
class PageField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PageField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'page';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Page field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			[
				'nextButton'     => [
					'type'        => Button::$type,
					'description' => __( 'An array containing the the individual properties for the "Next" button.', 'wp-graphql-gravity-forms' ),
				],
				// Although the property name is the same, this field is different than FieldProperty\PageNumberProperty.
				'pageNumber'     => [
					'type'        => 'Int',
					'description' => __( 'The page number of the current page.', 'wp-graphql-gravity-forms' ),
				],
				'previousButton' => [
					'type'        => Button::$type,
					'description' => __( 'An array containing the the individual properties for the "Previous" button.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
