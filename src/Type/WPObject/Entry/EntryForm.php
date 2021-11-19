<?php
/**
 * GraphQL Edge Type - EntryForm
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 *
 * @package WPGraphQL\GF\Type\WPObject\Entry
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\DataManipulators\FormDataManipulator;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 */
class EntryForm extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryForm';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'form';

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			static::prepare_config(
				[
					'description'     => static::get_description(),
					'fields'          => static::get_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'node' => [
				'type'        => Form::$type,
				'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Register form query.
	 */
	public static function register_field() : void {
		register_graphql_field(
			Entry::$type,
			self::$field_name,
			[
				'type'        => self::$type,
				'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $entry ) : array {
					$form = GFUtils::get_form( $entry['formId'], false );

					return [
						'node' => FormDataManipulator::manipulate( $form ),
					];
				},
			]
		);
	}
}
