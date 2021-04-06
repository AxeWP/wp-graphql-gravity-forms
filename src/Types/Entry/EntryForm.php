<?php
/**
 * GraphQL Edge Type - EntryForm
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 *
 * @package WPGraphQLGravityForms\Types\Entry
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Entry;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\DataManipulators\FormDataManipulator;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Creates a 1:1 relationship between an Entry and the Form associated with it.
 */
class EntryForm implements Hookable, Type, Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'EntryForm';

	/**
	 * Field registered in WPGraphQL.
	 */
	const FIELD = 'form';

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
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
		add_action( 'graphql_register_types', [ $this, 'register_field' ] );
	}

	/**
	 * Register new edge type.
	 */
	public function register_type() : void {
		register_graphql_type(
			self::TYPE,
			[
				'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'node' => [
						'type'        => Form::TYPE,
						'description' => __( 'The Gravity Forms form associated with the entry.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}

	/**
	 * Register form query.
	 */
	public function register_field() : void {
		register_graphql_field(
			Entry::TYPE,
			self::FIELD,
			[
				'type'        => self::TYPE,
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
