<?php
/**
 * DataLoader - Forms
 *
 * Loads Models for Gravity Forms Forms.
 *
 * @package WPGraphQL\GF\Data\Loader
 * @since 0.0.1
 */

declare( strict_types = 1 );

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
	 * {@inheritDoc}
	 */
	protected function loadKeys( array $keys ) {
		if ( empty( $keys ) ) {
			return $keys;
		}

		$loaded_forms = [];
		foreach ( $keys as $key ) {
			if ( empty( $key ) ) {
				continue;
			}

			$form = GFAPI::get_form( (int) $key );

			// Run the form through `gform_pre_render` to support 3rd party plugins like Populate Anything.
			if ( ! empty( $form ) ) {
				$form = gf_apply_filters( [ 'gform_pre_render', $form['id'] ], $form );
			}

			$loaded_forms[ $key ] = $form ?: null;
		}

		return $loaded_forms;
	}
}
