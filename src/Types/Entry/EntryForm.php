<?php
/**
 * GraphQL Edge Type - EntryForm
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 *
 * @package WPGraphQL\GF\Types\Entry
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Entry;

use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\DataManipulators\FormDataManipulator;
use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Form\Form;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 */
class EntryForm extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntryForm';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $field_name = 'form';

	/**
	 * {@inheritDoc}.
	 */
	public function register_hooks() : void {
		parent::register_hooks();
		add_action( get_graphql_register_action(), [ $this, 'register_field' ] );
	}

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description() : string {
		return __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
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
	public function register_field() : void {
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
