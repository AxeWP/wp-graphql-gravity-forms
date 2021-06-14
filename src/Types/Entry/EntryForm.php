<?php
/**
 * GraphQL Edge Type - EntryForm
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 *
 * @package WPGraphQLGravityForms\Types\Entry
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Entry;

use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\DataManipulators\FormDataManipulator;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Utils\GFUtils;

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
	 * FormDataManipulator instance.
	 *
	 * @var FormDataManipulator
	 */
	private $form_data_manipulator;

	/**
	 * Constructor
	 *
	 * @param FormDataManipulator $form_data_manipulator .
	 */
	public function __construct( FormDataManipulator $form_data_manipulator ) {
		$this->form_data_manipulator = $form_data_manipulator;
	}

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		parent::register_hooks();
		add_action( 'graphql_register_types', [ $this, 'register_field' ] );
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
	 *
	 * @return array
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
						'node' => $this->form_data_manipulator->manipulate( $form ),
					];
				},
			]
		);
	}
}
