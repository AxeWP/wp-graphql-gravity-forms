<?php
/**
 * Gravity Forms Testing factory.
 *
 * @package WPGraphQLGravityForms\Tests\Factories
 */

namespace WPGraphQLGravityForms\Tests\Factories;

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
