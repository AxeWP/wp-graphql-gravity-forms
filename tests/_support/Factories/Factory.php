<?php

namespace WPGraphQLGravityForms\Tests\Factories;

class Factory {
	public function __construct() {
		$this->form  = new Form();
		$this->field = new Field();
		$this->entry = new Entry();
	}
}
