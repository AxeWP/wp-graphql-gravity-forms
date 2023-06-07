<?php
/**
 * Registers all Gravity Forms fields to the GraphQL schema.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use GF_Fields;
use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Registry\FormFieldRegistry;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FormFields
 */
class FormFields implements Hookable, Registrable {
	/**
	 * {@inheritDoc}
	 *
	 * @var bool
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		// Hooks are applied later in the lifecycle, to ensure the TypeRegister is always up to date.
		self::register();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		$fields = GF_Fields::get_all();

		foreach ( $fields as $field ) {
			if ( ! in_array(
				$field->type,
				array_merge(
					Utils::get_ignored_gf_field_types(),
					[
						// These fields are registered as child types of a field interface, and should always be skipped.
						'calculation',
						'hiddenproduct',
						'singleproduct',
						'singleshipping',
						'price',
					]
				),
				true
			) ) {
				FormFieldRegistry::register( $field );
			}
		}
	}
}
