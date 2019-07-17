<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;

class EntryFieldConnectionResolver extends AbstractConnectionResolver {
    /**
     * @return bool Whether query should execute.
     */
    public function should_execute() : bool {
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
		return base64_encode( 'arrayconnection:' . $node['id'] );
	}

    /**
     * @return array The fields for this Gravity Forms entry.
     */
    public function get_query() : array {
        $entry = $this->source;
        $form  = GFAPI::get_form( $entry['formId'] );

        if ( ! $form ) {
            throw new UserError( __( 'The form used to generate this entry was not found.', 'wp-graphql-gravity-forms' ) );
        }

        return $form['fields'];
    }

    /**
     * @return array The fields for this Gravity Forms entry.
     */
    public function get_items() : array {
        return $this->get_query();
    }
}
