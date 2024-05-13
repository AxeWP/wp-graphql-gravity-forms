<?php
/**
 * DataLoader - Entries
 *
 * Loads Models for Gravity Forms Entries.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Loader;

use GFAPI;
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
	protected function get_model( $entry, $key ): SubmittedEntry {
		return new SubmittedEntry( $entry );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		// Associate the requested keys with their loaded entry.
		$loaded_entries = [];
		foreach ( $keys as $key ) {
			if ( empty( $key ) ) {
				continue;
			}

			$entry                  = GFAPI::get_entry( (int) $key );
			$loaded_entries[ $key ] = ! $entry instanceof \WP_Error ? $entry : null;
		}

		return $loaded_entries;
	}
}
