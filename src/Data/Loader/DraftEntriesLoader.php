<?php
/**
 * DataLoader - DraftEntries
 *
 * Loads Models for Gravity Forms DraftEntries.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.10.0
 */

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
	 * Given array of keys, loads and returns a map consisting of keys from `keys` array and loaded
	 * posts as the values
	 *
	 * Note that order of returned values must match exactly the order of keys.
	 * If some entry is not available for given key - it must include null for the missing key.
	 *
	 * For example:
	 * loadKeys(['a', 'b', 'c']) -> ['a' => 'value1, 'b' => null, 'c' => 'value3']
	 *
	 * @param array $keys .
	 *
	 * @return array|false
	 * @throws \Exception .
	 */
	public function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		$loaded_entries = [];
		foreach ( $keys as $key ) {
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
