<?php
/**
 * Factory for Gravity Forms entries.
 *
 * @package Tests\WPGraphQL\GF\Factory
 */

namespace Tests\WPGraphQL\GF\Factory;

use GFAPI;
use GFFormsModel;
use WP_UnitTest_Generator_Sequence;

/**
 * Class - Entry
 */
class Entry extends \WP_UnitTest_Factory_For_Thing {

	/**
	 * Constructor
	 *
	 * @param object $factory .
	 */
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'id'       => new WP_UnitTest_Generator_Sequence( '%s' ),
			'currency' => 'USD',
		];
	}

	/**
	 * Creates an entry object.
	 *
	 * @param array $args entry arguments.
	 */
	public function create_object( $args ) : int {
		return GFAPI::add_entry( $args );
	}

	/**
	 * Creates multiple entry objects.
	 *
	 * @param int   $count number to create.
	 * @param array $args entry arguments.
	 * @param array $generation_definitions .
	 */
	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$entry_ids = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$entry_args       = $args;
			$entry_args['id'] = $n + 1;

			$entry_ids[] = $this->create( $entry_args );
		}

		return $entry_ids;
	}

	/**
	 * Updates an entry object.
	 *
	 * @param int   $entry_id .
	 * @param array $properties properties to update.
	 */
	public function update_object( $entry_id, $properties ) {
		$result = true;

		foreach ( $properties as $key => $value ) {
			$result = GFAPI::updateEntryProperty( $entry_id, $key, $value );

			if ( ! $result ) {
				break;
			}
		}
		return $result;
	}

	/**
	 * Gets the entry object from an object id.
	 *
	 * @param int $entry_id .
	 */
	public function get_object_by_id( $entry_id ) {
		return GFAPI::get_entry( $entry_id );
	}

	/**
	 * Delete entries.
	 *
	 * @param array|string $entry_ids .
	 */
	public function delete( $entry_ids ) {
		if ( empty( $entry_ids ) ) {
			return;
		}
		if ( ! is_array( $entry_ids ) ) {
			$entry_ids = [ $entry_ids ];
		}
		return GFFormsModel::delete_entries( $entry_ids );
	}
}
