<?php
/**
 * Gravity Forms Testing factory.
 *
 * @package Tests\WPGraphQL\GravityForms\Factories
 */

namespace Tests\WPGraphQL\GravityForms\Factories;

/**
 * Class - Factory
 */
class Factory {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->form  = new Form();
		$this->field = new Field();
		$this->entry = new Entry();
		$this->draft = new DraftEntry();
	}
}
