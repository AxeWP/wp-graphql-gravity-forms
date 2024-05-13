<?php
/**
 * DataLoader - DraftEntries
 *
 * Loads Models for Gravity Forms DraftEntries.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Loader;

use GFFormsModel;
use GraphQL\Deferred;
use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\GF\Model\DraftEntry;

/**
 * Class - DraftEntriesLoader
 */
class DraftEntriesLoader extends AbstractDataLoader {
	/**
	 * Loader name. Same as the GraphQL Object.
	 *
	 * @var string
	 */
	public static string $name = 'gf_draft_entry';

	/**
	 * {@inheritDoc}
	 */
	protected function get_model( $entry, $key ): DraftEntry {
		return new DraftEntry( $entry, $key );
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		$loaded_entries = [];
		foreach ( $keys as $key ) {
			if ( empty( $key ) ) {
				continue;
			}

			$loaded_entries[ $key ] = GFFormsModel::get_draft_submission_values( $key ) ?: null;
		}

		return $loaded_entries;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception .
	 */
	public function load_deferred( $database_id ) {
		if ( empty( $database_id ) ) {
			return null;
		}

		$database_id = sanitize_text_field( $database_id );

		$this->buffer( [ $database_id ] );

		return new Deferred(
			function () use ( $database_id ) {
				return $this->load( $database_id );
			}
		);
	}
}
