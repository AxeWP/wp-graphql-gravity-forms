<?php
/**
 * Factory for Gravity Forms fields.
 *
 * @package Tests\WPGraphQL\GF\Factory
 */

namespace Tests\WPGraphQL\GF\Factory;

use GF_Field;
use GF_Fields;
use WP_UnitTest_Generator_Sequence;

/**
 * Class - Field
 */
class Field extends \WP_UnitTest_Factory_For_Thing {

	/**
	 * Constructor
	 *
	 * @param object $factory .
	 */
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'id'    => new WP_UnitTest_Generator_Sequence( '%s' ),
			'label' => new WP_UnitTest_Generator_Sequence( 'Field label %s' ),
		];
	}

	/**
	 * Creates a field object.
	 *
	 * @param array $args field arguments.
	 */
	public function create_object( $args ) {
		return GF_Fields::create( $args );
	}

	/**
	 * Creates multiple field objects.
	 *
	 * @param int   $count number to create.
	 * @param array $args field arguments.
	 * @param array $generation_definitions .
	 */
	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$fields = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$field_args = $args;
			$fields[]   = $this->create( $field_args );
		}

		return $fields;
	}

	/**
	 * Updates a field object.
	 *
	 * @param GF_Field $field .
	 * @param array    $args properties to update.
	 */
	public function update_object( $field, $args ) {
		foreach ( $args as $key => $value ) {
			$field->$key = $value;
		}
		return $field;
	}

	/**
	 * Get the field object. Returns itself.
	 *
	 * @param GF_Field $field .
	 */
	public function get_object_by_id( $field ) {
		return $field;
	}
}
