<?php
/**
 * Settings - WPGraphQL Settings
 *
 * @package WPGraphQLGravityForms\Settings
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Settings;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
/**
 * WPGraphQL Settings.
 */
class WPGraphQLSettings implements Hookable {
	/**
	 * {@inheritDoc}.
	 *
	 * @TODO: This should be a filter.
	 *
	 * @see: https://www.wpgraphql.com/filters/graphql_connection_max_query_amount/
	 */
	public function register_hooks() : void {
		add_filter( 'graphql_connection_max_query_amount', [ $this, 'set_max_query_amount' ], 11, 5 );
	}

	/**
	 * Bump max query amount to account for forms with many fields.
	 *
	 * @param int         $max_query_amount Max query amount.
	 * @param mixed       $source     source passed down from the resolve tree.
	 * @param array       $args       array of arguments input in the field as part of the GraphQL query.
	 * @param AppContext  $context    Object containing app context that gets passed down the resolve tree.
	 * @param ResolveInfo $info       Info about fields passed down the resolve tree.
	 *
	 * @return int Max query amount, possibly bumped.
	 */
	public function set_max_query_amount( int $max_query_amount, $source, array $args, AppContext $context, ResolveInfo $info ) : int {
		if ( 'formFields' === $info->fieldName ) {
			return (int) max( $max_query_amount, 600 );
		}
		return $max_query_amount;
	}
}
