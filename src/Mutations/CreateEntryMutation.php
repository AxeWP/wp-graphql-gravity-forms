<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;

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
			'projectId'      => [
				'type'        => 'Integer',
				'description' => __( 'Project ID.', 'wp-graphql-gravity-forms' ),
			],
			'inspectionType'      => [
				'type'        => 'String',
				'description' => __( 'Type of inspection: weekly or monthly.', 'wp-graphql-gravity-forms' ),
			],
			'inspectionDate'      => [
				'type'        => 'String',
				'description' => __( 'The date of the inspection in mm/dd/yyyy format.', 'wp-graphql-gravity-forms' ),
			],
			'inspectors'      => [
				'type'        => 'String',
				'description' => __( 'Comma-separated list of user IDs.', 'wp-graphql-gravity-forms' ),
			],
			'policiesPosted'      => [
				'type'        => 'String',
				'description' => __( 'Whether policies are posted.', 'wp-graphql-gravity-forms' ),
			],
			'oshaBookPosted'      => [
				'type'        => 'String',
				'description' => __( 'Whether OSHA book is posted.', 'wp-graphql-gravity-forms' ),
			],
			'emergencyProceduresPosted'      => [
				'type'        => 'String',
				'description' => __( 'Whether emergency procedures are posted.', 'wp-graphql-gravity-forms' ),
			],
			'startUpPackagePosted'      => [
				'type'        => 'String',
				'description' => __( 'If posted.', 'wp-graphql-gravity-forms' ),
			],
			'workerRepsPosted'      => [
				'type'        => 'String',
				'description' => __( 'If posted.', 'wp-graphql-gravity-forms' ),
			],
			'firstAidCertificatesPosted'      => [
				'type'        => 'String',
				'description' => __( 'If posted.', 'wp-graphql-gravity-forms' ),
			],
			'noticeOfProjectPosted'      => [
				'type'        => 'String',
				'description' => __( 'If posted.', 'wp-graphql-gravity-forms' ),
			],
			'fieldReportsPosted'      => [
				'type'        => 'String',
				'description' => __( 'If posted.', 'wp-graphql-gravity-forms' ),
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

            $fields_to_include = array_filter( $form['fields'], function( $field ) {
                $types_to_exclude = ['page', 'section', 'html', 'honeypot', 'signature'];
                return ! in_array( $field['type'], $types_to_exclude, true );
            } );

            // TODO: SUPPORT SIGNATURE ^

            $field_ids  = wp_list_pluck( $fields_to_include, 'id' );

            $field_values = [
                $input['projectId'] ?? 0, // Project
                $input['projectId'] ?? 0, // Project Number
                $input['projectId'] ?? 0, // Project Location
                $input['projectId'] ?? 0, // Project Manager
                $input['inspectionType'] ?? '',
                $input['inspectionDate'] ?? '',
                isset( $input['inspectors'] ) ? '[' . $input['inspectors'] . ']' : '', // Comma-separated list of user IDs
                $input['policiesPosted'] ?? '',
                $input['oshaBookPosted'] ?? '',
                $input['emergencyProceduresPosted'] ?? '',
                $input['startUpPackagePosted'] ?? '',
                $input['workerRepsPosted'] ?? '',
                $input['firstAidCertificatesPosted'] ?? '',
                $input['noticeOfProjectPosted'] ?? '',
                $input['fieldReportsPosted'] ?? '',
            ];

            $entry_data            = array_combine( $field_ids, $field_values );
            $entry_data['form_id'] = $form['id'];

            $entry_id = GFAPI::add_entry( $entry_data );

            if ( is_wp_error( $entry_id ) ) {
                throw new UserError( __( 'An error occurred while trying to create the entry.', 'wp-graphql-gravity-forms' ) );
            }
 
			return [
				'id' => $entry_id,
			];
		};
	}
}
