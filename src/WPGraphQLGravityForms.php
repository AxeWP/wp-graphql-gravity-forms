<?php

namespace WPGraphQLGravityForms;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types;

/**
 * Main plugin class.
 */
final class WPGraphQLGravityForms {
	/**
	 * Class instances.
	 */
    private $instances = [];

	/**
	 * Main method for running the plugin.
	 */
	public function run() {
		$this->create_instances();
		$this->register_hooks();
    }

	private function create_instances() {
        $this->instances['gravity_form']        = new Types\GravityForm;
        $this->instances['gravity_forms_entry'] = new Types\GravityFormsEntry;
    }

	private function register_hooks() {
		foreach ( $this->get_hookable_instances() as $instance ) {
            $instance->register_hooks();
        }
	}

	private function get_hookable_instances() {
        return array_filter( $this->instances, function( $instance ) {
            return $instance instanceof Hookable;
        } );
    }
}
