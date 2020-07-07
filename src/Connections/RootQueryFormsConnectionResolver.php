<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;
use WPGraphQLGravityForms\DataManipulators\FormDataManipulator;
use WPGraphQLGravityForms\Types\Enum\FormStatusEnum;

class RootQueryFormsConnectionResolver {
    /**
	 * Resolves queries for forms.
	 *
	 * @param mixed       $source  The query results.
	 * @param array       $args    The query arguments.
	 * @param AppContext  $context The AppContext object.
	 * @param ResolveInfo $info    The ResolveInfo object.
	 *
	 * @return array|null The connection or null if no forms.
	 */
    public function resolve( $source, array $args, AppContext $context, ResolveInfo $info ) {
        $status = $this->get_form_status( $args );
        $forms  = GFAPI::get_forms( $status['active'], $status['trashed'] );

        if ( is_wp_error( $forms ) ) {
            throw new UserError( __( 'An error occurred while trying to get Gravity Forms forms.', 'wp-graphql-gravity-forms' ) );
        }

        if ( ! $forms ) {
            return null;
        }

        $form_data_manipulator = new FormDataManipulator( new FieldsDataManipulator() );
        $forms = array_map( fn( $form ) => $form_data_manipulator->manipulate( $form ), $forms );

        /**
         * "wp_graphql_gf_form_object" filter
         *
         * Provides the ability to manipulate the form data before it is sent to the
         * client. This hook is somewhat similar to Gravity Forms' gform_pre_render hook
         * and can be used for dynamic field input population, among other things.
         *
         * @param array $form Form meta array.
         */
        $forms = array_map( fn( $form ) => apply_filters( 'wp_graphql_gf_form_object', $form ), $forms );

        $connection = Relay::connectionFromArray( $forms, $args );

        $nodes = array_map( function( $edge ) {
            return ! empty( $edge['node'] ) ? $edge['node'] : null;
        }, $connection['edges'] );

        $connection['nodes'] = $nodes ?: null;

        return $connection;
    }

    private function get_form_status( array $args ) : array {
        $status = $args['where']['status'] ?? '';

        if ( FormStatusEnum::INACTIVE === $status ) {
            return [
                'active'  => false,
                'trashed' => false,
            ];
        }

        if ( FormStatusEnum::TRASHED === $status ) {
            return [
                'active'  => true,
                'trashed' => true,
            ];
        }

        if ( FormStatusEnum::INACTIVE_TRASHED === $status ) {
            return [
                'active'  => false,
                'trashed' => true,
            ];
        }

        // Get forms that are active and not in the trash by default.
        return [
            'active'  => true,
            'trashed' => false,
        ];
    }
}
