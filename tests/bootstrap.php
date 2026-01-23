<?php
// This is global bootstrap for autoloading

// Load field classes for tests since they're not loaded in GF 2.9 test environment.
require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-creditcard.php';
require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-image-choice.php';
require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-multiple-choice.php';
require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-password.php';
require_once __DIR__ . '/_data/plugins/gravityforms/includes/fields/class-gf-field-price.php';
