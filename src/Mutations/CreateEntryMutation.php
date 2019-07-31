<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;

use WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Create a Gravity Forms entry.
 */
class CreateEntryMutation implements Hookable {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'createGravityFormsEntry';

    /**
     * EntryDataManipulator instance.
     */
    private $entry_data_manipulator;

    public function __construct( EntryDataManipulator $entry_data_manipulator ) {
        $this->entry_data_manipulator = $entry_data_manipulator;
    }

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
    }

    public function register_mutation() {
        register_graphql_mutation( self::TYPE, [
            'inputFields'         => $this->get_input_fields(),
			'outputFields'        => $this->get_output_fields(),
			'mutateAndGetPayload' => $this->mutate_and_get_payload(),
        ] );
    }

	/**
	 * Defines the mutation input field configuration.
	 *
	 * @return array
	 */
	public static function get_input_fields() : array {
		return [
			'formId'   => [
				'type'        => 'Integer',
				'description' => __( 'The form ID.', 'wp-graphql-gravity-forms' ),
			],
            'allTextValues' => [
                'type'        => [ 'list_of' => EntryTextValueInput::TYPE ],
                'description' => __( 'Text values.', 'wp-graphql-gravity-forms' ),
            ],
		];
    }

	/**
	 * Defines the mutation output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'entry' => [
				'type'        => Entry::TYPE,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $payload, array $args, AppContext $context, ResolveInfo $info ) {
					if ( ! isset( $payload['id'] ) || ! absint( $payload['id'] ) ) {
						return null;
					}

                    $entry = GFAPI::get_entry( $payload['id'] );

                    if ( ! $entry ) {
                        return null;
                    }

                    return $this->entry_data_manipulator->manipulate( $entry );
				},
			],
		];
    }

	/**
	 * Defines the mutation data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['formId'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
            }

            $form = GFAPI::get_form( absint( $input['formId'] ) );

            if ( ! $form || ! $form['is_active'] || $form['is_trash'] ) {
                throw new UserError( __( 'The ID for a valid, active form must be provided.', 'wp-graphql-gravity-forms' ) );
            }

            $keys                  = wp_list_pluck( $input['allTextValues'], 'id' );
            $values                = wp_list_pluck( $input['allTextValues'], 'value' );
            $entry_data            = array_combine( $keys, $values );
            $entry_data['form_id'] = $form['id'];
            $entry_id              = GFAPI::add_entry( $entry_data );

            if ( is_wp_error( $entry_id ) ) {
                throw new UserError( __( 'An error occurred while trying to create the entry.', 'wp-graphql-gravity-forms' ) );
            }
 
			return [
				'id' => $entry_id,
			];
		};
	}
}
