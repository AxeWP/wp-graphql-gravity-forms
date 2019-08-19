<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQLRelay\Connection\ArrayConnection;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;

class FormFieldConnectionResolver extends AbstractConnectionResolver {
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
        return $this->source['fields'];
    }
}
