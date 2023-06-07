<?php
/**
 * DataLoader - Forms
 *
 * Loads Models for Gravity Forms Forms.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Data\Loader;

use GFAPI;
use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\GF\Model\Form;

/**
 * Class - FormsLoader
 */
class FormsLoader extends AbstractDataLoader {
	/**
	 * Loader name. Same as the GraphQL Object.
	 *
	 * @var string
	 */
	public static string $name = 'gf_form';

	/**
	 * {@inheritDoc}
	 */
	protected function get_model( $entry, $key ): Form {
		return new Form( $entry );
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
	protected function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		$loaded_forms = [];
		foreach ( $keys as $key ) {
			$form = GFAPI::get_form( $key );

			// Run the form through `gform_pre_render` to support 3rd party plugins like Populate Anything.
			if ( ! empty( $form ) ) {
				$form = gf_apply_filters( [ 'gform_pre_render', $form['id'] ], $form );
			}

			$loaded_forms[ $key ] = $form ?: null;
		}

		return $loaded_forms;
	}
}
