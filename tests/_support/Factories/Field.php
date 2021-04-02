<?php

namespace WPGraphQLGravityForms\Tests\Factories;

use GF_Fields;
use WP_UnitTest_Generator_Sequence;

class Field extends \WP_UnitTest_Factory_For_Thing {

	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'id'    => new WP_UnitTest_Generator_Sequence( '%n' ),
			'label' => new WP_UnitTest_Generator_Sequence( 'Field label %s' ),
		];
	}

	public function create_object( $args ) {
		return GF_Fields::create( $args );
	}

	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$fields = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$field_args = $args;
			$fields[]   = $this->create( $field_args );
		}

		return $fields;
	}

	public function update_object( $field, $args ) {
		foreach ( $args as $key => $value ) {
			$field->$key = $value;
		}
		return $field;
	}

	public function get_object_by_id( $field ) {
		return $field;
	}

}
