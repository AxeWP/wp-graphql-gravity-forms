<?php
/**
 * ConnectionResolver - RootQueryEntry
 *
 * Resolves connections to Entries.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GF_Query;
use GraphQL\Error\UserError;
use GraphQLRelay\Connection\ArrayConnection;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQLGravityForms\Data\Loader\EntriesLoader;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersModeEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersOperatorInputEnum;

/**
 * Class - EntriesConnectionResolver
 */
class EntriesConnectionResolver extends AbstractConnectionResolver {
	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	public function should_execute() : bool {
		/**
		 * Filter to control whether the user should be allowed to view entries.
		 *
		 * @since 0.5.0
		 *
		 * @param bool $can_view_entries Whether the current user should be allowed to view form entries.
		 * @param array|int $form_ids The specific form IDs being queried. Returns `0` if querying entries from all forms.
		 */
		$can_user_view_entries = apply_filters( 'wp_graphql_gf_can_view_entries', current_user_can( 'gravityforms_view_entries' ) || current_user_can( 'gform_full_access' ), $this->get_form_ids() );

		if ( ! $can_user_view_entries ) {
			throw new UserError( __( 'Sorry, you are not allowed to view Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
		}

		return $can_user_view_entries;
	}

	/**
	 * Return the name of the loader to be used with the connection resolver
	 *
	 * @return string
	 */
	public function get_loader_name() : string {
		return EntriesLoader::$name;
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
	public function is_valid_offset( $offset ) {
		return GFAPI::entry_exists( $offset );
	}

	/**
	 * Validates Model.
	 *
	 * If model isn't a class with a `fields` member, this function with have be overridden in
	 * the Connection class.
	 *
	 * @param array $model model.
	 *
	 * @return bool
	 */
	protected function is_valid_model( $model ) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_query_args() : array {
		$query_args = [
			'form_ids'        => $this->get_form_ids(),
			'search_criteria' => $this->get_search_criteria(),
			'sorting'         => $this->get_sort(),
			'paging'          => $this->get_paging(),
		];

		/**
		 * Filter the $query args to allow folks to customize queries programmatically
		 *
		 * @param array       $query_args The args that will be passed to the WP_Query
		 * @param mixed       $source     The source that's passed down the GraphQL queries
		 * @param array       $args       The inputArgs on the field
		 * @param AppContext  $context    The AppContext passed down the GraphQL tree
		 * @param ResolveInfo $info       The ResolveInfo passed down the GraphQL tree
		 */
		$query_args = apply_filters( 'wp_graphql_gf_entries_connection_query_args', $query_args, $this->source, $this->args, $this->context, $this->info );

		return $query_args;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return GF_Query
	 */
	public function get_query() : GF_Query {
		$form_ids        = $this->query_args['form_ids'];
		$search_criteria = $this->query_args['search_criteria'];
		$sorting         = $this->query_args['sorting'];
		$paging          = $this->query_args['paging'];

		return new GF_Query( $form_ids, $search_criteria, $sorting, $paging );
	}
	/**
	 * {@inheritDoc}
	 */
	public function get_offset() {

		/**
		 * Defaults
		 */
		$offset = 0;

		/**
		 * Get the $after offset
		 */
		if ( ! empty( $this->args['after'] ) ) {
			$offset = ArrayConnection::cursorToOffset( $this->args['after'] ) + 1;
		} elseif ( ! empty( $this->args['before'] ) ) {
			$offset = ArrayConnection::cursorToOffset( $this->args['before'] ) + 1;
		}

		/**
		 * Return the higher of the two values
		 */
		return max( 0, $offset );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_cursor_for_node( $id ) : string {
		$index = $this->get_offset() + array_search( $id, array_keys( $this->nodes ), true );
		return base64_encode( 'arrayconnection:' . $index );  // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_ids() : array {
		return $this->query->get_ids();
	}


	/**
	 * Returns form ids.
	 *
	 * @return array|int
	 */
	private function get_form_ids() {
		if ( ! empty( $this->args['where']['formIds'] ) && is_array( $this->args['where']['formIds'] ) ) {
			return array_map( 'absint', $this->args['where']['formIds'] );
		}

		return 0; // Return all forms.
	}

	/**
	 * Gets search criteria for entry Ids.
	 *
	 * @return array
	 */
	private function get_search_criteria() : array {
		$search_criteria = $this->apply_status_to_search_criteria( [] );

		if ( ! empty( $this->args['where']['dateFilters']['startDate'] ) ) {
			$search_criteria['start_date'] = sanitize_text_field( $this->args['where']['dateFilters']['startDate'] );
		}

		if ( ! empty( $this->args['where']['dateFilters']['endDate'] ) ) {
			$search_criteria['end_date'] = sanitize_text_field( $this->args['where']['dateFilters']['endDate'] );
		}

		if ( ! empty( $this->args['where']['fieldFilters'] ) && is_array( $this->args['where']['fieldFilters'] ) ) {
			$search_criteria['field_filters'] = array_merge(
				[ 'mode' => $this->args['where']['fieldFiltersMode'] ?? FieldFiltersModeEnum::ALL ],
				$this->format_field_filters( $this->args['where']['fieldFilters'] )
			);
		}

		return $search_criteria;
	}

	/**
	 * Adds 'status' value to search criteria.
	 *
	 * @param array $search_criteria The search criteria for the entry Ids.
	 * @return array
	 */
	private function apply_status_to_search_criteria( array $search_criteria ) : array {
		$status = $this->args['where']['status'] ?? EntryStatusEnum::ACTIVE; // Default to active entries.

		// For all entries, don't add a 'status' value to search criteria.
		if ( 'ALL' === $status ) {
			return $search_criteria;
		}

		$search_criteria['status'] = sanitize_text_field( $status );

		return $search_criteria;
	}

	/**
	 * Correctly formats the field filters for search criteria.
	 *
	 * @param array $field_filters .
	 * @return array
	 */
	private function format_field_filters( array $field_filters ) : array {
		return array_reduce(
			$field_filters,
			function( $field_filters, $field_filter ) {
				if ( ! isset( $field_filter['key'] ) ) {
					throw new UserError( __( 'Every field filter must have a key.', 'wp-graphql-gravity-forms' ) );
				}

				$key             = empty( $field_filter['key'] ) ? 0 : sanitize_text_field( $field_filter['key'] );
				$operator        = $field_filter['operator'] ?? FieldFiltersOperatorInputEnum::IN; // Default to "in".
				$value           = $this->get_field_filter_value( $field_filter, $operator );
				$field_filters[] = compact( 'key', 'operator', 'value' );

				return $field_filters;
			},
			[]
		);
	}

	/**
	 * Gets filter value for each field used in search criteria.
	 *
	 * @param array  $field_filter Field filter.
	 * @param string $operator     Operator.
	 *
	 * @return mixed Filter value.
	 *
	 * @throws UserError Field filters must have one value field.
	 */
	private function get_field_filter_value( array $field_filter, string $operator ) {
		$value_fields = $this->get_field_filter_value_fields( $field_filter );

		if ( 1 !== count( $value_fields ) ) {
			throw new UserError( __( 'Every field filter must have one value field.', 'wp-graphql-gravity-forms' ) );
		}

		$field_filter_values = array_map( 'sanitize_text_field', $field_filter[ $value_fields[0] ] );

		if ( $this->should_field_filter_be_limited_to_single_value( $operator ) ) {
			return $field_filter_values[0] ?? '';
		}

		return $field_filter_values;
	}

	/**
	 * Returns whether field filter should be limited to a single value.
	 *
	 * @param string $operator Operator.
	 * @return boolean
	 */
	private function should_field_filter_be_limited_to_single_value( string $operator ) : bool {
		$operators_to_limit = [
			FieldFiltersOperatorInputEnum::CONTAINS,
			FieldFiltersOperatorInputEnum::GREATER_THAN,
			FieldFiltersOperatorInputEnum::LESS_THAN,
		];

		return in_array( $operator, $operators_to_limit, true );
	}

	/**
	 * Get value fields for the field filter.
	 *
	 * @param array $field_filter .
	 * @return array
	 */
	private function get_field_filter_value_fields( array $field_filter ) : array {
		return array_values(
			array_filter(
				[ 'stringValues', 'intValues', 'floatValues', 'boolValues' ],
				function( $value_field ) use ( $field_filter ) {
					return ! empty( $field_filter[ $value_field ] );
				}
			)
		);
	}

	/**
	 * Get sort argument for entry ID query.
	 *
	 * @return array
	 */
	private function get_sort() : array {
		// Set default sort direction.
		$sort = [
			'direction' => 'DESC',
		];

		if ( ! empty( $this->args['where']['sort'] ) && is_array( $this->args['where']['sort'] ) ) {
			$sort = [
				'key'        => $this->args['where']['sort']['key'] ?? '',
				'direction'  => $this->args['where']['sort']['direction'] ?? 'ASC',
				'is_numeric' => $this->args['where']['sort']['isNumeric'] ?? false,
			];
		}

		// Flip the direction on `last` query.
		if ( ! empty( $this->args['last'] ) ) {
			$sort['direction'] = 'ASC' === $sort['direction'] ? 'DESC' : 'ASC';
		}

		return $sort;
	}

	/**
	 * Get paging arguments for entry ID query.
	 *
	 * @return array
	 * @throws UserError When using unsupported pagination.
	 */
	private function get_paging() : array {
		/**
		 * Throw error if querying by first + before.
		 *
		 * @todo support `first` + `before`.
		 */
		if ( ! empty( $this->args['first'] ) && ! empty( $this->args['before'] ) ) {
				throw new UserError( __( 'Sorry, `before` pagination is currently not supported when `first` is set.', 'wp-graphql-gravity-forms' ) );
		}

		/**
		 * Throw error if querying by last + after.
		 *
		 * @todo support `last` + `after`
		 */
		if ( ! empty( $this->args['last'] ) && ! empty( $this->args['after'] ) ) {
				throw new UserError( __( 'Sorry, `last` pagination is currently not supported when `after` is set.', 'wp-graphql-gravity-forms' ) );
		}

		$offset = $this->get_offset();

		return [
			'offset'    => $offset,
			'page_size' => $this->get_query_amount(),
		];
	}
}
