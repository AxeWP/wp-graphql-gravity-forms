<?php
/**
 * DataLoader - Entries
 *
 * Loads Models for Gravity Forms Entries.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Data\Loader;

use GF_Query;
use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\GF\Model\SubmittedEntry;

/**
 * Class - EntriesLoader
 */
class EntriesLoader extends AbstractDataLoader {
	/**
	 * Loader name. Same as the GraphQL Object.
	 *
	 * @var string
	 */
	public static string $name = 'gf_entry';

	/**
	 * {@inheritDoc}
	 */
	protected function get_model( $entry, $key ) : SubmittedEntry {
		return new SubmittedEntry( $entry );
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

		$gf_query        = new GF_Query();
		$entries_from_db = $gf_query->get_entries( $keys );
		// GF doesn't cache form queries so we're going to use the fetched array.
		$loaded_entries = [];
		foreach ( $entries_from_db as $entry ) {
			$loaded_entries [ $entry['id'] ] = $entry;
		}

		return array_combine( $keys, $loaded_entries );
	}
}
