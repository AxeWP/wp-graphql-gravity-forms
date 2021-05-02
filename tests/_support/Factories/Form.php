<?php
/**
 * Factory for Gravity Forms forms.
 *
 * @package WPGraphQLGravityForms\Tests\Factories
 */

namespace WPGraphQLGravityForms\Tests\Factories;

use GFAPI;
use GFFormsModel;
use WP_UnitTest_Generator_Sequence;

/**
 * Class - Form
 */
class Form extends \WP_UnitTest_Factory_For_Thing {
	/**
	 * Constructor
	 *
	 * @param object $factory .
	 */
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = [
			'title'       => new WP_UnitTest_Generator_Sequence( 'Form title %s' ),
			'description' => new WP_UnitTest_Generator_Sequence( 'Form description %s' ),
			'fields'      => [],
		];
	}

	/**
	 * Creates a form object.
	 *
	 * @param array $args form arguments.
	 */
	public function create_object( $args ) {
		$form_id = GFAPI::add_form( $args );

		if ( ! isset( GFFormsModel::$unique_ids[ $form_id ] ) ) {
			GFFormsModel::$unique_ids[ $form_id ] = uniqid();
		}

		if ( ( array_key_exists( 'is_active', $args ) && ! $args['is_active'] ) || ! empty( $args['is_trash'] ) ) {
			$form = GFAPI::get_form( $form_id );
			$this->update_object(
				$form_id,
				array_merge(
					$form,
					[
						'is_active' => $args['is_active'] ?? 1,
						'is_trash'  => $args['is_trash'] ?? 0,
					]
				)
			);
		}
		return $form_id;
	}

	/**
	 * Creates multiple form objects.
	 *
	 * @param int   $count number to create.
	 * @param array $args form arguments.
	 * @param array $generation_definitions .
	 */
	public function create_many( $count, $args = [], $generation_definitions = null ) {
		$form_ids = [];
		for ( $n = 0; $n < $count; $n++ ) {
			$form_args  = $args;
			$form_ids[] = $this->create( $form_args );
		}

		return $form_ids;
	}

	/**
	 * Updates a form object.
	 *
	 * @param int   $form_id .
	 * @param array $args properties to update.
	 */
	public function update_object( $form_id, $args ) {
		$form       = GFAPI::get_form( $form_id );
		$form       = array_merge( $form, $args );
		$is_updated = GFAPI::update_form( $form, $form_id );
		if ( ! empty( $args['is_trash'] ) ) {
			$is_updated = GFFormsModel::trash_form( $form_id );
		}
		return $is_updated;
	}

	/**
	 * Gets the form object from an object id.
	 *
	 * @param int $form_id .
	 * @return array
	 */
	public function get_object_by_id( $form_id ) {
		return GFAPI::get_form( $form_id );
	}

	/**
	 * Delete forms.
	 *
	 * @param array|string $form_ids .
	 */
	public function delete( $form_ids ) {
		require_once \GFCommon::get_base_path() . '/form_display.php';

		if ( ! is_array( $form_ids ) ) {
			$form_ids = [ $form_ids ];
		}

		GFAPI::delete_forms( $form_ids );

		foreach ( $form_ids as $id ) {
			if ( isset( GFFormsModel::$unique_ids[ $id ] ) ) {
				unset( GFFormsModel::$unique_ids[ $id ] );
			}
			if ( isset( \GFFormDisplay::$submission[ $id ] ) ) {
				unset( \GFFormDisplay::$submission[ $id ] );
			}
		}
	}
}
