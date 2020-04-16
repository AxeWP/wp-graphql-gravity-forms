<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQLRelay\Connection\ArrayConnection;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;

class EntryFieldConnectionResolver extends AbstractConnectionResolver {
    /**
     * @return bool Whether query should execute.
     */
    public function should_execute() : bool {
        return true;
    }

    /**
     * Determine whether or not the the offset is valid, i.e the item corresponding to the offset exists.
	 * Offset is equivalent to WordPress ID (e.g post_id, term_id). So this function is equivalent
	 * to checking if the WordPress object exists for the given ID.
     *
     * @param int $offset The offset.
     *
     * @return bool Whether the offset is valid.
     */
    public function is_valid_offset( $offset ) : bool {
        return true;
    }

    /**
     * @return array Query arguments.
     */
    public function get_query_args() : array {
        return [];
    }

    /**
     * @return string Base-64 encoded cursor value.
     */
	protected function get_cursor_for_node( $node, $key = null ) : string {
		return base64_encode( ArrayConnection::PREFIX . $node['id'] );
	}

    /**
     * @return array Query to use for data fetching.
     */
    public function get_query() : array {
        return [];
    }

    /**
     * @return array The fields for this Gravity Forms entry.
     */
    public function get_items() : array {
        $entry = $this->source;
        $form  = GFAPI::get_form( $entry['formId'] );

        if ( ! $form ) {
            throw new UserError( __( 'The form used to generate this entry was not found.', 'wp-graphql-gravity-forms' ) );
        }

        return ( new FieldsDataManipulator() )->manipulate( $form['fields'] );
    }
}
