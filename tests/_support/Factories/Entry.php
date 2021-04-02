<?php

namespace WPGraphQLGravityForms\Tests\Factories;

use GFAPI;
use WP_UnitTest_Generator_Sequence;

class Entry extends \WP_UnitTest_Factory_For_Thing {

	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'id' => new WP_UnitTest_Generator_Sequence( '%n' ),
		];
	}

	public function create_object( $args ) {
		return GFAPI::add_entry( $args );
	}

	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$entry_ids = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$entry_args       = $args;
			$entry_args['id'] = $n + 1;

			$entry_ids[] = $this->create( $entry_args );
		}

		return $entry_ids;
	}

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

	public function get_object_by_id( $form_id ) {
		return GFAPI::get_entry( $form_id );
	}
}
