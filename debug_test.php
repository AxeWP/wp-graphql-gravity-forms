<?php
declare(strict_types=1);

// Simple script to check password field GraphQL type registration
echo "=== Checking PasswordField GraphQL type ===\n";

// Check if the type exists in the GraphQL registry
$type_registry = \WPGraphQL\Registry\TypeRegistry::get_default();

$reflection = new \ReflectionClass($type_registry);
$types_property = $reflection->getProperty('types');
$types_property->setAccessible(true);
$types = $types_property->getValue($type_registry);

if (isset($types['PasswordField'])) {
    echo "PasswordField type EXISTS in GraphQL registry\n";
} else {
    echo "PasswordField type DOES NOT EXIST in GraphQL registry\n";
    echo "Available field types containing 'password':\n";
    foreach (array_keys($types) as $type_name) {
        if (stripos($type_name, 'password') !== false) {
            echo "  - $type_name\n";
        }
    }
}
