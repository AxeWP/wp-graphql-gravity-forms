<?php
// Simple script to check password field GraphQL type registration
echo "=== Checking Password field in GF_Fields::get_all() ===\n";

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/tests/_data/wordpress/');
}

// Load WordPress
require_once ABSPATH . 'wp-load.php';
require_once __DIR__ . '/tests/_data/plugins/gravityforms/gravityforms.php';

$fields = GF_Fields::get_all();
$password_field = null;

echo "Total fields returned: " . count($fields) . "\n\n";

foreach ($fields as $field) {
    if ($field->type === 'password') {
        $password_field = $field;
        echo "✓ Password field FOUND in GF_Fields::get_all()\n";
        echo "  Type: " . $field->type . "\n";
        echo "  Class: " . get_class($field) . "\n";
        echo "  Form editor button: ";
        var_dump($field->get_form_editor_button());
        break;
    }
}

if (!$password_field) {
    echo "✗ Password field NOT found in GF_Fields::get_all()\n";
}

echo "\n=== All field types ===\n";
$types = array_unique(array_map(function($f) { return $f->type; }, $fields));
sort($types);
foreach ($types as $type) {
    echo "  - $type\n";
}
