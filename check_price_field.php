<?php
// Load WordPress
require_once '/var/www/html/wp-load.php';

echo "=== Checking Price field in GF_Fields::get_all() ===\n";

$fields = GF_Fields::get_all();
$price_found = false;

foreach ($fields as $field) {
    if ($field->type === 'price') {
        $price_found = true;
        echo "✓ Price field FOUND in GF_Fields::get_all()\n";
        echo "  Type: " . $field->type . "\n";
        echo "  Class: " . get_class($field) . "\n";
        break;
    }
}

if (!$price_found) {
    echo "✗ Price field NOT found in GF_Fields::get_all()\n";
    echo "Available types: " . implode(', ', array_map(function($f) { return $f->type; }, $fields)) . "\n";
}

echo "class_exists('GF_Field_Price'): " . (class_exists('GF_Field_Price') ? 'true' : 'false') . "\n";
